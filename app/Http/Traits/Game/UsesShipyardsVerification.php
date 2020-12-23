<?php

namespace App\Http\Traits\Game;

use App\Models\Player;

trait UsesShipyardsVerification
{

    /**
     * @function ensure the blueprint is owned by the requesting player
     * @param string $id
     * @param Player $player
     * @return bool
     */
    private function blueprintIsPlayerOwned (string $id, Player $player): bool
    {
        return $player->blueprints->containsStrict('id', $id);
    }

    /**
     * @function validate input
     * @param string $name
     * @return bool
     */
    private function isClassNameValid (string $name): bool
    {
        return is_string($name)
            && strlen($name) >= config('rules.blueprints.className.min')
            && strlen($name) <= config('rules.blueprints.className.max');
    }

    /**
     * @function verifies that the player has room for another blueprint
     * @param Player $player
     * @return bool
     */
    private function blueprintMaxNotReached (Player $player): bool
    {
        $numBps = count($player->blueprints);
        $numMaxBps = $this->getTotalPopulation($player) * config('rules.blueprints.num.factor');
        $max = config('rules.blueprints.num.max');
        if ($numMaxBps > $max) $numMaxBps = $max;
        return $numBps < $numMaxBps;
    }

    /**
     * @function check if the hullType exists and the player has a shipyard that can build these ships.
     * @param string $hullType
     * @param Player $player
     * @return bool
     */
    private function isHullTypeValid (string $hullType, Player $player): bool
    {
        $allowedShipyards = [];
        $allShipyards = collect(config('rules.shipyards.hullTypes'));
        foreach ($allShipyards as $key => $shipyard) {
            // if the shipyard contains $hullType in construct array, add to $allowedShipyards
            if (in_array($hullType, $shipyard['construct'])) $allowedShipyards[] = $key;
        }
        return
            in_array($hullType, array_keys(config('rules.ships.hullTypes')))
            && count($player->shipyards->whereIn('type', $allowedShipyards)) > 0;
    }

    /**
     * @function ensure module max is not exceeded
     * @param string $hullType
     * @param int $numModules
     * @return bool
     */
    private function moduleMaxValid (string $hullType, int $numModules): bool
    {
        return $numModules <= config('rules.ships.hullTypes.'.$hullType.'.slots');
    }

    /**
     * @function ensure all modules are valid for this hullType
     * @param string $hullType
     * @param array $modules
     * @return bool
     */
    private function areModulesValid (string $hullType, array $modules): bool
    {
        $validModules = array_map(function($m) {
            return $m['techType'];
        }, array_filter(config('rules.modules'), function($module) use ($hullType) {
            return $module['hullType'] === $hullType;
        }));
        return count(array_diff($modules, $validModules)) === 0;
    }

    /**
     * @function check if the class name is already used
     * @param Player $player
     * @param string $name
     * @return bool
     */
    private function isClassNameUnique (Player $player, string $name): bool
    {
        return count($player->blueprints->where('name', $name)) === 0;
    }

}
