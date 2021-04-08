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
     * @function find a target fleet: random fleet in the closest column to $fleet
     * @param array $fleet
     * @param Collection $encounter
     * @return array
     */
    private function findTargetFleet(array $fleet, Collection $encounter): array
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
     * @function save target of a fleet (fleetId and shipId) to encounter collection
     * @param string $targetingFleetId
     * @param string $targetedFleetId
     * @param string $targetedShipId
     * @param Collection $encounter
     * @return Collection
     */
    private function saveTarget(
        string $targetingFleetId,
        string $targetedFleetId,
        string $targetedShipId,
        Collection $encounter): Collection
    {
        $encounter['fleets'] = $encounter['fleets']->map(
            function ($fleet) use ($targetingFleetId, $targetedFleetId, $targetedShipId) {
                if ($fleet['id'] === $targetingFleetId) {
                    $fleet['target_fleet_id'] = $targetedFleetId;
                    $fleet['target_ship_id'] = $targetedShipId;
                }
                return $fleet;
            }
        );
        return $encounter;
    }

    /**
     * @function select a target fleet: random fleet in the closest column to $fleet
     * @param array $fleet
     * @param Collection $encounter
     * @return Collection
     */
    private function chooseTarget(array $fleet, Collection $encounter): Collection
    {
        $e = new EncounterService;
        $targetFleet = null;
        $targetShipId = null;

        // does the fleet have a target?
        if (!$fleet['target_fleet_id'] || !$fleet['target_ship_id']) {
            echo "fleet has no targets\n";
            $targetFleet = $this->findTargetFleet($fleet, $encounter);
            $targetShipId = $targetFleet['ships']->random()['id'];
        }

        // fleet has no target to shoot at.
        else {
            echo "fleet is already targeting fleet ".$fleet['target_fleet_id']."\n";
            if (
                $e->fleetExists($fleet['target_fleet_id'], $encounter)
                && $e->fleetShipExists($fleet['target_fleet_id'], $fleet['target_ship_id'], $encounter)
            ) {
                // target fleet and ship still exist. proceed with saved values.
                echo "fleet targets still exist, proceeding.\n";
                $targetFleet = $encounter['fleets']->where('id', $fleet['target_fleet_id'])->first();
                $targetShipId = $fleet['target_ship_id'];
            } else {
                // find new target
                echo "fleet targets do not exists anymore, finding new target.\n";
                $targetFleet = $this->findTargetFleet($fleet, $encounter);
                $targetShipId = $targetFleet['ships']->random()['id'];
            }
        }

        $encounter = $this->saveTarget($fleet['id'], $targetFleet['id'], $targetShipId, $encounter);
        return $encounter;
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
            ->info("$turnSlug #".$encounter['id']." TURN $turn STEP 3: process damage.");

        $encounter['fleets']->each(function ($fleet) use (&$encounter, $turnSlug, $e) {
            $encounter = $this->chooseTarget($fleet, $encounter);

            // encounter was updated, but $fleet does still have the old values - refresh $fleet:
            $updatedFleet = $encounter['fleets']->where('id', $fleet['id'])->first();
            $fleet['target_fleet_id'] = $updatedFleet['target_fleet_id'];
            $fleet['target_ship_id'] = $updatedFleet['target_ship_id'];

            // get fleet and ship so we can output values to logfile.
            $targetFleet = $encounter['fleets']->where('id', $fleet['target_fleet_id'])->first();
            $targetShip = $targetFleet['ships']->where('id', $fleet['target_ship_id'])->first();
            echo $e->getFleetFullName($fleet)." (".($fleet['attacker'] ? "attacker" : "defender").") => targeting "
                .$e->getFleetFullName($targetFleet).", targetShip='".$targetShip['name']."'\n";
            Log::channel('encounter')
                ->info(
                    "$turnSlug #".$encounter['id']." => "
                    .$e->getFleetFullName($fleet)." (".($fleet['attacker'] ? "attacker" : "defender").")"
                    ." targeting ".$e->getFleetFullName($targetFleet)." ("
                    .($targetFleet['attacker'] ? "attacker" : "defender").")"
                    .", targetShip='".$targetShip['name']."'"
                );
        });

        return $encounter;
    }

}
