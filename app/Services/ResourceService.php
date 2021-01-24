<?php
namespace App\Services;

use App\Models\Player;
use App\Models\PlayerResource;
use Illuminate\Support\Collection;

class ResourceService {

    /**
     * @function get all resources of a player and format it
     * @param Player $player
     * @return Collection
     */
    public function getResources (Player $player): Collection
    {
        $f = new FormatApiResponseService;
        return $player->resources->map(function ($res) use ($f) {
            return $f->formatPlayerResource($res);
        });
    }

    /**
     * @function enforce maximum values for resource storage
     * @param PlayerResource $res
     * @return int
     */
    public function enforceStorageMax (PlayerResource $res): int
    {
        $max = config('rules.player.resourceTypes.'.$res->resource_type.'.'.$res->storage_level.'.amount');
        $amount = $res->storage;
        $amount = $amount > $max ? $max : $amount;
        return intval(round($amount));
    }

    /**
     * @function check if the player has the necessary resources
     * this function ignores population, since it works differently from normal player resources.
     * @param Player $player
     * @param array $costs
     * @return bool
     */
    public function playerCanAfford (Player $player, array $costs): bool
    {
        $res = $player->resources()->get();
        foreach($costs as $resType => $amount) {
            if (
                $resType !== 'population' &&
                $amount > $res->where('resource_type', $resType)->first()->storage
            ) return false;
        }
        return true;
    }

    /**
     * @function subtracts resources from a player
     * @param Player $player
     * @param array $costs
     * @return void
     */
    public function subtractResources(Player $player, array $costs)
    {
        foreach($costs as $resType => $amount) {
            if ($resType !== 'population') {
                $playerResource = $player->resources->where('resource_type', $resType)->first();
                $playerResource->storage -= $amount;
                $playerResource->save();
            }
        }
    }

    /**
     * @function calculate ship resource costs from hullType and modules
     * @param string $hullType
     * @param array $modules
     * @return array
     */
    public function getShipResourceCosts(string $hullType, array $modules): array
    {
        // prepare costs
        $costs = [];
        foreach(config('rules.player.resourceTypes') as $resType => $value) {
            $costs[$resType] = 0;
        }
        // hull costs
        foreach(config('rules.ships.hullTypes.'.$hullType.'.costs') as $type => $value) {
            if ($type !== 'turns') $costs[$type] += $value;
        }
        // module costs
        foreach($modules as $mod) {
            $moduleCosts = collect(config('rules.modules'))
                ->filter(function($c) use ($hullType, $mod) {
                    return $c['hullType'] === $hullType && $c['techType'] === $mod;
                })->first()['costs'];
            foreach($moduleCosts as $key => $value) {
                if ($key !== 'population' && $key !== 'turns') $costs[$key] += $value;
                if ($key === 'population') $costs[$key] = $value;
            }
        }
        return $costs;
    }

    /**
     * @function calculate build duration of a ship
     * @param string $hullType
     * @param array $modules
     * @return int
     */
    public function getShipBuildDuration(string $hullType, array $modules): int
    {
        $turns = config('rules.ships.hullTypes.'.$hullType.'.costs.turns');
        foreach($modules as $mod) {
            $moduleCosts = collect(config('rules.modules'))
                ->filter(function($c) use ($hullType, $mod) {
                    return $c['hullType'] === $hullType && $c['techType'] === $mod;
                })->first()['costs']['turns'];
            $turns += $moduleCosts;
        }
        return $turns;
    }

}
