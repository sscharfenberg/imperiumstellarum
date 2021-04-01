<?php
namespace App\Services;

use App\Models\Encounter;
use App\Models\Player;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EncounterService {

    /**
     * @function get encounters where the player is a participant
     * @param Player $player
     * @param string $gameId
     * @return Collection
     */
    public function getPlayerEncounters (Player $player, string $gameId): Collection
    {
        return Encounter::where('game_id', '=', $gameId)
            //->whereNotNull('processed_at')
            ->whereHas('participants', function (Builder $query) use ($player) {
                $query->where('player_id', '=', $player->id);
            })
            ->get();
    }


}
