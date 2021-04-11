<?php

namespace App\Actions\Turn\Encounter;

use Illuminate\Support\Collection;

trait UsesDamageLogging
{


    /**
     * @function initiate damage log; this functions is called once per encounter turn, at the beginning.
     * @param Collection $encounter
     * @return Collection
     */
    private function initiateDamageLog (Collection $encounter): Collection
    {
        $encounter['damage_log'] = $encounter['fleets']->map(function ($fleet) {
            return [
                'fleetId' => $fleet['id'],
                'playerId' => $fleet['player_id'],
                'damage' => 0
            ];
        });
        return $encounter;
    }


    /**
     * @function add actual damage caused to fleet.
     * @param Collection $encounter
     * @param string $fleetId
     * @param int $damage
     * @return Collection
     */
    private function logTurnDamage(Collection $encounter, string $fleetId, int $damage): Collection
    {
        $encounter['damage_log'] = $encounter['damage_log']->map(function ($fleet) use ($fleetId, $damage) {
            if ($fleet['fleetId'] === $fleetId) {
                $fleet['damage'] += $damage;
            }
            return $fleet;
        });
        return $encounter;
    }


}
