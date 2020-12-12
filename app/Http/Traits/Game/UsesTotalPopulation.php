<?php

namespace App\Http\Traits\Game;

use App\Models\Player;

trait UsesTotalPopulation
{

    /**
     * @function get players total population
     * @param Player $player
     * @return float
     */
    private function getTotalPopulation(Player $player): float
    {
        $stars = $player->stars;
        $planets = collect();
        foreach($stars as $star) {
            $planets = $planets->concat($star->planets);
        }
        return $planets->sum('population');
    }


}
