<?php

namespace App\Actions\Turn\Encounter;

use App\Models\EncounterTurn;
use App\Models\Game;
use App\Models\Turn;
use App\Models\Encounter;
use App\Services\FleetService;
use Illuminate\Support\Collection;

trait UsesEncounterLogging
{

    /**
     * @function create db entry for encounter
     * @param Collection $encounter
     */
    private function createEncounter (Collection $encounter)
    {
        $turnId = Turn::where('game_id', '=', $encounter['game_id'])
            ->whereNull('processed')
            ->first()
            ->id;
        Encounter::create([
            'id' => $encounter['id'],
            'game_id' => $encounter['game_id'],
            'turn_id' => $turnId,
            'star_id' => $encounter['star']['id']
        ]);
    }

    /**
     * @function add new turn to log
     * @param Collection $encounter
     * @param int $turn
     * @return EncounterTurn
     */
    private function createNewTurn (Collection $encounter, int $turn): EncounterTurn
    {
        return EncounterTurn::create([
            'game_id' => $encounter['game_id'],
            'encounter_id' => $encounter['id'],
            'turn' => $turn
        ]);
    }

    /**
     * @function update data for attacker and defender in turn data
     * @param Collection $encounter
     * @param EncounterTurn $encounterTurn
     * @return Collection
     */
    private function updateTurnFleetData (Collection $encounter, EncounterTurn $encounterTurn): Collection
    {
        $encounterTurn->attacker = array_values($encounter['attacker']->map(function ($fleet) {
            return [
                'fleetId' => $fleet['id'],
                'playerId' => $fleet['player_id'],
                'name' => $fleet['name'],
                'col' => $fleet['col'],
                'ships' => $this->formatShips($fleet['ships'])
            ];
        })->toArray());
        $encounterTurn->defender = array_values($encounter['defender']->map(function ($fleet) {
            return [
                'fleetId' => $fleet['id'],
                'playerId' => $fleet['player_id'],
                'name' => $fleet['name'],
                'col' => $fleet['col'],
                'ships' => $this->formatShips($fleet['ships'])
            ];
        })->toArray());
        $encounterTurn->save();
        return $encounter;
    }

    /**
     * @function format ship
     * @param Collection $ships
     * @return array
     */
    private function formatShips (Collection $ships): array
    {
        return [
            'ark' => count($ships->filter(function($ship) {
                return $ship['hull_type'] === 'ark';
            })),
            'small' => count($ships->filter(function($ship) {
                return $ship['hull_type'] === 'small';
            })),
            'medium' => count($ships->filter(function($ship) {
                return $ship['hull_type'] === 'medium';
            })),
            'large' => count($ships->filter(function($ship) {
                return $ship['hull_type'] === 'large';
            })),
            'xlarge' => count($ships->filter(function($ship) {
                return $ship['hull_type'] === 'xlarge';
            })),
        ];
    }


}
