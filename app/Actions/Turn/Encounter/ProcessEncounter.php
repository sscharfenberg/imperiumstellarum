<?php

namespace App\Actions\Turn\Encounter;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessEncounter
{

    private function processEncounter (Collection $encounter, string $turnSlug)
    {
        $resolved = false;
        $turn = 0;
        while(!$resolved) {
            $turn++;
            Log::channel('encounter')
                ->notice("$turnSlug #".$encounter['id']." start processing turn $turn.");
            // add new turn to log
            $encounter['turns']->push([
                'number' => $turn,
                'movement' => collect(),
                'damage' => collect()
            ]);

            // 1) Move Fleets
            $m = new \App\Actions\Turn\Encounter\ProcessEncounterMovement;
            $encounter = $m->handle($encounter, $turnSlug, $turn);


            // 2) Select targets for ships
            // 3) Assign damage to targets
            // 4) Check for destroyed ships and remove them
            // 5) check if encounter ends (no attacker or defender ships)
            // 6) increase turn



            // temp so it is not never-ending.
            if ($turn >= 10) $resolved = true;
        }
        dd($encounter['turns']);
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
