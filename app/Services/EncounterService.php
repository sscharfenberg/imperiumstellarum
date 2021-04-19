<?php
namespace App\Services;

use App\Models\Encounter;
use App\Models\Player;

use App\Models\Star;
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
            ->whereNotNull('processed_at')
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
    public function getClosestOpponentCol (Collection $opponents, int $column): ?int
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
     * @function check if a fleet with id exists
     * @param string $fleetId
     * @param Collection $encounter
     * @return bool
     */
    public function fleetExists (string $fleetId, Collection $encounter): bool
    {
        return $encounter['fleets']->where('id', $fleetId)->count() === 1
            && $encounter['fleets']->where('id', $fleetId)->first()['ships']->count() > 0;
    }

    /**
     * @function check if a fleetShip with id exists
     * @param string $fleetId
     * @param string $shipId
     * @param Collection $encounter
     * @return bool
     */
    public function fleetShipExists (string $fleetId, string $shipId, Collection $encounter): bool
    {
        $fleet = $encounter['fleets']->where('id', $fleetId)->first();
        return $fleet['ships']->where('id', $shipId)->count() === 1
            && $fleet['ships']->where('id', $shipId)->first()['hp_structure_current'] > 0;
    }

    /**
     * @function get a fleet's ships. only returns ships that are not dead.
     * @param array $fleet
     * @return Collection $fleet['ships']
     */
    public function getNotDeadShips (array $fleet): Collection
    {
        return $fleet['ships']->filter( function($ship) {
            return $ship['hp_structure_current'] > 0;
        });
    }

    /**
     * @function format the name of a fleet/shipyard
     * @param array $fleet
     * @return string
     */
    public function getFleetFullName (array $fleet): string
    {
        return "[".$fleet['player_ticker']."] ".$fleet['name'];
    }

    /**
     * @function get targetted ship from target queue by fleetId
     * @param Collection $encounter
     * @param bool $attacker
     * @param string $fleetId
     * @return string
     */
    public function getShipIdFromTargetQueue (Collection $encounter, bool $attacker, string $fleetId): string
    {
        $queue = $attacker ? $encounter['attacker_queue'] : $encounter['defender_queue'];
        $target = $queue->where('fleet_id', $fleetId)->first();
        return $target['ship_id'];
    }

    /**
     * @function get weapon range from rules config
     * @param string $techType
     * @param string $hullType
     * @return int
     */
    public function getWeaponRange (string $techType, string $hullType): int
    {
        return collect(config('rules.modules'))->filter(function ($mod) use ($hullType, $techType) {
            return $mod['moduleType'] === 'offensive'
                && $mod['hullType'] === $hullType
                && $mod['techType'] === $techType;
        })->first()['range'];
    }

    /**
     * @function calculate distance between two fleets.
     * @param array $fleet
     * @param array $targetFleet
     * @return int
     */
    public function getFleetDistance (array $fleet, array $targetFleet): int
    {
        return max($fleet['col'], $targetFleet['col']) - min($fleet['col'], $targetFleet['col']);
    }

    /**
     * @function get offensive tech types as flat array
     * @return array
     */
    public function getWeaponTechAreas (): array
    {
        return array_keys(
            collect(config('rules.tech.areas'))->filter(function ($area) {
                return isset($area['dmgMultipliers']);
            })->toArray()
        );
    }

    /**
     * @function get damage multiplier for a techType vs. a hpArea
     * @param string $techType
     * @param string $hpArea
     * @return float
     */
    public function getDamageMultiplier (string $techType, string $hpArea): float
    {
        if ($hpArea === 'structure') return 1;
        return config('rules.tech.areas.'.$techType.'.dmgMultipliers.'.$hpArea);
    }

    /**
     * @function get range multiplier for damage from distance and range
     * @param int $distance
     * @param int $range
     * @return float
     */
    public function getRangeMultiplier (int $distance, int $range): float
    {
        if ($range >= $distance) return 1; // within range
        if ($range * config('rules.encounters.falloff.rangeMultiplier') >= $distance)
            return config('rules.encounters.falloff.damageMultiplier'); // falloff
        return 0; // out of range
    }


    /**
     * @function get a home destination for a player so the fleets can return home.
     * @param Player $player
     * @return Star
     */
    public function getHomeDestination (Player $player): Star
    {
        $playerStars = $player->stars;
        $starsScored = collect();
        $playerStars->each(function ($star) use (&$starsScored) {
            $rules = config('rules.encounters.homeStarFactors');
            $harvesters = $star->harvesters_count;
            $shipyards = $star->shipyards;
            $shipyardsScore = $shipyards->reduce(function ($carry, $shipyard) use ($rules) {
                return $carry + $rules['shipyard'][$shipyard->type];
            });
            $fleetsScore = $star->fleets->count() * $rules['fleet'];
            // push star with score into collection
            $starsScored->push([
                $star->id => $harvesters * $rules['harvester'] + $shipyardsScore + $fleetsScore
            ]);
        });
        // get key of the star with the highest score.
        $destinationId = key($starsScored->sortDesc()->first());
        return Star::find($destinationId);
    }


}
