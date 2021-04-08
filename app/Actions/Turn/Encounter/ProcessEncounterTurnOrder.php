<?php

namespace App\Actions\Turn\Encounter;

use App\Models\EncounterTurn;

use App\Services\EncounterService;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProcessEncounterTurnOrder
{

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
            ->info("$turnSlug #".$encounter['id']." TURN $turn STEP 1: determine turn order.");
        $encounter['fleets'] = $encounter['fleets']->shuffle();
        $order = 1;
        $encounter['fleets']->each(function ($fleet) use (&$order, $turnSlug, $encounter, $e) {
            Log::channel('encounter')
                ->info("$turnSlug #".$encounter['id']." $order => ".$e->getFleetFullName($fleet));
            $order++;
        });
        return $encounter;
    }

}
