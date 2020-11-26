<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Research;
use App\Models\Star;
use App\Models\Planet;
use App\Models\StorageUpgrade;
use App\Models\PlayerResource;
use App\Models\Harvester;
use App\Models\Shipyard;
use App\Models\TechLevel;

class FormatApiResponseService {


    /**
     * @function format api response for a Star
     * @param Star $star
     * @return array
     */
    public function formatStar (Star $star)
    {
        return [
            'id' => $star->id,
            'x' => $star->coord_x,
            'y' => $star->coord_y,
            'spectral' => $star->spectral,
            'name' => $star->name
        ];
    }

    /**
     * @function format api response for a Planet
     * @param Planet $planet
     * @return array
     */
    public function formatPlanet (Planet $planet)
    {
        return [
            'id' => $planet->id,
            'starId' => $planet->star_id,
            'orbitalIndex' => $planet->orbital_index,
            'type' => $planet->type,
            'population' => $planet->population,
            'foodConsumption' => $planet->food_consumption,
            'resources' => $planet->resources
        ];
    }

    /**
     * @function format api response for a StorageUpgrade
     * @param StorageUpgrade $upgrade
     * @return array
     */
    public function formatStorageUpgrades (StorageUpgrade $upgrade)
    {
        return [
            'resourceType' => $upgrade->resource_type,
            'newLevel' => $upgrade->new_level,
            'untilComplete' => $upgrade->until_complete
        ];
    }

    /**
     * @function format api response for a PlayerResource
     * @param PlayerResource $res
     * @return array
     */
    public function formatPlayerResource (PlayerResource $res)
    {
        return [
            'type' => $res->resource_type,
            'amount' => $res->storage,
            'max' => config(
                'rules.player.resourceTypes.'.$res->resource_type.'.'.$res->storage_level.'.amount'
            ),
            'level' => $res->storage_level,
            'maxLevel' => array_key_last(config('rules.player.resourceTypes.'.$res->resource_type))
        ];
    }

    /**
     * @function format api response for a harvester
     * @param Harvester $harvester
     * @return array
     */
    public function formatHarvester (Harvester $harvester)
    {
        return [
            'id' => $harvester->id,
            'planetId' => $harvester->planet_id,
            'resourceType' => $harvester->resource_type,
            'production' => $harvester->production,
            'untilComplete' => $harvester->until_complete
        ];
    }

    /**
     * @function format api response for a shipyard
     * @param Shipyard $shipyard
     * @return array
     */
    public function formatShipyard (Shipyard $shipyard)
    {
        return [
            'id' => $shipyard->id,
            'planetId' => $shipyard->planet_id,
            'small' => $shipyard->small,
            'medium' => $shipyard->medium,
            'large' => $shipyard->large,
            'xlarge' => $shipyard->xlarge,
            'untilComplete' => $shipyard->until_complete
        ];
    }

    /**
     * @function format api response for a techLevel
     * @param TechLevel $techLevel
     * @return array
     */
    public function formatTechLevel (TechLevel $techLevel)
    {
        return [
            'id' => $techLevel->id,
            'type' => $techLevel->type,
            'level' => $techLevel->level
        ];
    }

    /**
     * @function format api response for a research job
     * @param Research $research
     * @return array
     */
    public function formatResearchJob (Research $research)
    {
        return [
            'id' => $research->id,
            'techLevelId' => $research->tech_level_id,
            'type' => $research->type,
            'level' => $research->level,
            'remaining' => $research->remaining,
            'work' => $research->work,
            'order' => $research->order
        ];
    }


    /**
     * @function format api response for a player (that isn't the current player)
     * @param Player $player
     * @return array
     */
    public function formatPlayer (Player $player)
    {
        return [
            'id' => $player->id,
            'ticker' => $player->ticker,
            'name' => $player->name,
            'colour' => $player->colour
        ];
    }
}
