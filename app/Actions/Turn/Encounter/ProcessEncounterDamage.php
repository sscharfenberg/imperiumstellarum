<?php

namespace App\Actions\Turn\Encounter;

use App\Services\EncounterService;

use App\Services\FleetService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProcessEncounterDamage
{

    use UsesDamageLogging;

    /**
     * @function find a target fleet: random fleet in the closest column to $fleet
     * @param array $fleet
     * @param Collection $encounter
     * @return null|array
     */
    private function findTargetFleet(array $fleet, Collection $encounter): ?array
    {
        $e = new EncounterService;
        $f = new FleetService;
        // find out what the opponents of $fleet are
        $opponents = $fleet['attacker'] ? $e->getDefenders($encounter) : $e->getAttackers($encounter);
        if (!$opponents) return null;
        $col = $fleet['col'];
        $opponents = $opponents->map(function($fleet) use ($col) {
            $fleet['distance'] = max($fleet['col'], $col) - min($fleet['col'], $col);
            return $fleet;
        });
        // filter fleets without ships, then sort by distance - lowest distance first
        $opponents = $opponents->filter(function ($fleet) {
            return $fleet['ships']->count() > 0;
        })->sortBy('distance')->values();
        // if there are no opponents left, we can't target anything.
        if ($opponents->count() === 0) return null;
        $targetFleet = $opponents->first();
        // calculate distance between targetingFleet and closest opponents
        $distance = max($col, $targetFleet['col']) - min($col, $targetFleet['col']);
        // calculate prefered range
        $range = $f->getFleetPreferredRange($targetFleet['ships']->toArray());
        // range * falloff modifier >= distance? return targetFleet.
        if ($range * config('rules.encounters.falloff.rangeMultiplier') >= $distance) {
            return $targetFleet;
        } else {
            //  return null if not (no targets)
            return null;
        }
    }


    /**
     * @function save target of a fleet (fleetId and shipId) to encounter collection
     * @param string $targetingFleetId
     * @param string $targetedFleetId
     * @param Collection $encounter
     * @return Collection
     */
    private function saveTarget(
        string $targetingFleetId,
        string $targetedFleetId,
        Collection $encounter): Collection
    {
        $encounter['fleets'] = $encounter['fleets']->map(
            function ($fleet) use ($targetingFleetId, $targetedFleetId) {
                if ($fleet['id'] === $targetingFleetId) {
                    $fleet['target_fleet_id'] = $targetedFleetId;
                }
                return $fleet;
            }
        );
        return $encounter;
    }


    /**
     * @function set the target for a fleet
     * @param Collection $encounter
     * @param string $targetingFleetId
     * @param string $turnSlug
     * @return Collection $encounter
     */
    private function setFleetTarget (Collection $encounter, string $targetingFleetId, string $turnSlug): Collection
    {
        $e = new EncounterService;
        $targetingFleet = $encounter['fleets']->where('id', $targetingFleetId)->first();
        $targetFleet = $this->findTargetFleet($targetingFleet, $encounter);
        if ($targetFleet) {
            Log::channel('encounter')
                ->info(
                    "$turnSlug #".$encounter['id']." => "
                    .$e->getFleetFullName($targetingFleet)." (".($targetingFleet['attacker'] ? "attacker" : "defender").")"
                    ." targeting ".$e->getFleetFullName($targetFleet)." ("
                    .($targetFleet['attacker'] ? "attacker" : "defender").")"
                );
            echo $e->getFleetFullName($targetingFleet)." (".($targetingFleet['attacker'] ? "attacker" : "defender").")"
                ." targeting ".$e->getFleetFullName($targetFleet)." ("
                .($targetFleet['attacker'] ? "attacker" : "defender")."\n";
            // return the encounter
            return $this->saveTarget($targetingFleetId, $targetFleet['id'], $encounter);
        } else {
            // we don't have a target, so simply return encounter.
            return $encounter;
        }
    }


    /**
     * @function modify $encounter collection by applying damage to ship
     * @param Collection $encounter
     * @param string $fleetId
     * @param string $shipId
     * @param int $damage
     * @param string $hpArea
     * @return Collection
     */
    private function applyDamage(
        Collection $encounter,
        string $fleetId,
        string $shipId,
        int $damage,
        string $hpArea
    ): Collection
    {
        $actualDamage = $damage;
        // map fleets
        $encounter['fleets'] = $encounter['fleets']->map(function ($fleet) use ($fleetId, $shipId, $damage, $hpArea, $actualDamage) {
            // if we are at the correct fleet,
            if ($fleet['id'] === $fleetId) {
                // map ships
                $fleet['ships'] = $fleet['ships']->map(function($ship) use ($shipId, $hpArea, $damage, $actualDamage) {
                    // if we are at the correct ship,
                    if ($ship['id'] === $shipId) {
                        // apply damage
                        $ship['hp_'.$hpArea.'_current'] -= $damage;
                        if ($ship['hp_'.$hpArea.'_current'] < 0) {
                            $actualDamage = $actualDamage - abs($ship['hp_'.$hpArea.'_current']);
                            $ship['hp_'.$hpArea.'_current'] = 0;
                        }
                    }
                    return $ship;
                })->values();
            }
            return $fleet;
        })->values();
        // return the encounter
        return $this->logTurnDamage($encounter, $fleetId, $actualDamage);
    }


    /**
     * @function choose a new target ship from a $fleetId and update queue.
     * @param Collection $encounter
     * @param bool $attackerQueue
     * @param string $fleetId
     * @return Collection
     */
    private function chooseNewTargetShip (Collection $encounter, bool $attackerQueue, string $fleetId): Collection
    {
        $e = new EncounterService;
        $targetFleet = $encounter['fleets']->where('id', $fleetId)->first();
        $availableShips = $e->getNotDeadShips($targetFleet);
        // if the fleet has non dead ships left, choose a random ship and update ship_id for the fleet
        if (count($availableShips) > 0) {
            $targetShip = $availableShips->random();
            //echo "reassigning target to ".$targetShip['name'].", id=".$targetShip['id']." from ".count($availableShips)." available\n";
            if ($attackerQueue) {
                $encounter['attacker_queue'] = $encounter['attacker_queue']->map(function ($target) use ($fleetId, $targetShip) {
                    if ($target['fleet_id'] === $fleetId) {
                        $target['ship_id'] = $targetShip['id'];
                    }
                    return $target;
                });
            } else {
                $encounter['defender_queue'] = $encounter['defender_queue']->map(function ($target) use ($fleetId, $targetShip) {
                    if ($target['fleet_id'] === $fleetId) {
                        $target['ship_id'] = $targetShip['id'];
                    }
                    return $target;
                });
            }
            //echo "reassigning target to ".$targetShip['name']."\n";
        }
        return $encounter;
    }


    /**
     * @function process damage from a ship to a target ship
     * @param Collection $encounter
     * @param array $firingShip
     * @param string $firingFleetId
     * @param string $targetFleetId
     * @param int $column
     * @param string $turnSlug
     * @return Collection
     */
    private function processShip (
        Collection $encounter,
        array $firingShip,
        string $firingFleetId,
        string $targetFleetId,
        int $column,
        string $turnSlug
    ): Collection
    {
        $e = new EncounterService;
        $firingFleet = $encounter['fleets']->where('id', $firingFleetId)->first();
        $targetFleet = $encounter['fleets']->where('id', $targetFleetId)->first();
        $distance = max($column, $targetFleet['col']) - min($column, $targetFleet['col']);
        $hpAreas = ['shields', 'armour', 'structure'];
        $targetShipId = $e->getShipIdFromTargetQueue($encounter, $firingFleet['attacker'], $targetFleet['id']);
        $targetShip = $targetFleet['ships']->where('id', $targetShipId)->first();

        // if the ship is dead, choose a new one.
        if ($targetShip['hp_structure_current'] <= 0) {
            //echo $firingShip['name']." REPORTS: TARGET ".$targetShip['name']." IS ALREADY DEAD; FUCK.\n";
            $encounter = $this->chooseNewTargetShip($encounter, $firingFleet['attacker'], $targetFleetId);
            $targetShipId = $e->getShipIdFromTargetQueue($encounter, $firingFleet['attacker'], $targetFleet['id']);
            $targetShip = $targetFleet['ships']->where('id', $targetShipId)->first();
            //echo "reassigned target to ".$targetShip['name'].", hp = ".$targetShip['hp_structure_current']."\n";
            Log::channel('encounter')
                ->info(
                    "$turnSlug #".$encounter['id']." # "
                    .$firingShip['name']." target switched to ".$targetShip['name'].", dist=$distance"
                );
        }

        //echo "=> ".$firingShip['name'].", targetting fleet ".$targetFleet['name'].", targetShip='".$targetShip['name']."' at distance=".$distance."\n";

        // loop all weapon tech areas
        foreach($e->getWeaponTechAreas() as $tech) {
            // check if ship has damage > 0 and the target is in range
            if ($firingShip['dmg_' . $tech] > 0 ) {
                $dmgAssigned = false;
                // loop all hpAreas and check if there are hitpoints left
                foreach($hpAreas as $area) {
                    $dmgMultiplier = $e->getDamageMultiplier($tech, $area);
                    $range = $e->getWeaponRange($tech, $firingShip['hull_type']);
                    $rangeMultiplier = $e->getRangeMultiplier($distance, $range);
                    // out of range
                    if ($rangeMultiplier === 0.0 && !$dmgAssigned) {
                        $dmgAssigned = true;
                        Log::channel('encounter')
                            ->info(
                                "$turnSlug #".$encounter['id']." * "
                                .$firingShip['name']." firing at ".$targetShip['name'].", dist=$distance, range=$range"
                                ." => out of effective range."
                            );
                        echo $firingShip['name']." firing at ".$targetShip['name'].", dist=$distance, range=$range"
                            ." => out of effective range.\n";
                    }
                    else if ($targetShip['hp_'.$area.'_current'] > 0 && !$dmgAssigned) {
                        $dmgAssigned = true;
                        // calculate actual damage, modified by range and weaponType vs. hullArea
                        $damage = intval(round($firingShip['dmg_'.$tech] * $dmgMultiplier * $rangeMultiplier), 10);
                        // apply damage in encounter collection
                        $encounter = $this->applyDamage($encounter, $targetFleetId, $targetShipId, $damage, $area);
                        Log::channel('encounter')
                            ->info(
                                "$turnSlug #".$encounter['id']." * "
                                .$firingShip['name']." firing at ".$targetShip['name'].", dist=$distance, range=$range, "
                                ." rangeMultiplier=".$rangeMultiplier.", dmgMultiplier=".$dmgMultiplier
                                .", causing ".$damage." damage to ".$area
                            );
                        echo " * ".$firingShip['name']." firing at ".$targetShip['name'].", dist=$distance, range=$range, "
                            ." rangeMultiplier=".$rangeMultiplier.", dmgMultiplier=".$dmgMultiplier
                            .", causing ".$damage." damage to ".$area."\n";
                    }
                    //if ($targetShip['hp_structure_current'] === 0) {
                    //    Log::channel('encounter')
                    //        ->info(
                    //            "$turnSlug #".$encounter['id']." => "
                    //            .$firingShip['name']." firing at ".$targetShip['name']." - target is dead."
                    //        );
                    //    echo $firingShip['name']." firing at ".$targetShip['name']." - target is dead.\n";
                    //    dump($targetShip);
                    //}
                }


            }
        }

        return $encounter;
    }


    /**
     * @function process a fleet: choose target, process ships.
     * @param string $fleetId
     * @param Collection $encounter
     * @param string $turnSlug
     * @return Collection $encounter
     */
    private function processFleet (Collection $encounter, string $fleetId, string $turnSlug): Collection
    {
        // first, set targets for this fleet
        $encounter = $this->setFleetTarget($encounter, $fleetId, $turnSlug);
        $fleet = $encounter['fleets']->where('id', $fleetId)->first();

        if ($fleet['target_fleet_id']) {
            // loop ships in fleet and process ships
            $fleet['ships']->each(function ($ship) use (&$encounter, $fleet, $fleetId, $turnSlug) {
                $encounter = $this->processShip(
                    $encounter, $ship, $fleet['id'], $fleet['target_fleet_id'], $fleet['col'], $turnSlug
                );
            });
        }

        return $encounter;
    }


    /**
     * @function move fleets (changing row)
     * @param Collection $encounter
     * @param string $turnSlug
     * @param int $turn
     * @return Collection
     */
    public function handle (Collection $encounter, string $turnSlug, int $turn): Collection
    {
        Log::channel('encounter')
            ->info("$turnSlug #".$encounter['id']." TURN $turn STEP 3: process damage.");

        // loop fleets and process them.
        $encounter['fleets']->each(function ($fleet) use (&$encounter, $turnSlug) {
            $encounter = $this->processFleet($encounter, $fleet['id'], $turnSlug);
        });

        return $encounter;
    }

}
