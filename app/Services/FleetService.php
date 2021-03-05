<?php
namespace App\Services;

use App\Models\Player;
use App\Models\Star;
use Exception;

class FleetService {


    /**
     * @function calculate the fleet travel time between two stars
     * @param Star $start
     * @param Star $end
     * @return int
     */
    public function travelTime (Star $start, Star $end): int
    {
        $turnsPerDistance = config('rules.fleets.movement.turnsPerDistance');
        $distance = sqrt(
            pow($end->coord_x - $start->coord_x, 2) +
            pow($end->coord_y - $start->coord_y, 2)
        );
        return (int)ceil($distance * $turnsPerDistance);
    }

    /**
     * @function check if a star has an owner
     * @param Star $star
     * @param Player $player
     * @return bool
     */
    public function starHasDifferentOwner (Star $star, Player $player):bool
    {
        return !!($star->player_id && $star->player_id !== $player->id);
    }

}
