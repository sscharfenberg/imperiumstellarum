<?php
namespace App\Services;

use Illuminate\Support\Str;

class PlayerDefaultService {


    /**
     * @function create default stores of a player
     * @param string $playerId
     * @param string $gameId
     * @return array
     */
    public function resources (string $playerId, string $gameId): array
    {
        $rules = config('rules.player.resourceTypes');
        $resources = [];
        foreach($rules as $key => $rule) {
            $resources[] = [
                'id' => Str::uuid(),
                'player_id' => $playerId,
                'game_id' => $gameId,
                'resource_type' => $key,
                'storage' => $rule['0']['amount'],
                'storage_level' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        return $resources;
    }


    /**
     * @function create default tech levels for player
     * @param string $playerId
     * @return array
     */
    public function techLevels (string $playerId): array
    {
        $rules = config('rules.tech.areas');
        $techLevels = [];
        foreach($rules as $key => $tech) {
            $techLevels[] = [
                'id' => Str::uuid(),
                'player_id' => $playerId,
                'type' => $key,
                'level' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        return $techLevels;
    }

}
