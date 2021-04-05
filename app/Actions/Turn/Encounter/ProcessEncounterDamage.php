<?php

namespace App\Actions\Turn\Encounter;

use App\Models\EncounterTurn;
use App\Services\EncounterService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProcessEncounterDamage
{

    use UsesEncounterLogging;


    private function selectTarget(Collection $fleet, Collection $encounter, string $turnSlug): Collection
    {
        return $fleet;
    }


    /**
     * @function move fleets (changing row)
     * @param Collection $encounter
     * @param string $turnSlug
     * @param EncounterTurn $encounterTurn
     * @return Collection
     */
    public function handle (Collection $encounter, string $turnSlug, EncounterTurn $encounterTurn): Collection
    {
        $e = new EncounterService;
        $turn = $encounterTurn->turn;
        Log::channel('encounter')
            ->info("$turnSlug #".$encounter['id']." turn $turn STEP 2: process damage.");

        // concat all fleets into one collection and shuffle for random turn order.
        $encounter['fleets'] = $e->randomFleetOrder($encounter);
        $encounter['fleets']->each(function ($fleet) {
            echo "Fleet ".$fleet['id']."\n";
        });

        return $encounter;
    }

}
