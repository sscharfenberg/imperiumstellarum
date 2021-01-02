<?php

namespace App\Actions\Game;

use App\Models\Game;
use App\Models\Turn;
use Exception;
use Illuminate\Support\Facades\Log;

class ProcessTurn
{

    /**
     * @function create the new turn
     * @param Game $game
     * @param Turn $turn
     * @return Turn
     */
    private function createNewTurn (Game $game, Turn $turn): Turn
    {
        Log::info('TURN PROCESSING: creating new turn for g'.$game->number.'.');
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
        $h = new \App\Actions\Turn\ProcessHarvesters;
        $h->handle($game);
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
        $c = new \App\Actions\Turn\Colonies;
        $c->handle($game);
    }

    /**
     * @function process shipyard builds
     * @param Game $game
     * @param Turn $turn
     * @return void
     */
    private function processShipyards(Game $game, Turn $turn)
    {
        Log::info('TURN PROCESSING: g'.$game->number.'t'.$turn->number.', STEP 4: Build Shipyards.');
        $s = new \App\Actions\Turn\BuildShipyards;
        $s->handle($game);
    }

    /**
     * @function process research
     * @param Game $game
     * @param Turn $turn
     * @return void
     */
    private function processResearch(Game $game, Turn $turn)
    {
        Log::info('TURN PROCESSING: g'.$game->number.'t'.$turn->number.', STEP 5: PROCESS RESEARCH.');
        $s = new \App\Actions\Turn\ProcessResearch;
        $s->handle($game);
    }

    /**
     * @function build ships
     * @param Game $game
     * @param Turn $turn
     * @throws Exception
     * @return void
     */
    private function buildships(Game $game, Turn $turn)
    {
        Log::info('TURN PROCESSING: g'.$game->number.'t'.$turn->number.', STEP 6: BUILD SHIPS.');
        $s = new \App\Actions\Turn\BuildShips;
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
        // #4 build shipyards
        $this->processShipyards($game, $turn);
        // #5 do research
        $this->processResearch($game, $turn);
        // #6 build ships
        $this->buildships($game, $turn);


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
