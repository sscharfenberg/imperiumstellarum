<?php

namespace App\Actions\Game;

use App\Models\Game;
use App\Models\Turn;
use Exception;
use Illuminate\Support\Facades\Log;

class ProcessTurn
{


    /**
     * create a new turn or not
     * set this to "true" if you want to test something and not actually write to db
     * @var bool
     */
    private $dryRun = false;

    /**
     * @function create the new turn
     * @param Game $game
     * @param Turn $turn
     * @return void
     */
    private function createNewTurn (Game $game, Turn $turn)
    {
        try {
            Log::channel('turn')->info('TURN PROCESSING: creating new turn for g'.$game->number.'.');
            Turn::create([
                'game_id' => $game->id,
                'number' => $turn->number + 1,
                'due' => now()->addMinutes($game->turn_duration)
            ]);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    "g".$game->number."t".$turn->number." Exception while attempting to create new turn:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
    }

    /**
     * @function call BuildStorageUpgrades
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    private function processStorageUpgrades(Game $game, string $turnSlug)
    {
        try {
            Log::channel('turn')->info("$turnSlug - STEP 1: Storage Upgrades.");
            $s = new \App\Actions\Turn\BuildStorageUpgrades;
            $s->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    $turnSlug." Exception while attempting to process storage upgrades:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
    }

    /**
     * @function call harvester processing
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    private function processHarvesters(Game $game, string $turnSlug)
    {
        try {
            Log::channel('turn')->info("$turnSlug - STEP 2: Process Harvesters.");
            $h = new \App\Actions\Turn\ProcessHarvesters;
            $h->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    $turnSlug." Exception while attempting to process harvesters:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
    }

    /**
     * @function call populationGrowth
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    private function handleColonies(Game $game, string $turnSlug)
    {
        try {
            Log::channel('turn')->info("$turnSlug - STEP 3: Handle Colonies.");
            $c = new \App\Actions\Turn\ProcessColonies;
            $c->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    $turnSlug." Exception while attempting to handle colonies:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
    }

    /**
     * @function process shipyard builds
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    private function processShipyards(Game $game, string $turnSlug)
    {
        try {
            Log::channel('turn')->info("$turnSlug - STEP 4: Build Shipyards.");
            $s = new \App\Actions\Turn\BuildShipyards;
            $s->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    $turnSlug." Exception while attempting to build shipyards:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
    }

    /**
     * @function process research
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    private function processResearch(Game $game, string $turnSlug)
    {
        try {
            Log::channel('turn')->info("$turnSlug - STEP 5: PROCESS RESEARCH.");
            $s = new \App\Actions\Turn\ProcessResearch;
            $s->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    $turnSlug." Exception while attempting to process research:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
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
        try {
            Log::channel('turn')->info("$turnSlug - STEP 6: BUILD SHIPS.");
            $s = new \App\Actions\Turn\BuildShips;
            $s->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    $turnSlug." Exception while attempting to build ships:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
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
        try {
            Log::channel('turn')->info("$turnSlug - STEP 7: MOVE FLEETS.");
            $s = new \App\Actions\Turn\ProcessFleetMovement;
            $s->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    $turnSlug." Exception while attempting to move fleets:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
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
        try {
            Log::channel('turn')->info("$turnSlug - STEP 8: CHANGE PLAYER RELATIONS.");
            $s = new \App\Actions\Turn\ProcessPlayerRelations;
            $s->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    $turnSlug." Exception while attempting to change player relations:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
    }

    /**
     * @function process encounters (fleet combat)
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    private function processEncounters (Game $game, string $turnSlug)
    {
        try {
            Log::channel('turn')->info("$turnSlug - STEP 9: PROCESS FLEET ENCOUNTERS.");
            $s = new \App\Actions\Turn\Encounter\FindEncounters;
            $s->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('encounter')
                ->error(
                    $turnSlug." Exception while attempting to process fleet encounters:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
    }

    /**
     * @function process colonization of unclaimed stars
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    private function processColonization (Game $game, string $turnSlug)
    {
        try {
            Log::channel('turn')->info("$turnSlug - STEP 10: PROCESS COLONIZATION OF UNCLAIMED STARS.");
            $s = new \App\Actions\Turn\ProcessColonization;
            $s->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    $turnSlug." Exception while attempting to process colonization of unclaimed stars:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
    }

    /**
     * @function process ship regeneration: natural shield regeneration, armour/structure repair at shipyards
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    private function processShipRegen (Game $game, string $turnSlug)
    {
        try {
            Log::channel('turn')->info("$turnSlug - STEP 11: PROCESS SHIP REGENERATION.");
            $s = new \App\Actions\Turn\ProcessShipRegen;
            $s->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    $turnSlug." Exception while attempting to process ship regen:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
    }

    /**
     * @function pcoess dead players - check for players fit the criteria, mark and notify them
     * @param Game $game
     * @param string $turnSlug
     */
    private function processDeadPlayers (Game $game, string $turnSlug)
    {
        try {
            Log::channel('turn')->info("$turnSlug - STEP 12: PROCESS DEAD PLAYERS.");
            $s = new \App\Actions\Turn\ProcessDeadPlayers;
            $s->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    $turnSlug." Exception while attempting to process dead players:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
    }

    /**
     * @function pcoess winners - check if any player has won the game and react accordingly
     * @param Game $game
     * @param string $turnSlug
     */
    private function processWinners (Game $game, string $turnSlug)
    {
        try {
            Log::channel('turn')->info("$turnSlug - STEP 13: PROCESS WINNERS.");
            $s = new \App\Actions\Turn\ProcessWinners;
            $s->handle($game, $turnSlug);
        } catch (Exception $e) {
            Log::channel('turn')
                ->error(
                    $turnSlug." Exception while attempting to process winners:\n"
                    .$e->getMessage()."\n".$e->getTraceAsString()
                );
        }
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
        $turnSlug = 'g'.$game->number.'t'.($turn->number + 1);
        Log::channel('turn')->info("TURN PROCESSING $turnSlug - START");
        if (!$this->dryRun) {
            $game->processing = true;
            $game->save();
        }

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
        // #9 resolve encounters
        $this->processEncounters($game, $turnSlug);
        // #10 colonize star system
        $this->processColonization($game, $turnSlug);
        // #11 shield regen, ship repairs
        $this->processShipRegen($game, $turnSlug);
        // #12 process dead players
        $this->processDeadPlayers($game, $turnSlug);
        // #13 process winners
        $this->processWinners($game, $turnSlug);

        // #final: cleanup
        if (!$this->dryRun) {
            $updatedGame = Game::where('id', '=', $game->id)->first();
            $turn->processed = now();
            $turn->save();
            if (!$updatedGame->finished) {
                $this->createNewTurn($game, $turn);
            }
            $game->processing = false;
            $game->save();
            // log execution time of turn processing.
            $execution = hrtime(true) - $start;
            Log::channel('turn')->info("TURN PROCESSING $turnSlug - finished in ".$execution/1e+9." seconds.");
        }
    }
}
