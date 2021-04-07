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

    /**
     * @param Collection $opponents
     * @param int $column
     * @return int|null
     */
    public function getClosestOpponentCol (Collection $opponents, int $column): int
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
     * @function shuffle the encounter fleets for random order
     * @param Collection $encounter
     * @return Collection
     */
    public function randomFleetOrder (Collection $encounter): Collection
    {
        return $encounter['fleets']->shuffle();
    }

    /**
     * @function get the encounters attackers
     * @param Collection $encounter
     * @return Collection
     */
    public function getAttackers (Collection $encounter): Collection
    {
        return $encounter['fleets']->filter(function ($fleet) {
            return $fleet['attacker'];
        });
    }

    /**
     * @function get the encounters defenders
     * @param Collection $encounter
     * @return Collection
     */
    public function getDefenders (Collection $encounter): Collection
    {
        return $encounter['fleets']->filter(function ($fleet) {
            return !$fleet['attacker'];
        });
    }

    /**
     * @function format the name of a fleet/shipyard
     * @param array $fleet
     * @return string
     */
    public function getFleetFullName (array $fleet): string
    {
        return "[".$fleet['playerTicker']."] ".$fleet['name'];
    }

}
