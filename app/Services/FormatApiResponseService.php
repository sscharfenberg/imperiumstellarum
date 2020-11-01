<?php

namespace App\Services;

use App\Models\Planet;
use App\Models\PlayerResource;
use App\Models\Star;
use App\Models\StorageUpgrade;

class FormatApiResponseService {


    /**
     * @function format api response for Star
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
     * @function format api response for Planet
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
            'resources' => $planet->resources
        ];
    }

    /**
     * @function format api response for StorageUpgrade
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
     * @function format api response for StorageUpgrade
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
}
