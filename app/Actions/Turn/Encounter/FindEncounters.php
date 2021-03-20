<?php

namespace App\Actions\Turn\Encounter;

use App\Models\Player;
use App\Models\PlayerRelation;
use App\Models\Shipyard;
use App\Models\Star;
use App\Services\PlayerRelationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\Game;
use Exception;

class FindEncounters
{

    /**
     * @function default encounter model
     * @param Star $star
     * @param Collection $shipyards
     * @return Collection
     */
    private function createEncounter (Star $star, Collection $shipyards): Collection
    {
        return collect([
            'star' => $star,
            'defender' => collect([]),
            'shipyards' => $shipyards,
            'attacker' => collect([])
        ]);
    }

    /**
     * @function get the encounters for a star
     * @param Star $star
     * @param Collection $gameRelations
     * @param Collection $shipyards
     * @param string $turnSlug
     * @return Collection
     */
    private function getStarEncounter (Star $star, Collection $gameRelations, Collection $shipyards, string $turnSlug): Collection
    {
        $p = new PlayerRelationService;

        Log::channel('turn')
            ->notice("$turnSlug checking star [".$star->owner->ticker."] $star->name.");
        // filter shipyards so we find out how many shipyards are located at this star
        $starShipyards = $shipyards->filter(function($shipyard) use ($star) {
            return $shipyard->star_id === $star->id;
        });
        Log::channel('turn')
            ->notice(
                "$turnSlug found ".count($star->fleets)
                ." fleets and ".count($starShipyards)." shipyards in system."
            );
        $encounter = $this->createEncounter($star, $starShipyards);

        foreach($star->fleets as $fleet) {
            // check fleet owner
            if ($star->owner->id !== $fleet->player_id) {
                $effectiveRelation = $p->getEffectiveRelation($fleet->player, $star->owner, $gameRelations);
                // hostile fleet, add to attacker collection
                if ($effectiveRelation === 0) {
                    Log::channel('turn')
                        ->notice("$turnSlug found hostile fleet [".$fleet->player->ticker."] $fleet->name.");
                    $encounter['attacker']->add($fleet);
                }
                // allied fleet, add to defender collection
                if ($effectiveRelation === 2) {
                    Log::channel('turn')
                        ->notice("$turnSlug found allied fleet [".$fleet->player->ticker."] $fleet->name.");
                    $encounter['defender']->add($fleet);
                }
            } else {
                // star owner fleet, add to defender
                Log::channel('turn')
                    ->notice("$turnSlug found fleet of star owner [".$fleet->player->ticker."] $fleet->name.");
                $encounter['defender']->add($fleet);
            }
        }

        // TODO: add ships to encounter!

        return $encounter;
    }

    /**
     * @function find out if any encounters need to be processed and trigger processing.
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    public function handle(Game $game, string $turnSlug)
    {
        $p = new \App\Actions\Turn\Encounter\ProcessEncounter;
        $gameRelations = PlayerRelation::where('game_id', $game->id)
            ->get();
        $stars = Star::where('game_id', '=', $game->id)
            ->whereNotNull('player_id')
            ->with('fleets')
            ->with('owner')
            ->get()
            ->filter(function($star) {
                // only check stars that have a fleet with a different owner than the star owner
                return $star->fleets->filter(function ($fleet) {
                    return $fleet->star->player_id !== $fleet->player_id;
                })->count() > 0;
            });
        $shipyards = Shipyard::where('game_id', $game->id)
            ->where('until_complete', '=', 0)
            ->get();

        Log::channel('turn')
            ->notice($turnSlug.' - found '.count($stars).' stars that have a potential encounter.');

        // main loop - check each star and see if there is an encounter.
        foreach($stars as $star) {
            $encounter = $this->getStarEncounter($star, $gameRelations, $shipyards, $turnSlug);
            if (count($encounter['attacker']) > 0) {
                Log::channel('turn')
                    ->notice("$turnSlug found valid encounter @ [".$star->owner->ticker."] $star->name.");
                $p->handle($game, $encounter, $turnSlug);
            } else {
                Log::channel('turn')
                    ->notice("$turnSlug no encounter @ [".$star->owner->ticker."] $star->name.");
            }
        }

    }

}
