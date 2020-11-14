<?php

namespace App\Http\Traits\Game;

use App\Models\Player;

trait UsesTotalPopulation
{

    /**
     * @function get players total population
     * @param Player $player
     * @return mixed
     */
    private function getTotalPopulation(Player $player)
    {
        $stars = $player->stars;
        $planets = collect();
        foreach($stars as $star) {
            $planets = $planets->concat($star->planets);
        }
        return $planets->filter(function($planet) {
            return $planet->population > 0;
        })->reduce(function ($carry, $planet) {
            return $carry + $planet->population;
        });
    }


}
