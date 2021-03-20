<?php

namespace App\Actions\Game;

use App\Models\Game;
use App\Models\Planet;
use App\Models\Player;
use App\Models\Shipyard;
use App\Models\Star;
use App\Models\Turn;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StartGame
{

    /**
     * @function create initial turn
     * @param Game $game
     * @return Turn
     */
    private function createInitialTurn (Game $game): Turn
    {
        return Turn::create([
            'game_id' => $game->id,
            'number' => 0,
            'due' => Carbon::now()->addMinutes($game->turn_duration)
        ]);
    }

    /**
     * @function find out if the homesystem is missing resources.
     * @param Star $star
     * @return array
     */
    private function getMissingResourceSlots (Star $star): array
    {
        $planets = $star->planets;
        $min = config('rules.stars.minSlotsHome');
        $slots = $toAdd
            = array_combine(array_keys(config('rules.player.resourceTypes')), [0,0,0,0]);
        // loop planets and add the resourceSlots to "slots" array; so we have an array with the number of
        // each resource type
        foreach($planets as $planet) {
            foreach($planet->resources as $slot) {
                $slots[$slot['resourceType']] += $slot['slots'];
            }
        }
        // loop all resourceTypes and check if there are less than the minimum.
        // if so, remember this in $toAdd array
        foreach(array_keys($toAdd) as $slot) {
            $toAdd[$slot] = $slots[$slot] - $min[$slot] < 0
                ? $min[$slot] - $slots[$slot]
                : 0;
        }
        // filter the array before returning by eliminating resourceTypes
        // where no slots need to be added
        return array_filter($toAdd, function($slots) {
            return $slots > 0;
        });
    }

    /**
     * @function update the planets resources and add/update resource slots
     * @param array $toAdd
     * @param Planet $planet
     * @return array
     */
    private function addSlotsToColony (Array $toAdd, Planet $planet): array
    {
        $resources = collect($planet->resources);
        // loop the types where we need to add slots.
        foreach($toAdd as $type => $num) {
            // do the planet resources already have the resourceSlot to add?
            if ($resources->contains('resourceType', $type)) {
                // add the correct number.
                $resources = $resources->map(function($res) use ($type, $num) {
                    if ($res['resourceType'] === $type) $res['slots'] += $num;
                    return $res;
                });
            } else {
                // if the planet resources do not yet have a resourceSlot of the type to add
                // push a new entry.
                $resources->push([
                    'resourceType' => $type,
                    'slots' => $num,
                    'value' => 1
                ]);
            }
        }
        return $resources->toArray();
    }

    /**
     * @function select the starting colony from the planets of a star
     * by scoring resourceslots/values
     * @param Star $star
     * @param Player $player
     * @return void
     */
    public function seedPlayerColony (Star $star, Player $player)
    {
        $planets = $star->planets()->get();
        $highest = 0;
        $highestId = 0;
        foreach($planets as $planet) {
            $resourceScores = array_map(function($p) {
                return $p['slots'] * $p['value'];
            }, $planet->resources);
            $score = array_reduce($resourceScores, function($carry, $score) {
                return $carry += $score;
            });
            $planet['score'] = $score ? $score : 0;
            if ($score > $highest) {
                $highest = $score;
                $highestId = $planet->id;
            }
        }
        $colony = Planet::find($highestId);
        $colony->population = 10;

        // check if the homesystem has sufficient resources
        $toAdd = $this->getMissingResourceSlots($star);
        if (array_sum($toAdd) > 0) {
            Log::channel('game')->info("Home System of Empire $player->ticker does not have sufficient resourceSlots, missing: ".json_encode($toAdd, JSON_PRETTY_PRINT));
            $colony->resources = $this->addSlotsToColony($toAdd, $colony);
            Log::channel('game')->info("Colony after manipulation: ".json_encode($colony->resources, JSON_PRETTY_PRINT));
        } else {
            Log::channel('game')->info("$player->ticker has sufficient resources.");
        }

        $colony->save();
        Log::channel('game')->info("Chose Planet $colony->orbital_index as starting colony for empire $player->ticker.");

        // create starting shipyard
        $shipyard = Shipyard::create([
            'planet_id' => $colony->id,
            'star_id' => $colony->star->id,
            'game_id' => $star->game->id,
            'player_id' => $player->id,
            'type' => array_keys(config('rules.shipyards.hullTypes'))[0],
            'until_complete' => 0,
            'notified' => true
        ]);
        Log::channel('game')->info("Created starting shipyard for player $player->ticker ".json_encode($shipyard, JSON_PRETTY_PRINT));

        // TODO: Ark blueprint, Destroyer Blueprint? Ships? Home Fleets.
    }

    /**
     * @function seed player colonies.
     * @param $game
     * @throws \Exception
     * @return void
     */
    public function seedPlayerStars ($game) {
        Log::channel('game')->notice('Seeding player starting colonies.');
        $players = $game->players;
        $homeSystems = $game->stars->filter(function ($value) {
            return $value['home_system'] === true;
        });
        foreach($players as $player) {
            // determine which of the available systems will be player starting system
            $systemIndex = random_int(0, count($homeSystems) - 1);
            $playerHome = $homeSystems->values()->get($systemIndex);
            // remove the selected system from collection
            $homeSystems = $homeSystems->reject(function($system) use ($playerHome) {
                return $system->id === $playerHome->id;
            });
            $playerHome->player_id = $player->id;
            $playerHome->save();
            Log::channel('game')->info("Chose Star $playerHome->name as home system for empire $player->ticker.");
            $this->seedPlayerColony($playerHome, $player);
        }
    }

    /**
     * @function handle game start
     * @param  Game $game
     * @throws \Exception
     * @return void
     */
    public function handle(Game $game)
    {
        $game->processing = true;
        $game->can_enlist = false;
        $game->save();
        Log::channel('game')->info('Set g'.$game->number.' meta data to \'processing\'=true, \'can_enlist\'=false.');
        // create a turn
        $turn = $this->createInitialTurn($game);
        Log::channel('game')->info('created turn t'.$turn->number.' for g'.$game->number);
        Log::channel('game')->notice('Selecting starting systems for all enlisted players.');
        $this->seedPlayerStars($game);
        Log::channel('game')->notice('Finished selecting starting systems for all enlisted players.');
        // save game after all processing is done.
        $game->processing = false;
        $game->map = null;
        $game->active = true;
        $game->save();
        Log::channel('game')->info('Unset \'processing\'. for g'.$game->number);
        Log::channel('game')->notice('Finished starting game g'.$game->number);
    }
}
