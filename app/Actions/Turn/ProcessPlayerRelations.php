<?php

namespace App\Actions\Turn;

use App\Models\PlayerRelation;
use App\Models\PlayerRelationChange;
use Exception;
use App\Models\Game;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;


class ProcessPlayerRelations
{

    /**
     * @function complete player relation changes
     * @param Collection $relations
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    public function completeRelationChange(Collection $relations, string $turnSlug)
    {
        foreach($relations as $relation) {
            $playerRelation = PlayerRelation::where('game_id', '=', $relation->game_id)
                ->where('player_id', '=', $relation->player_id)
                ->where('recipient_id', '=', $relation->recipient_id)
                ->first();
            $recipientTicker = $relation->recipient->ticker;
            $playerTicker = $relation->player->ticker;
            if ($playerRelation) {
                $playerRelation->status = $relation->status;
                $playerRelation->save();
                Log::notice("TURN PROCESSING $turnSlug - updated PlayerRelation from $playerTicker to $recipientTicker with status $relation->status.");
            } else {
                PlayerRelation::create([
                    'game_id' => $relation->game_id,
                    'player_id' => $relation->player_id,
                    'recipient_id' => $relation->recipient_id,
                    'status' => $relation->status
                ]);
                Log::notice("TURN PROCESSING $turnSlug - created new PlayerRelation from $playerTicker to $recipientTicker with status $relation->status.");
            }
            // updates done, delete PlayerRelationChange
            try {
                $relation->delete();
                // TODO: send system message to player that relation was changed.
                // TODO: determine if a message needs to be sent to recipient
            } catch(Exception $e) {
                Log::error("TURN PROCESSING $turnSlug - failed to delete PlayerRelationChanges $relation->id: ".$e->getMessage());
            }
        };
    }



    /**
     * @function build ships
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    public function handle(Game $game, string $turnSlug)
    {
        $relationChangePending = PlayerRelationChange::where('game_id', $game->id)
            ->where('until_done', '>', '0')
            ->decrement('until_done');
        if ($relationChangePending) {
            Log::notice("TURN PROCESSING $turnSlug - Decreased 'until_done' for $relationChangePending PlayerRelationChanges.");
        } else {
            Log::notice("TURN PROCESSING $turnSlug - No PlayerRelationChanges.");
        }

        // find out which relationChanges are finished
        $relationChangesToComplete = PlayerRelationChange::where('game_id', $game->id)
            ->where('until_done', '=', '0')
            ->get();

        $num = count($relationChangesToComplete);
        if ($num > 0) {
            Log::notice("TURN PROCESSING $turnSlug - Finalizing $num pending PlayerRelationChanges.");
            $this->completeRelationChange($relationChangesToComplete, $turnSlug);
        } else {
            Log::notice("TURN PROCESSING $turnSlug - No PlayerRelationChanges need to be completed.");
        }
        Log::notice("TURN PROCESSING $turnSlug - finished processing PlayerRelationChanges.");

    }

}
