<?php

namespace App\Actions\Turn\Encounter;

use App\Models\EncounterTurn;

use App\Services\EncounterService;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PersistTurn
{

    /**
     * @function format ship
     * @param Collection $ships
     * @return array
     */
    private function formatShips (Collection $ships): array
    {
        $fleetShips = [];
        $numArks = count($ships->filter(function($ship) {
            return $ship['hull_type'] === 'ark';
        }));
        $numSmall = count($ships->filter(function($ship) {
            return $ship['hull_type'] === 'small';
        }));
        $numMedium = count($ships->filter(function($ship) {
            return $ship['hull_type'] === 'medium';
        }));
        $numLarge = count($ships->filter(function($ship) {
            return $ship['hull_type'] === 'large';
        }));
        $numXlarge = count($ships->filter(function($ship) {
            return $ship['hull_type'] === 'xlarge';
        }));
        if ($numArks > 0) $fleetShips['ark'] = $numArks;
        if ($numSmall > 0) $fleetShips['small'] = $numSmall;
        if ($numMedium > 0) $fleetShips['medium'] = $numMedium;
        if ($numLarge > 0) $fleetShips['large'] = $numLarge;
        if ($numXlarge > 0) $fleetShips['xlarge'] = $numXlarge;
        return $fleetShips;
    }


    /**
     * @function handle persisting encounter - update/delete database entries.
     * @param Collection $encounter
     * @param string $turnSlug
     * @param int $turn
     * @return EncounterTurn
     */
    public function handle (Collection $encounter, string $turnSlug, int $turn): EncounterTurn
    {
        $e = new EncounterService;
        $encounterTurn = EncounterTurn::create([
            'game_id' => $encounter['game_id'],
            'encounter_id' => $encounter['id'],
            'turn' => $turn,
            'attacker' => array_values($e->getAttackers($encounter)->map(function ($fleet) {
                return [
                    'fleetId' => $fleet['id'],
                    'playerId' => $fleet['player_id'],
                    'name' => $fleet['name'],
                    'col' => $fleet['col'],
                    'row' => $fleet['row'],
                    'ships' => $this->formatShips($fleet['ships']),
                    'acc' => $fleet['turn_acceleration']
                ];
            })->toArray()),
            'defender' => array_values($e->getDefenders($encounter)->map(function ($fleet) {
                return [
                    'fleetId' => $fleet['id'],
                    'playerId' => $fleet['player_id'],
                    'name' => $fleet['name'],
                    'col' => $fleet['col'],
                    'row' => $fleet['row'],
                    'ships' => $this->formatShips($fleet['ships']),
                    'acc' => $fleet['turn_acceleration']
                ];
            })->toArray()),
            'damage' => $encounter['damage_log']
        ]);
        Log::channel('encounter')
            ->notice(
                "$turnSlug #".$encounter['id']." TURN $turn"
                .($turn !== 0 ? " STEP 6" : "")
                .": persisted encounter turn in database, id="
                .$encounterTurn->id
            );
        return $encounterTurn;
    }

}
