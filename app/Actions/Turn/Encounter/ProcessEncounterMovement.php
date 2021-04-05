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
     * @param Collection $opponents
     * @param int $column
     * @return int|null
     */
    private function getClosestOpponentCol (Collection $opponents, int $column): int
    {
        $closest = null;
        foreach ($opponents as $fleet) {
            if ($closest === null || abs($column - $closest) > abs($fleet['col'] - $column)) {
                $closest = $fleet['col'];
            }
        }
        return $closest;
    }

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
        $encounter['attacker'] = $encounter['attacker']->map(function ($fleet) use ($encounter) {
            $fleet['turn_acceleration'] = $this->getAccelerationDeviation($fleet['ships']->min('acceleration'));
            return $fleet;
        });
        $encounter['defender'] = $encounter['defender']->map(function ($fleet) use ($encounter) {
            $fleet['turn_acceleration'] = $this->getAccelerationDeviation($fleet['ships']->min('acceleration'));
            return $fleet;
        });
        return $encounter;
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
        $newCol = $fleet['col'];
        // fleet does not want to change col, default preferredColumn === col
        $preferredDirection = 0;
        // find column of closest oppenent
        $colOpponents = $this->getClosestOpponentCol($opponents, $fleet['col']);
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

        //echo $fleet['name']." col=".$fleet['col']
        //.", closest opponent ".$closestOpponent['name']." @".$colOpponents.", range =".$fleet['preferred_range']
        //.", preferred col=".$preferredColumn
        //.", current acc=".$fleetAcceleration.", closest opponent acc=".$oppAcceleration."\n";

        Log::channel('encounter')->info(
            "$turnSlug #".$encounterId." => "
            .$fleet['name']." col=".$fleet['col']
            .", closest opponent ".$closestOpponent['name']." @".$colOpponents.", range =".$fleet['preferred_range']
            .", preferred col=".$preferredColumn
            .", current acc=".$fleetAcceleration.", closest opponent acc=".$oppAcceleration
        );

        // if acceleration is >= closest opponent, modify $newCol by -1/+1
        if ($fleetAcceleration >= $oppAcceleration) {
            $newCol = $fleet['col'] + $preferredDirection;
            //echo " => ".$fleet['name']." has acc >= random closest opponent, "
            //    ."moving by $preferredDirection\n";
            Log::channel('encounter')->info(
                "$turnSlug #".$encounterId." => ".$fleet['name']." has acc >= random closest opponent, "
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
        $encounter = $this->modifyTurnAcceleration($encounter);

        // concat all fleets into one collection and shuffle for random turn order.
        $allFleets = $e->randomOrder($encounter);
        // get attacker IDs so we can find out if a fleet is attacker or defender.
        $attackerFleetIds = $encounter['attacker']->map(function($fleet) {
            return $fleet['id'];
        })->toArray();

        // loop over all fleets in the random turn order determined above.
        $allFleets = $allFleets->map(function ($fleet) use ($encounter, $attackerFleetIds, $turnSlug) {
            // find out if fleet is attacker or defender.
            $isAttacker = true;
            if (!in_array($fleet['id'], $attackerFleetIds)) $isAttacker = false;
            // calculate new column.
            if ($isAttacker) {
                $newColumn = $this->getNewColumn($encounter['defender'], $fleet, 1, $turnSlug, $encounter['id']);
            } else {
                $newColumn = $this->getNewColumn($encounter['attacker'], $fleet, -1, $turnSlug, $encounter['id']);
            }
            if ($fleet['col'] !== $newColumn) {
                Log::channel('encounter')->info(
                    "$turnSlug #".$encounter['id']." ".$fleet['name']
                    ." movement ".$fleet['col']." => ".$newColumn
                );
                $fleet['col'] = $newColumn;
            }
            return $fleet;
        });

        // set $encounter fleets to changed values with new col
        $encounter['attacker'] = $allFleets->filter(function($fleet) use ($attackerFleetIds) {
            return in_array($fleet['id'], $attackerFleetIds);
        });
        $encounter['defender'] = $allFleets->filter(function($fleet) use ($attackerFleetIds) {
            return !in_array($fleet['id'], $attackerFleetIds);
        });

        return $encounter;
    }

}
