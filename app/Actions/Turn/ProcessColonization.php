<?php

namespace App\Actions\Turn;

use App\Models\Fleet;
use App\Models\FleetMovement;
use App\Models\Game;
use App\Models\Planet;
use App\Models\Player;
use App\Models\Star;

use App\Services\EncounterService;
use App\Services\FleetService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;

use App\Services\MessageService;
use Illuminate\Support\Str;


class ProcessColonization
{


    /**
     * @function notify fleet owners of the incident
     * @param Star $star
     * @param string $newOwnerId
     * @param Collection $otherFleetOwners
     * @param string $turnSlug
     */
    private function notifyFleetOwners (Star $star, string $newOwnerId, Collection $otherFleetOwners, string $turnSlug)
    {
        $m = new MessageService;
        // notify new owner.
        $newOwner = Player::where('game_id', '=', $star->game_id)
            ->where('id', '=', $newOwnerId)
            ->first();
        $m->sendNotification(
            $newOwner,
            'game.messages.sys.colonization.won.subject',
            'game.messages.sys.colonization.won.body',
            [
                'star' => $star->name." (".$star->coord_x."/".$star->coord_y.")"
            ]
        );
        Log::channel('turn')->notice("$turnSlug send notification to [$newOwner->ticker] $newOwner->name.");

        // notify the other players that have not colonized the star due to fleet score.
        $players = Player::where('game_id', '=', $star->game_id)
            ->whereIn('id', $otherFleetOwners)
            ->get();
        $players->each( function($player) use ($star, $m, $newOwner, $turnSlug) {
            $m->sendNotification(
                $player,
                'game.messages.sys.colonization.lost.subject',
                'game.messages.sys.colonization.lost.body',
                [
                    'star' => $star->name." (".$star->coord_x."/".$star->coord_y.")",
                    'empire' => "[$newOwner->ticker] $newOwner->name"
                ]
            );
            Log::channel('turn')->notice("$turnSlug send notification to [$player->ticker] $player->name.");
        });
    }


