<?php

namespace App\Http\Traits\Game;

trait UsesEffectiveResearch
{

    /**
     * @function calculate effective Research
     * @param float $priority
     * @param float $population
     * @return float
     */
    public function calculateEffectiveResearch (float $priority, float $population)
    {
        if($priority <= 1) return ceil($priority * $population);
        for ($i = 1; $i < $priority; $i++) {
            $priority = $priority * 0.92;
        }
        return floor($population * $priority);
    }

}
