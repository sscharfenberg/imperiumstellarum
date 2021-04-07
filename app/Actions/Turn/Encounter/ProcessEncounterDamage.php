<?php

namespace App\Actions\Turn\Encounter;

use App\Models\EncounterTurn;

use App\Services\EncounterService;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProcessEncounterDamage
{

    use UsesEncounterLogging;

    /**
     * @function select a target fleet: random fleet in the closest column to $fleet
     * @param array $fleet
     * @param Collection $encounter
     * @return array
     */
    private function selectTargetFleet(array $fleet, Collection $encounter): array
    {
        $e = new EncounterService;
        // find out what the opponents of $fleet are
        $opponents = $fleet['attacker'] ? $e->getDefenders($encounter) : $e->getAttackers($encounter);
        // calculate column of closest fleet(s)
        $closestColumn = $e->getClosestOpponentCol($opponents, $fleet['col']);
        // pick a random fleet in the calculated column
        return $opponents->where('col', $closestColumn)->random();
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
        $encounter['fleets']->each(function ($fleet) use (&$encounter, $turnSlug, $e) {
            $targetFleet = $this->selectTargetFleet($fleet, $encounter);



            echo $e->getFleetFullName($fleet)." (".($fleet['attacker'] ? "attacker" : "defender").") => targetting ".$e->getFleetFullName($targetFleet)."\n";
            Log::channel('encounter')
                ->info(
                    "$turnSlug #".$encounter['id']." '"
                    .$e->getFleetFullName($fleet)."' (".($fleet['attacker'] ? "attacker" : "defender").")"
                    ." targetting '".$e->getFleetFullName($targetFleet)."' (".($targetFleet['attacker'] ? "attacker" : "defender").")"
                );
        });

        return $encounter;
    }

}
