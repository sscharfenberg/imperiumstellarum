<?php

namespace App\Actions\Turn\Encounter;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\Game;
use Exception;

class ProcessEncounter
{

    /**
     * @function move fleets (changing row)
     * @param Collection $encounter
     * @param string $turnSlug
     * @param int $turn
     */
    private function moveFleets (Collection $encounter, string $turnSlug, int $turn)
    {
        Log::channel('encounter')
            ->info("$turnSlug #".$encounter['id']." turn $turn STEP 1: MOVE FLEETS.");

        //foreach($encounter['attacker'] as $fleet) {
//
        //    dd($fleet['ships']);
        //}
        // find out which distance the fleet wants to be at
        // move as far as possible to this column
    }


    private function processEncounter (Collection $encounter, string $turnSlug)
    {
        $resolved = false;
        $turn = 0;
        while(!$resolved) {
            $turn++;
            Log::channel('encounter')
                ->notice("#".$encounter['id']." start processing turn $turn.");
            echo "encounter turn $turn\n";
            // 1) Move Fleets
            $this->moveFleets($encounter, $turnSlug, $turn);
            // 2) Select targets for ships
            // 3) Assign damage to targets
            // 4) Check for destroyed ships and remove them
            // 5) check if encounter ends (no attacker or defender ships)
            // 6) increase turn



            // temp so it is not never-ending.
            if ($turn >= 10) $resolved = true;
        }
    }


    /**
     * @function find out if any encounters need to be processed and trigger processing.
     * @param string $turnSlug
     * @param Collection $encounter
     * @throws Exception
     * @return void
     */
    public function handle(Collection $encounter, string $turnSlug)
    {
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
            ->debug("#".$encounter['id']." attacker: ".json_encode($encounter['attacker'], JSON_PRETTY_PRINT));
        Log::channel('encounter')
            ->debug("#".$encounter['id']." defender: ".json_encode($encounter['defender'], JSON_PRETTY_PRINT));
        // start processing.
        $this->processEncounter($encounter, $turnSlug);
    }

}
