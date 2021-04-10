<?php

namespace App\Actions\Turn\Encounter;

use App\Models\EncounterTurn;

use App\Services\EncounterService;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProcessEncounterCleanup
{


    /**
     * @function remove dead ships and fleets from encounter
     * @param Collection $encounter
     * @param string $turnSlug
     * @return Collection
     */
    private function removeCorpses (Collection $encounter, string $turnSlug): Collection
    {
        $deadShips = collect();
        $deadFleets = collect();
        // map fleets and filter dead ships.
        $encounter['fleets'] = $encounter['fleets']->map( function($fleet) use ($deadShips) {
            $fleet['ships'] = $fleet['ships']->filter( function($ship) use ($deadShips) {
                if ($ship['hp_structure_current'] <= 0) {
                    $deadShips->add($ship);
                }
                return $ship['hp_structure_current'] > 0;
            });
            return $fleet;
        })->values();
        // filter fleets and remove fleets without ships
        $encounter['fleets'] = $encounter['fleets']->filter( function($fleet) use ($deadFleets) {
            if ($fleet['ships']->count() === 0) {
                $deadFleets->add($fleet);
            }
            return $fleet['ships']->count() > 0;
        });

        // TODO: add to log/turn for frontend.
        // log cleanup
        if ($deadShips->count() > 0) {
            Log::channel('encounter')
                ->debug(
                    "$turnSlug #".$encounter['id']." => removed ".$deadShips->count()." dead ships: "
                    .json_encode($deadShips, JSON_PRETTY_PRINT)
                );
        }
        if ($deadFleets->count() > 0) {
            Log::channel('encounter')
                ->debug(
                    "$turnSlug #".$encounter['id']." => removed ".$deadFleets->count()." dead fleets: "
                    .json_encode($deadFleets, JSON_PRETTY_PRINT)
                );
        }
        if ($deadFleets->count() === 0 && $deadShips->count() === 0) {
            Log::channel('encounter')
                ->info("$turnSlug #".$encounter['id']." => no ships or fleets removed.");
        }

        echo "removed ".$deadShips->count()." dead ships:\n";
        dump($deadShips);
        echo "removed ".$deadFleets->count()." dead fleets:\n";
        dump($deadFleets);

        return $encounter;
    }


    /**
     * @function update target queues for attacker and defender; get new random targets if needed
     * @param Collection $encounter
     * @param string $turnSlug
     * @return Collection
     */
    public function updateTargetQueues (Collection $encounter, string $turnSlug): Collection
    {
        $e = new EncounterService;
        $targetFleetsBeforeUpdate = count($encounter['attacker_queue']) + count($encounter['defender_queue']);
        $updatedShips = 0;

        // loop attacker/defender
        foreach(['attacker', 'defender'] as $type) {
            // filter attacker/defender queue and remove fleets that do not exist anymore.
            $encounter[$type.'_queue'] = $encounter[$type.'_queue']->filter(function ($target) use ($e, $encounter) {
                return $e->fleetExists($target['fleet_id'], $encounter);
            });
            // map attackers queue and change ship_id if the current ship id is dead.
            $encounter[$type.'_queue'] = $encounter[$type.'_queue']->map(function ($target) use ($e, $encounter, &$updatedShips) {
                if (!$e->fleetShipExists($target['fleet_id'], $target['ship_id'], $encounter)) {
                    $fleet = $encounter['fleets']->where('id', $target['fleet_id'])->first();
                    $availableShips = $e->getNotDeadShips($fleet);
                    $target['ship_id'] = $availableShips->random()['id'];
                    $updatedShips++;
                }
                return $target;
            });
        }

        // logging.
        $removedFleets = $targetFleetsBeforeUpdate - ($encounter['attacker_queue']->count() + $encounter['defender_queue']->count());
        if ($removedFleets > 0) {
            Log::channel('encounter')
                ->info("$turnSlug #".$encounter['id']." => removed ".$removedFleets." fleets from target queues.");
        }
        if ($updatedShips > 0) {
            Log::channel('encounter')
                ->info("$turnSlug #".$encounter['id']." => changed ".$updatedShips." ships in target queues.");
        }
        if ($removedFleets === 0 && $updatedShips === 0) {
            Log::channel('encounter')
                ->info("$turnSlug #".$encounter['id']." => no changes to target queues.");
        }

        return $encounter;
    }


    /**
     * @function handle cleanup step: remove dead ships/fleets, update target queues if needed.
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
            ->info("$turnSlug #".$encounter['id']." TURN $turn STEP 4: cleanup.");

        // part 1: cleanup dead ships and fleets
        $encounter = $this->removeCorpses($encounter, $turnSlug);
        // part 2: update target queues
        $encounter = $this->updateTargetQueues($encounter, $turnSlug);

        // return encounter
        return $encounter;
    }

}
