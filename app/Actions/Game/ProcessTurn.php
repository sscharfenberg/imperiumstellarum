<?php

namespace App\Actions\Game;

use App\Models\Game;
use App\Models\Turn;
use Illuminate\Support\Facades\Log;

class ProcessTurn
{

    /**
     * @function create the new turn
     * @param Game $game
     * @param Turn $turn
     * @return Turn
     */
    private function createNewTurn (Game $game, Turn $turn)
    {
        return Turn::create([
            'game_id' => $game->id,
            'number' => $turn->number + 1,
            'due' => now()->addMinutes($game->turn_duration)
        ]);
    }


    /**
     * @function call BuildStorageUpgrades
     * @param Game $game
     * @param Turn $turn
     * @return void
     */
    private function processStorageUpgrades(Game $game, Turn $turn)
    {
        Log::info('TURN PROCESSING: g'.$game->number.'t'.$turn->number.', STEP 1: Storage Upgrades.');
        $s = new \App\Actions\Turn\BuildStorageUpgrades;
        $s->handle($game);
    }


    /**
     * @function call harvester processing
     * @param Game $game
     * @param Turn $turn
     * @return void
     */
    private function processHarvesters(Game $game, Turn $turn)
    {
        Log::info('TURN PROCESSING: g'.$game->number.'t'.$turn->number.', STEP 2: Process Harvesters.');
        $s = new \App\Actions\Turn\ProcessHarvesters;
        $s->handle($game);
    }


    /**
     * @function call populationGrowth
     * @param Game $game
     * @param Turn $turn
     * @return void
     */
    private function handleColonies(Game $game, Turn $turn)
    {
        Log::info('TURN PROCESSING: g'.$game->number.'t'.$turn->number.', STEP 3: Population Growth.');
        $s = new \App\Actions\Turn\Colonies;
        $s->handle($game);
    }


    /**
     * @function handle game start
     * @param  Game $game
     * @param Turn $turn
     * @throws \Exception
     * @return void
     */
    public function handle(Game $game, Turn $turn)
    {
        $start = hrtime(true);
        Log::info('TURN PROCESSING: g'.$game->number.'t'.$turn->number.'.');
        $game->processing = true;
        $game->save();
        // #1 process storage upgrades
        $this->processStorageUpgrades($game, $turn);
        // #2 process harvesters
        $this->processHarvesters($game, $turn);
        // #3 population growth
        $this->handleColonies($game, $turn);

        // ...


        // #final: cleanup
        $turn->processed = now();
        $turn->save();
        $this->createNewTurn($game, $turn);
        $game->processing = false;
        $game->save();

        // log execution time of turn processing.
        $execution = hrtime(true) - $start;
        Log::info('TURN PROCESSING: g'.$game->number.'t'.$turn->number.' finished in '.$execution/1e+9.' seconds.');
    }
}
