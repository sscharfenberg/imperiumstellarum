<?php

namespace App\Actions\Turn\Encounter;

use Illuminate\Support\Collection;

trait UsesTurnLogging
{

    /**
     * @function log fleet movement for a specific turn
     * @param Collection $log - current encounter log for turns
     * @param Collection $data - data that should be logged
     * @param int $turn - turn number
     * @return Collection
     */
    private function logTurnMovement(Collection $log, Collection $data, int $turn): Collection
    {
        return $log->map(function ($t) use ($turn, $data) {
            if ($t['number'] === $turn) $t['movement'] = $data;
            return $t;
        });
    }


}
