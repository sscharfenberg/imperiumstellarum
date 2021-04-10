<?php

namespace App\Actions\Turn\Encounter;

use App\Services\EncounterService;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProcessEncounterTurnOrder
{


    /**
     * @function move fleets (changing row)
     * @param Collection $encounter
     * @param string $turnSlug
     * @param int $turn
     * @return Collection
     */
    public function handle (Collection $encounter, string $turnSlug, int $turn): Collection
    {
        $e = new EncounterService;
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
