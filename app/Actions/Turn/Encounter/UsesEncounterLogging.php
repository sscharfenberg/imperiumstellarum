<?php

namespace App\Actions\Turn\Encounter;

use App\Models\EncounterParticipant;
use App\Models\EncounterTurn;
use App\Models\Game;
use App\Models\Turn;
use App\Models\Encounter;
use App\Services\FleetService;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

trait UsesEncounterLogging
{

    /**
     * @function create db entry for encounter
     * @param Collection $encounter
     */
    private function createEncounter (Collection $encounter)
    {
        // find the current turn
        $turnId = Turn::where('game_id', '=', $encounter['game_id'])
            ->whereNull('processed')
            ->first()
            ->id;
        // find all unique players involved in this encounter
        $participantIds = $encounter['defender']->map(function ($fleet) {
            return $fleet['player_id'];
        })->concat($encounter['attacker']->map(function($fleet) {
            return $fleet['player_id'];
        }))->concat([$encounter['star']['owner']['id']])
            ->unique();
        $participants = $participantIds->map(function($participantId) use ($encounter) {
            return [
                'id' => Uuid::uuid4(),
                'game_id' => $encounter['game_id'],
                'encounter_id' => $encounter['id'],
                'player_id' => $participantId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        })->toArray();

        // create the encounter entry
        Encounter::create([
            'id' => $encounter['id'],
            'game_id' => $encounter['game_id'],
            'turn_id' => $turnId,
            'star_id' => $encounter['star']['id']
        ]);

        // create the encounter participants
        EncounterParticipant::insert($participants);
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
                'row' => $fleet['row'],
                'ships' => $this->formatShips($fleet['ships'])
            ];
        })->toArray());
        $encounterTurn->defender = array_values($encounter['defender']->map(function ($fleet) {
            return [
                'fleetId' => $fleet['id'],
                'playerId' => $fleet['player_id'],
                'name' => $fleet['name'],
                'col' => $fleet['col'],
                'row' => $fleet['row'],
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
        $return = [];
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
        if ($numArks > 0) $return['ark'] = $numArks;
        if ($numSmall > 0) $return['small'] = $numSmall;
        if ($numMedium > 0) $return['medium'] = $numMedium;
        if ($numLarge > 0) $return['large'] = $numLarge;
        if ($numXlarge > 0) $return['xlarge'] = $numXlarge;
        return $return;
    }


}
