<?php

namespace App\Http\Traits\Game;

use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerRelation;

trait UsesDiplomacyVerification
{

    /**
     * @function verify that the status set by the client is valid:
     * int, within allowed range, and within allowed range for current status
     * @param int $status
     * @param PlayerRelation|null $relation
     * @return bool
     */
    public function isStatusValid (int $status, PlayerRelation $relation = null): bool
    {
        $validStatii = array_keys(config('rules.diplomacy.status'));
        $currentStatus = $relation ? $relation->status : 1;
        $validNewStatii = config('rules.diplomacy.status.'.$currentStatus.'.validChange');
        return is_int($status)
            && in_array($status, $validStatii)
            && in_array($status, $validNewStatii);
    }

}
