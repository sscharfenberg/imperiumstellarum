<?php

namespace App\Actions\Turn\Encounter;

use App\Models\EncounterTurn;
use App\Services\EncounterService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProcessEncounterMovement
{

    use UsesEncounterLogging;

    /**
     * @function modify acceleration by random deviation
     * @param int $acc
     * @return float
     */
    private function getAccelerationDeviation (int $acc): float
    {
        $scale = pow(10, 2);
        $defaultDeviation = config('rules.encounters.accDeviation');
        $min = 1 - ($defaultDeviation / 2);
        $max = 1 + ($defaultDeviation / 2);
        $deviation = mt_rand($min * $scale, $max * $scale) / $scale;
        return $deviation * $acc;
    }

    /**
     * @function change encounter fleets by setting turn_acceleration for all fleets.
     * @param Collection $encounter
     * @return Collection
     */
    private function modifyTurnAcceleration (Collection $encounter): Collection
    {
        return $encounter['fleets']->map(function ($fleet) use ($encounter) {
            $fleet['turn_acceleration'] = $this->getAccelerationDeviation($fleet['ships']->min('acceleration'));
            return $fleet;
        });
    }

    /**
     * @function calculate the new column of a fleet
     * calculate the direction that the fleet wants to move: away from oppenents or closer to opponents
     * then compare acceleration with one of the closest opponent fleets
     * if fleet has better acceleration, move in preferred direction.
     * @param Collection $opponents
     * @param array $fleet
     * @param int $dir - direction: [-1,1]
     * @param string $turnSlug
     * @param string $encounterId
     * @return int
     */
    private function getNewColumn (Collection $opponents, array $fleet, int $dir, string $turnSlug, string $encounterId): int
    {
        $e = new EncounterService;
        $newCol = $fleet['col'];
        // fleet does not want to change col, default preferredColumn === col
        $preferredDirection = 0;
        // find column of closest oppenent
        $colOpponents = $e->getClosestOpponentCol($opponents, $fleet['col']);
        // calculate preferred column of fleet
        $preferredColumn = $colOpponents + ($fleet['preferred_range'] * $dir);
        // fleet wants to move by +1
        if ($preferredColumn > $fleet['col']) $preferredDirection = +1;
        // fleet wants to move by -1
        if ($preferredColumn < $fleet['col']) $preferredDirection = -1;
        // determine the closest opponent. if there is more than one, choose a random one.
        $closestOpponent = $opponents->where('col', $colOpponents)->random();
        $fleetAcceleration = $fleet['turn_acceleration'];
        $oppAcceleration = $closestOpponent['turn_acceleration'];

        //echo $e->getFleetFullName($fleet)." col=".$fleet['col']
        //.", closest opponent ".$e->getFleetFullName($closestOpponent)." @".$colOpponents.", range =".$fleet['preferred_range']
        //.", preferred col=".$preferredColumn
        //.", current acc=".$fleetAcceleration.", closest opponent acc=".$oppAcceleration."\n";

        Log::channel('encounter')->info(
            "$turnSlug #".$encounterId." => "
            .$e->getFleetFullName($fleet)." col=".$fleet['col']
            .", closest opponent ".$e->getFleetFullName($closestOpponent)." @".$colOpponents.", range =".$fleet['preferred_range']
            .", preferred col=".$preferredColumn
            .", current acc=".$fleetAcceleration.", closest opponent acc=".$oppAcceleration
        );

        // if acceleration is >= closest opponent, modify $newCol by -1/+1
        if ($fleetAcceleration >= $oppAcceleration) {
            $newCol = $fleet['col'] + $preferredDirection;
            //echo " => ".$e->getFleetFullName($fleet)." has acc >= random closest opponent, "
            //    ."moving by $preferredDirection\n";
            Log::channel('encounter')->info(
                "$turnSlug #".$encounterId." => ".$e->getFleetFullName($fleet)." has acc >= random closest opponent, "
                ."moving by $preferredDirection"
            );
        }

        // ensure ships don't move out of the arena
        if ($newCol > 10) $newCol = 10;
        if ($newCol < 0) $newCol = 0;

        return $newCol;
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
            ->info("$turnSlug #".$encounter['id']." turn $turn STEP 1: move fleets.");

        // randomly modify fleet acceleration for this turn
        $encounter['fleets'] = $this->modifyTurnAcceleration($encounter);

        // concat all fleets into one collection and shuffle for random turn order.
        $encounter['fleets'] = $e->randomFleetOrder($encounter);

        // loop over all fleets in the random turn order determined above.
        $encounter['fleets'] = $encounter['fleets']->map(function ($fleet) use ($encounter, $turnSlug, $e) {
            if ($fleet['attacker']) {
                $newColumn = $this->getNewColumn($e->getDefenders($encounter), $fleet, 1, $turnSlug, $encounter['id']);
            } else {
                $newColumn = $this->getNewColumn($e->getAttackers($encounter), $fleet, -1, $turnSlug, $encounter['id']);
            }
            if ($fleet['col'] !== $newColumn) {
                Log::channel('encounter')->info(
                    "$turnSlug #".$encounter['id']." ".$e->getFleetFullName($fleet)
                    ." movement ".$fleet['col']." => ".$newColumn
                );
                $fleet['col'] = $newColumn;
            }
            return $fleet;
        });

        return $encounter;
    }

}
