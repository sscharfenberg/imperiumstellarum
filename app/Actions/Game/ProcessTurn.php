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
     * @param string $turnSlug
     * @return void
     */
    private function processStorageUpgrades(Game $game, string $turnSlug)
    {
        Log::info("TURN PROCESSING $turnSlug - STEP 1: Storage Upgrades.");
        $s = new \App\Actions\Turn\BuildStorageUpgrades;
        $s->handle($game, $turnSlug);
    }

    /**
     * @function call harvester processing
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    private function processHarvesters(Game $game, string $turnSlug)
    {
        Log::info("TURN PROCESSING $turnSlug - STEP 2: Process Harvesters.");
        $h = new \App\Actions\Turn\ProcessHarvesters;
        $h->handle($game, $turnSlug);
    }

    /**
     * @function call populationGrowth
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    private function handleColonies(Game $game, string $turnSlug)
    {
        Log::info("TURN PROCESSING $turnSlug - STEP 3: Population Growth.");
        $c = new \App\Actions\Turn\ProcessColonies;
        $c->handle($game, $turnSlug);
    }

    /**
     * @function process shipyard builds
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    private function processShipyards(Game $game, string $turnSlug)
    {
        Log::info("TURN PROCESSING $turnSlug - STEP 4: Build Shipyards.");
        $s = new \App\Actions\Turn\BuildShipyards;
        $s->handle($game, $turnSlug);
    }

    /**
     * @function process research
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    private function processResearch(Game $game, string $turnSlug)
    {
        Log::info("TURN PROCESSING $turnSlug - STEP 5: PROCESS RESEARCH.");
        $s = new \App\Actions\Turn\ProcessResearch;
        $s->handle($game, $turnSlug);
    }

    /**
     * @function build ships
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    private function buildships(Game $game, string $turnSlug)
    {
        Log::info("TURN PROCESSING $turnSlug - STEP 6: BUILD SHIPS.");
        $s = new \App\Actions\Turn\BuildShips;
        $s->handle($game, $turnSlug);
    }

    /**
     * @function move fleets
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    private function moveFleets(Game $game, string $turnSlug)
    {
        Log::info("TURN PROCESSING $turnSlug - STEP 7: MOVE FLEETS.");
        $s = new \App\Actions\Turn\ProcessFleetMovement;
        $s->handle($game, $turnSlug);
    }

    /**
     * @function move fleets
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    private function changePlayerRelations(Game $game, string $turnSlug)
    {
        Log::info("TURN PROCESSING $turnSlug - STEP 8: CHANGE PLAYER RELATIONS.");
        $s = new \App\Actions\Turn\ProcessPlayerRelations;
        $s->handle($game, $turnSlug);
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
        $turnSlug = 'g'.$game->number.'t'.$turn->number;
        Log::info("TURN PROCESSING $turnSlug - START");
        $game->processing = true;
        $game->save();

        // #1 process storage upgrades
        $this->processStorageUpgrades($game, $turnSlug);
        // #2 process harvesters
        $this->processHarvesters($game, $turnSlug);
        // #3 population growth
        $this->handleColonies($game, $turnSlug);
        // #4 build shipyards
        $this->processShipyards($game, $turnSlug);
        // #5 do research
        $this->processResearch($game, $turnSlug);
        // #6 build ships
        $this->buildships($game, $turnSlug);
        // #7 move fleets
        $this->moveFleets($game, $turnSlug);
        // #8 change diplomatic relations
        $this->changePlayerRelations($game, $turnSlug);
        // #9 resolve fleet combat
        // #10 colonize star system
        // #11 change system ownership
        // #12 process dead players
        // #13 process winners
        // ...


        // #final: cleanup
        $turn->processed = now();
        $turn->save();
        $this->createNewTurn($game, $turn);
        $game->processing = false;
        $game->save();

        // log execution time of turn processing.
        $execution = hrtime(true) - $start;
        Log::info("TURN PROCESSING $turnSlug - finished in ".$execution/1e+9." seconds.");
    }
}
