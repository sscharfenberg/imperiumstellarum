<?php

namespace App\Http\Traits\Game;

use App\Models\Planet;
use App\Models\Player;
use App\Services\ResourceService;

trait UsesEmpireVerification
{

    /**
     * @function check if player owns the star that the planet belongs to
     * @param Player $player
     * @param Planet $planet
     * @return bool
     */
    private function playerOwnsPlanet(Player $player, Planet $planet): bool
    {
        $playerStar = $player->stars->find($planet->star->id);
        if ($playerStar) return true;
        return false;
    }

    /**
     * @function verify there is an empty slot for the harvester
     * @param Player $player
     * @param Planet $planet
     * @param string $resourceType
     * @return false
     */
    private function harvesterInstallable(Player $player, Planet $planet, string $resourceType): bool
    {
        $numPlayerHarvesters = count($player->harvesters
            ->where('planet_id', $planet->id)
            ->where('resource_type', $resourceType)
        );
        $resourceSlots = array_filter($planet->resources, function($res) use ($resourceType) {
            return $res["resourceType"] === $resourceType;
        });
        $numSlots = array_values($resourceSlots)[0]['slots'];
        return $numSlots > $numPlayerHarvesters;
    }

    /**
     * @function verify player has the necessary resources
     * @param Player $player
     * @param string $type
     * @return bool
     */
    private function playerCanAffordHarvester (Player $player, string $type): bool
    {
        $costs = array_filter(
            config('rules.harvesters.'.$type.'.costs'), function($resType) {
            return $resType !== 'turns';
        }, ARRAY_FILTER_USE_KEY);
        $r = new ResourceService();
        if (!$r->playerCanAfford($player, $costs)) return false;
        return true;
    }

    /**
     * @function check if foodConsumption is valid and within bounds
     * @param float $foodConsumption
     * @return bool
     */
    private function foodConsumptionValid(float $foodConsumption): bool
    {
        if (!is_numeric($foodConsumption)) return false;
        return $foodConsumption >= config('rules.planets.food.min')
            && $foodConsumption <= config('rules.planets.food.max');
    }

    /**
     * @function verify planet has population > 0
     * @param Planet $planet
     * @return bool
     */
    private function verifyPlanetHasPopulation(Planet $planet): bool
    {
        return $planet->population > 0;
    }

    /**
     * @function verify the planet does not already have a shipyard
     * @param Planet $planet
     * @return bool
     */
    private function shipyardInstallable(Planet $planet): bool
    {
        return $planet->shipyard === null;
    }

    /**
     * @function verify the indicated type is indeed the direct upgrade without skipping a shipyard type
     * @param Planet $planet
     * @param string $type
     * @return boolean
     */
    private function shipyardUpgradeable(Planet $planet, string $type): bool
    {
        $shipyard = $planet->shipyard;
        if (!$shipyard) return false;
        if ($type === "medium" && $shipyard->type === "small") return true;
        if ($type === "large" && $shipyard->type === "medium") return true;
        if ($type === "xlarge" && $shipyard->type === "large") return true;
        return false;
    }

}
