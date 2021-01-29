<?php

namespace App\Http\Traits\Game;

use App\Models\PlayerRelation;
use App\Models\PlayerRelationChange;

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

    /**
     * @function verify that there is no relation change pending
     * @param string $gameId
     * @param string $playerId
     * @param string $recipientId
     * @return bool
     */
    public function hasNoRelationChangePending (string $gameId, string $playerId, string $recipientId): bool
    {
        $relationChange = PlayerRelationChange::where('game_id', $gameId)
            ->where('player_id', $playerId)
            ->where('recipient_id', $recipientId)
            ->first();
        if ($relationChange) return false;
        return true;
    }

}
