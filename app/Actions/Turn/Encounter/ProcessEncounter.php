<?php

namespace App\Actions\Turn\Encounter;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessEncounter
{

    /**
     * @function calculate the new column of a fleet
     * calculate the direction that the fleet wants to move: away from oppenents or closer to opponents
     * then compare acceleration with one of the closest opponent fleets
     * if fleet has better acceleration, move in preferred direction.
     * @param Collection $opponents
     * @param array $fleet
     * @param int $dir - direction: [-1,1]
     * @return int
     */
    private function getNewColumn (Collection $opponents, array $fleet, int $dir): int
    {
        $newCol = $fleet['col'];
        // fleet does not want to change col, default preferredColumn === col
        $preferredDirection = 0;
        // find column of closest oppenent
        $colOpponents = $dir === 1 ? $opponents->max('col') : $opponents->min('col');
        // calculate preferred column of fleet
        $preferredColumn = $colOpponents + ($fleet['preferred_range'] * $dir);
        // fleet wants to move by +1
        if ($preferredColumn > $fleet['col']) $preferredDirection = +1;
        // fleet wants to move by -1
        if ($preferredColumn < $fleet['col']) $preferredDirection = -1;
        echo "current col=".$fleet['col']." opp col=".$colOpponents." - range=".$fleet['preferred_range']." - preferredColumn: ".$preferredColumn;
        echo " acc ".$fleet['ships']->min('acceleration')."\n";
        // determine the closest opponent. if there is more than one, choose a random one.
        $closestOpponent = $opponents->where('col', $colOpponents)->random();
        // if acceleration is >= closest opponent, modify $newCol by -1/+1
        if ($fleet['ships']->min('acceleration') >= $closestOpponent['ships']->min('acceleration')) {
            $newCol = $fleet['col'] + $preferredDirection;
            echo "fleet has better acc than a random closest opp, moving by ".$preferredDirection."\n";
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
     * @param int $turn
     * @return Collection
     */
    private function moveFleets (Collection $encounter, string $turnSlug, int $turn): Collection
    {
        Log::channel('encounter')
            ->info("$turnSlug #".$encounter['id']." turn $turn STEP 1: move fleets.");
        // concat all fleets into one collection and shuffle for random turn order.
        $allFleets = $encounter['defender']->concat($encounter['attacker'])->shuffle();
        // get attacker IDs so we can find out if a fleet is attacker or defender.
        $attackerFleetIds = $encounter['attacker']->map(function($fleet) {
            return $fleet['id'];
        })->toArray();

        // loop over all fleets in the random turn order determined above.
        $allFleets = $allFleets->map(function ($fleet) use ($encounter, $attackerFleetIds) {
            // find out if fleet is attacker or defender.
            $isAttacker = true;
            if (!in_array($fleet['id'], $attackerFleetIds)) $isAttacker = false;
            echo $fleet['name']." is ".($isAttacker ? "attacker" : "defender")."\n";
            // calculate new column.
            if ($isAttacker) {
                $newColumn = $this->getNewColumn($encounter['defender'], $fleet, 1);
            } else {
                $newColumn = $this->getNewColumn($encounter['attacker'], $fleet, -1);
            }
            $fleet['col'] = $newColumn;
            echo "new column = ".$newColumn."\n";
            return $fleet;
        });

        // set $encounter fleets to changed values with new col
        $encounter['attacker'] = $allFleets->filter(function($fleet) use ($attackerFleetIds) {
            return in_array($fleet['id'], $attackerFleetIds);
        });
        $encounter['defender'] = $allFleets->filter(function($fleet) use ($attackerFleetIds) {
            return !in_array($fleet['id'], $attackerFleetIds);
        });

        echo "\n\n";

        return $encounter;
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
            $encounter = $this->moveFleets($encounter, $turnSlug, $turn);
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
