<?php

namespace App\Actions\Turn\Encounter;

use App\Models\Game;
use App\Services\EncounterService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessEncounter
{

    use UsesEncounterLogging;

    /**
     * @function main function for processing encounters
     * @param Collection $encounter
     * @param string $turnSlug
     */
    private function processEncounter (Collection $encounter, string $turnSlug)
    {
        $turn = 0;
        $setupTurn = $this->createNewTurn($encounter, $turn);
        $this->updateTurnFleetData($encounter, $setupTurn);

        // main turn loop
        while(!$encounter['resolved']) {
            $turn++;
            Log::channel('encounter')
                ->notice("\n\n$turnSlug #".$encounter['id']." start processing turn $turn.\n");

            echo "\nTurn $turn\n\n";

            // add new turn to encounter log and database
            $encounterTurn = $this->createNewTurn($encounter, $turn);
            $encounter['turns']->push([
                $encounterTurn->turn => $encounterTurn->id
            ]);

            // 1) determine turn order
            $t = new \App\Actions\Turn\Encounter\ProcessEncounterTurnOrder;
            $encounter = $t->handle($encounter, $turnSlug, $encounterTurn);

            // 2) Move Fleets
            $m = new \App\Actions\Turn\Encounter\ProcessEncounterMovement;
            $encounter = $m->handle($encounter, $turnSlug, $encounterTurn);

            // 3) Process Damage
            $d = new \App\Actions\Turn\Encounter\ProcessEncounterDamage;
            $encounter = $d->handle($encounter, $turnSlug, $encounterTurn);

            // 4) Cleanup: remove dead ships/fleets, update target queues if targets are dead.
            $k = new \App\Actions\Turn\Encounter\ProcessEncounterCleanup;
            $encounter = $k->handle($encounter, $turnSlug, $encounterTurn);

            // 5) check if encounter ends (no attacker or defender ships). this sets $encounter['resolved'] if needed.
            $e = new \App\Actions\Turn\Encounter\ProcessEncounterEndCheck;
            $encounter = $e->handle($encounter, $turnSlug, $encounterTurn);

            // update encounter turn data
            $this->updateTurnFleetData($encounter, $encounterTurn);
        }

        Log::channel('encounter')
            ->notice(
                "$turnSlug end processing of #".$encounter['id']." at ["
                .$encounter['star']['owner']->ticker."] "
                .$encounter['star']['name']
            );

        $encounter = null;

    }


    /**
     * @function find out if any encounters need to be processed and trigger processing.
     * @param Collection $encounter
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    public function handle(Collection $encounter, string $turnSlug)
    {
        $e = new EncounterService;
        // tons of logging.
        Log::channel('encounter')
            ->notice(
                "$turnSlug start processing of #".$encounter['id']." at ["
                .$encounter['star']['owner']->ticker."] "
                .$encounter['star']['name']
            );
        Log::channel('encounter')
            ->debug("#".$encounter['id']." star: ".json_encode($encounter['star'], JSON_PRETTY_PRINT));
        Log::channel('encounter')
            ->info("#".$encounter['id']." ".count($e->getAttackers($encounter))." attacking fleets:");
        Log::channel('encounter')
            ->debug("#".$encounter['id']." attacker: ".json_encode($e->getAttackers($encounter), JSON_PRETTY_PRINT));
        Log::channel('encounter')
            ->info("#".$encounter['id']." ".count($e->getDefenders($encounter))." defending fleets:");
        Log::channel('encounter')
            ->debug("#".$encounter['id']." defender: ".json_encode($e->getDefenders($encounter), JSON_PRETTY_PRINT));

        // create db entry for encounter and participants
        $this->createDbEncounter($encounter);
        // start processing.
        $this->processEncounter($encounter, $turnSlug);
    }

}
