<?php

namespace App\Actions\Game;



use App\Models\Game;
use App\Models\Planet;
use App\Models\Star;
use App\Models\Turn;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\SeedGameService;

class StartGame
{

    /**
     * @function create initial turn
     * @param Game $game
     * @return Turn
     */
    private function createInitialTurn (Game $game)
    {
        return Turn::create([
            'game_id' => $game->id,
            'number' => 0,
            'due' => Carbon::now()->addMinutes($game->turn_duration)
        ]);
    }

    /**
     * @function select the starting colony from the planets of a star
     * by scoring resourceslots/values
     * @param Star $star
     */
    public function seedPlayerColony (Star $star)
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
        $colony->save();
        Log::info('Chose Planet '.$colony->orbital_index.' as starting colony.');
        // TODO: starting shipyard
    }

    /**
     * @function seed player colonies.
     * @param $game
     * @throws \Exception
     */
    private function seedPlayerStars ($game) {
        Log::notice('Seeding player starting colonies.');
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
            $playerHome->owner_id = $player->id;
            $playerHome->save();
            Log::info('Chose Star '.$playerHome->name.' as player starting system.');
            $this->seedPlayerColony($playerHome);
        }
    }

    /**
     * @function handle game start
     * @param  Game $game
     * @throws \Exception
     */
    public function handle(Game $game)
    {
        $game->processing = true;
        $game->can_enlist = false;
        $game->save();
        Log::info('Set g'.$game->number.' meta data to \'processing\'=true, \'can_enlist\'=false.');
        // create a turn
        $turn = $this->createInitialTurn($game);
        Log::info('created turn t'.$turn->number.' for g'.$game->number);
        Log::notice('Selecting starting systems for all enlisted players.');
        $this->seedPlayerStars($game);
        Log::notice('Finished selecting starting systems for all enlisted players.');
        $game->processing = false;
        $game->map = null;
        $game->save();
        Log::info('Unset \'processing\'. for g'.$game->number);
        Log::notice('Finished starting game g'.$game->number);
    }
}
