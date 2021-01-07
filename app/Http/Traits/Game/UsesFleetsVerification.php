<?php

namespace App\Http\Traits\Game;

use App\Models\Player;

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
    private function isFleetNameValid (string $name): bool
    {
        return is_string($name)
            && strlen($name) >= config('rules.fleets.name.min')
            && strlen($name) <= config('rules.fleets.name.max');
    }

}
