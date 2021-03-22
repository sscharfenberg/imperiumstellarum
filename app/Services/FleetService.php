<?php
namespace App\Services;

use App\Models\Fleet;
use App\Models\Player;
use App\Models\Star;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class FleetService {


    /**
     * @function calculate the fleet travel time between two stars
     * @param Star $start
     * @param Star $end
     * @return int
     */
    public function travelTime (Star $start, Star $end): int
    {
        $turnsPerDistance = config('rules.fleets.movement.turnsPerDistance');
        $distance = sqrt(
            pow($end->coord_x - $start->coord_x, 2) +
            pow($end->coord_y - $start->coord_y, 2)
        );
        return (int)ceil($distance * $turnsPerDistance);
    }


    public function getForeignFleets (Player $player): Collection
    {
        // IDs of player owned stars
        $playerStarIds = Star::where('game_id', '=', $player->game_id)
            ->where('player_id', '=', $player->id)
            ->get()
            ->map(function($star) {
                return $star->id;
            })->toArray();
        // IDs of stars where one of the player's fleets is located.
        $fleetStarIds = Fleet::where('game_id', '=', $player->game_id)
            ->where('player_id', '=', $player->id)
            ->whereNotNull('star_id')
            ->whereNotIn('star_id', $playerStarIds)
            ->get()
            ->map(function($fleet){
                return $fleet->star_id;
            });
        // find the 'foreign' fleets
        return Fleet::where('game_id', '=', $player->game_id)
            // foreign fleets at the player's stars
            ->where('player_id', '!=', $player->id)
            ->whereIn('star_id', $playerStarIds)
            // foreign fleets at foreign stars that have at least one of the player's fleets present
            ->orWhere(function($query) use ($player, $fleetStarIds) {
                $query->where('game_id', '=', $player->game_id)
                    ->where('player_id', '!=', $player->id)
                    ->whereIn('star_id', $fleetStarIds);
            })
            ->get();
    }

    /**
     * @function check if a star has an owner
     * @param Star $star
     * @param Player $player
     * @return bool
     */
    public function starHasDifferentOwner (Star $star, Player $player):bool
    {
        return !!($star->player_id && $star->player_id !== $player->id);
    }

    /**
     * @function get a fleets preferred range, calculated by max damage of a weapon type.
     * @param array $ships
     * @return int
     */
    public function getFleetPreferredRange (array $ships): int
    {
        $preferedRange = 10;
        $accDmgRanges = [
            'small.laser' => 0,
            'small.missile' => 0,
            'small.railgun' => 0,
            'small.plasma' => 0,
            'medium.laser' => 0,
            'medium.missile' => 0,
            'medium.railgun' => 0,
            'medium.plasma' => 0,
            'large.laser' => 0,
            'large.missile' => 0,
            'large.railgun' => 0,
            'large.plasma' => 0,
            'xlarge.laser' => 0,
            'xlarge.missile' => 0,
            'xlarge.railgun' => 0,
            'xlarge.plasma' => 0,
        ];
        foreach($ships as $ship) {
            $hullType = $ship['hull_type'];
            if ($ship['dmg_laser'] > 0) $accDmgRanges[$hullType.".laser"] += $ship['dmg_laser'];
            if ($ship['dmg_missile'] > 0) $accDmgRanges[$hullType.".missile"] += $ship['dmg_missile'];
            if ($ship['dmg_railgun'] > 0) $accDmgRanges[$hullType.".railgun"] += $ship['dmg_railgun'];
            if ($ship['dmg_plasma'] > 0) $accDmgRanges[$hullType.".plasma"] += $ship['dmg_plasma'];
        }
        $maxDmg = max($accDmgRanges);
        if ($maxDmg > 0) {
            $maxDmgWpnType = array_search(max($accDmgRanges), $accDmgRanges);
            $preferedRange = collect(config('rules.modules'))->filter(function($mod) use ($maxDmgWpnType) {
                return $mod['hullType'] === explode('.', $maxDmgWpnType)[0]
                    && $mod['techType'] === explode('.', $maxDmgWpnType)[1];
            })->first()['range'];
        }
        return $preferedRange;
    }

}
