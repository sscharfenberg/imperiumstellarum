<?php

namespace App\Http\Traits\Game;

use App\Models\Player;
use App\Models\Fleet;

trait UsesFleetsVerification
{

    /**
     * @function verify the player owns the star with the supplied id
     * @param Player $player
     * @param string $starId
     * @return bool
     */
    public function playerOwnsStar (Player $player, string $starId): bool
    {
        return $player->stars->containsStrict('id', $starId);
    }

    /**
     * @function validate fleet name
     * @param string $name
     * @return bool
     */
    public function isFleetNameValid (string $name): bool
    {
        return is_string($name)
            && strlen($name) >= config('rules.fleets.name.min')
            && strlen($name) <= config('rules.fleets.name.max');
    }

    /**
     * @function verify the player owns the fleet with the supplied id
     * @param Player $player
     * @param string $fleetId
     * @return bool
     */
    public function playerOwnsFleet (Player $player, string $fleetId): bool
    {
        return $player->fleets->containsStrict('id', $fleetId);
    }

    /**
     * @function verify that the fleet has no ships assigned.
     * @param Fleet $fleet
     * @return bool
     */
    public function isFleetEmpty(Fleet $fleet): bool
    {
        return count($fleet->ships) === 0;
    }

    /**
     * @function verify the fleet name is unique.
     * @param Player $player
     * @param string $name
     * @return bool
     */
    public function isFleetNameUnique(Player $player, string $name): bool
    {
        $fleets = $player->fleets;
        return !$fleets->containsStrict('name', $name);
    }

}