    /**
     * @function fleets that have not been able to colonize the star retreat home
     * @param Star $star
     * @param Collection $fleets
     * @param Collection $playerIds
     * @param string $turnSlug
     */
    private function retreatLosingFleets (Star $star, Collection $fleets, Collection $playerIds, string $turnSlug)
    {
        $e = new EncounterService;
        $fl = new FleetService;
        $players = Player::where('game_id', '=', $star->game_id)
            ->whereIn('id', $playerIds)
            ->get();
        // loop the players
        $players->each( function ($player) use ($e, $fl, $fleets, $star, $turnSlug) {
            $playerFleetIds = $fleets->map( function ($fleet) { return $fleet->id; });
            $playerFleets = Fleet::where('game_id', '=', $star->game_id)
                ->where('player_id', '=', $player->id)
                ->whereIn('id', $playerFleetIds); // keep query open so we can update
            $destination = $e->getHomeDestination($player);
            $travelTime = $fl->travelTime($star, $destination);
            // update fleets star_id to null
            $playerFleets->update(['star_id' => null]);
            // create fleet movements
            $movements = $playerFleets->get()->map(function ($fleet) use ($player, $destination, $travelTime) {
                return [
                    'id' => Str::uuid(),
                    'game_id' => $fleet->game_id,
                    'player_id' => $player->id,
                    'fleet_id' => $fleet->id,
                    'star_id' => $destination->id,
                    'until_arrival' => $travelTime,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            })->toArray();
            FleetMovement::insert($movements);
            Log::channel('turn')->notice(
                "$turnSlug fleets from [$player->ticker] $player->name are retreating to "
                .$destination->name." ($destination->coord_x/$destination->coord_y)"
            );
        });
    }

    /**
     * @function colonize star: remove ark, change population on highest scoring planet, change player_id of star
     * @param Star $star
     * @param string $newOwnerId
     * @param Collection $arks
     * @param string $turnSlug
     */
    private function colonizeStar (Star $star, string $newOwnerId, Collection $arks, string $turnSlug)
    {
        $planets = $star->planets;
        // score planets based solely on resources.
        $planetScores = collect();
        $planets->each( function ($planet) use (&$planetScores) {
            $resourceScores = collect($planet->resources)->map( function ($type) {
                return $type['slots'] * $type['value'];
            });
            $planetScores->put($planet->id, $resourceScores->reduce(function ($carry, $item) {
                return $carry + $item;
            }));
        });
        // key of highest scoring planet.
        $colonyId = $planetScores->sortDesc()->take(1)->keys()->first();
        // calculate new population of colony
        $population = collect(config('rules.modules'))->filter( function ($mod) {
            return $mod['hullType'] === 'ark' && $mod['techType'] === 'colony';
        })->first()['costs']['population'] * config('rules.planets.population.newColonyFactor');

        // remove ark that is used to create colony
        $colonyArk = $arks->random();
        $colonyArk->delete();
        Log::channel('turn')->notice("$turnSlug ark $colonyArk->name was deleted.");

        // update planet with new colony
        Planet::where('game_id', '=', $star->game_id)
            ->where('id', '=', $colonyId)
            ->update(['population' => $population]);
        Log::channel('turn')->notice("$turnSlug planet #$colonyId population set to $population.");

        // change player_id on star
        $star->player_id = $newOwnerId;
        $star->save();
        $newOwner = $star->owner;
        Log::channel('turn')->notice(
            "$turnSlug star #$star->id '$star->name' ($star->coord_x/$star->coord_y)"
            ." player_id set to #$newOwner->id. Successfully claimed by player #"
            .$newOwnerId." [$newOwner->ticker]."
        );
    }

    /**
     * @function check the star, count the number of fleets with arks in them.
     * @param Star $star
     * @param string $turnSlug
     */
    private function handleStar (Star $star, string $turnSlug)
    {
        $fleets = Fleet::where('game_id', '=', $star->game_id)
            ->where('star_id', '=', $star->id)
            ->get();
        // only fleets with arks can colonize planets
        // prepare playerScore collection with playerId as key. this is by default unique since a key can only exist once.
        $playerScore = $fleets->filter( function ($fleet) {
            return count (
                    $fleet->ships->filter( function($ship) {
                        return $ship->hull_type === 'ark' && $ship->colony;
                    })
                ) > 0;
        })->mapWithKeys( function ($fleet) {
            return [$fleet->player_id => 0];
        });
        // loop fleets and increase player score according to ships
        $fleets->each( function ($fleet) use (&$playerScore) {
            $currentCount = $playerScore->get($fleet['player_id']);
            $currentCount += config('rules.encounters.ownerChange.ark') * count($fleet['ships']->filter(function($ship) {
                return $ship['hull_type'] === 'ark';
            }));
            $currentCount += config('rules.encounters.ownerChange.small') * count($fleet['ships']->filter(function($ship) {
                return $ship['hull_type'] === 'small';
            }));
            $currentCount += config('rules.encounters.ownerChange.medium') * count($fleet['ships']->filter(function($ship) {
                return $ship['hull_type'] === 'medium';
            }));
            $currentCount += config('rules.encounters.ownerChange.large') * count($fleet['ships']->filter(function($ship) {
                return $ship['hull_type'] === 'large';
            }));
            $currentCount += config('rules.encounters.ownerChange.xlarge') * count($fleet['ships']->filter(function($ship) {
                return $ship['hull_type'] === 'xlarge';
            }));
            $playerScore->put($fleet['player_id'], $currentCount);
        });
        Log::channel('turn')->notice(
            "$turnSlug star #$star->id '$star->name' ($star->coord_x/$star->coord_y)"
            ." player scores: ".json_encode($playerScore, JSON_PRETTY_PRINT)
        );
        $newOwnerId = $playerScore->sortDesc()->take(1)->keys()->first();
        // get fleets that are not from the winner (they will need to be retreated back)
        $otherFleets = $fleets->filter( function ($fleet) use ($newOwnerId) {
            return $fleet->player_id !== $newOwnerId;
        });
        // prepare "otherFleetOwners" collection - players that where present, but didn't get the star due to score.
        $otherFleetOwners = $otherFleets->map( function ($fleet) {
            return $fleet->player_id;
        })->unique()->values();

        // collect winning player's arks
        $newOwnerFleets = $fleets->filter( function ($fleet) use ($newOwnerId) {
            return $fleet->player_id === $newOwnerId;
        });
        $newOwnerArks = collect();
        $newOwnerFleets->each( function ($fleet) use (&$newOwnerArks) {
            $newOwnerArks = $newOwnerArks->concat(
                $fleet->ships->filter( function ($ship) {
                    return $ship->hull_type === 'ark' && $ship->colony;
                })
            );
        });

        // actually colonize star system
        $this->colonizeStar($star, $newOwnerId, $newOwnerArks, $turnSlug);

        // fleets that are not owned by the player that colonized the system will retreat back.
        if (count($otherFleetOwners) > 0) {
            $this->retreatLosingFleets($star, $otherFleets, $otherFleetOwners, $turnSlug);
        }

        // send notifications to new owner and to other players
        $this->notifyFleetOwners($star, $newOwnerId, $otherFleetOwners, $turnSlug);
    }


    /**
     * @function build ships
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    public function handle(Game $game, string $turnSlug)
    {
        // find unclaimed stars with at least one fleet at the star
        $contestedStars = Star::where('game_id', '=', $game->id)
            ->whereNull('player_id')
            ->has('fleets')
            ->get();

        if ($contestedStars->count() > 0) {
            $contestedStars->each(function ($star) use ($turnSlug) {
                Log::channel('turn')->notice(
                    "$turnSlug star #$star->id '$star->name' ($star->coord_x/$star->coord_y)"
                    ." is unclaimed and has a fleet in system."
                );
                $this->handleStar($star, $turnSlug);
            });
        } else {
            Log::channel('turn')->notice(
                "$turnSlug no unclaimed stars with at least one fleet in system."
            );
        }

    }


}
