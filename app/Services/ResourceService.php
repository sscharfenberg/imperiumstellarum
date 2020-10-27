<?php
namespace App\Services;

use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

class ResourceService {


    /**
     * @param Player $player
     * @return Collection
     */
    public function getResources (Player $player)
    {
        return $player->resources->map(function ($store) {
            return [
                'type' => $store->resource_type,
                'amount' => $store->storage,
                'max' => config(
                    'rules.player.resourceTypes.'.$store->resource_type.'.'.$store->storage_level.'.amount'
                ),
                'level' => $store->storage_level,
                'maxLevel' => array_key_last(config('rules.player.resourceTypes.'.$store->resource_type))
            ];
        });
    }


    /**
     * @function check if the player has the necessary resources
     * @param Player $player
     * @param array $costs
     * @return bool
     */
    public function playerCanAfford (Player $player, array $costs)
    {
        $res = $player->resources()->get();
        foreach($costs as $resType => $amount) {
            if (
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
            $playerResource = $player->resources->where('resource_type', $resType)->first();
            $playerResource->storage -= $amount;
            $playerResource->save();
        }
    }

}
