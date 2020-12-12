<?php

namespace App\Http\Traits\Game;

trait UsesPopulationGrowth
{

    /**
     * @function calculate new population from "foodConsumption per population"
     * @param float $population
     * @param float $foodPerPop
     * @return float
     */
    public function calculateNewPopulation (float $population, float $foodPerPop): float
    {
        $starvingMultiplier = 0.8;
        $newPop = $foodPerPop < 1
            ? $population * (1 + ((log($foodPerPop) * 3) / 100))
            : $population + ((exp($foodPerPop - 3) / $population) / 20);
        $newPop = $foodPerPop < 0.01 ? $population * $starvingMultiplier : $newPop;
        return round($newPop, 8);
    }

}
