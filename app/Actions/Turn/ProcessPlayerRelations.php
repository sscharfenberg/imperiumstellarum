<?php

namespace App\Actions\Turn;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\Game;
use App\Models\PlayerRelation;
use App\Models\PlayerRelationChange;
use App\Services\MessageService;
use App\Services\PlayerRelationService;
use Exception;

class ProcessPlayerRelations
{

    /**
     * @function notify both the initiator and the recipient of the relation change
     * @param PlayerRelationChange $relationChange
     * @param Collection $gameRelations
     */
    private function notifyPlayers (PlayerRelationChange $relationChange, Collection $gameRelations)
    {
        $m = new MessageService;
        $p = new PlayerRelationService;
        $gameUsers = $m->getGameUsers($relationChange->game_id);
        $gameRelations = PlayerRelation::where('game_id', $relationChange->game_id)->get();
        $initiator = $relationChange->player;
        $recipient = $relationChange->recipient;

        // notify initiator
        $messageLocale = $gameUsers
            ->where('id', '=', $initiator->user_id)
            ->first()
            ->locale;
        $effectiveRelation = $p->getEffectiveRelation($initiator, $recipient, $gameRelations);
        $m->sendNotification(
            $relationChange->player,
            'game.messages.sys.diplomacy.relationChangeInitiator.subject',
            'game.messages.sys.diplomacy.relationChangeInitiator.body',
            [
                'ticker' => $recipient->ticker,
                'status' => $relationChange->status,
                'statusString' => __('game.diplomacy.relations.'.$relationChange->status, [], $messageLocale),
                'effective' => $effectiveRelation,
                'effectiveString' => __('game.diplomacy.relations.'.$effectiveRelation, [], $messageLocale)
            ]
        );

        // notify recipient
        $messageLocale = $gameUsers
            ->where('id', '=', $recipient->user_id)
            ->first()
            ->locale;
        $effectiveRelation = $p->getEffectiveRelation($recipient, $initiator, $gameRelations);
        $m->sendNotification(
            $relationChange->recipient,
            'game.messages.sys.diplomacy.relationChangeRecipient.subject',
            'game.messages.sys.diplomacy.relationChangeRecipient.body',
            [
                'ticker' => $recipient->ticker,
                'status' => $relationChange->status,
                'statusString' => __('game.diplomacy.relations.'.$relationChange->status, [], $messageLocale),
                'effective' => $effectiveRelation,
                'effectiveString' => __('game.diplomacy.relations.'.$effectiveRelation, [], $messageLocale)
            ]
        );
    }


    /**
     * @function complete player relation changes
     * @param Collection $relationChanges
     * @param string $turnSlug
     * @param string $gameId
     * @return void
     *@throws Exception
     */
    public function completeRelationChange(Collection $relationChanges, string $turnSlug, string $gameId)
    {
        foreach($relationChanges as $relationChange) {
            $playerRelation = PlayerRelation::where('game_id', '=', $relationChange->game_id)
                ->where('player_id', '=', $relationChange->player_id)
                ->where('recipient_id', '=', $relationChange->recipient_id)
                ->first();
            $recipientTicker = $relationChange->recipient->ticker;
            $playerTicker = $relationChange->player->ticker;
            if ($playerRelation) {
                $playerRelation->status = $relationChange->status;
                $playerRelation->save();
                Log::notice("TURN PROCESSING $turnSlug - updated PlayerRelation from $playerTicker to $recipientTicker with status $relationChange->status.");
            } else {
                PlayerRelation::create([
                    'game_id' => $relationChange->game_id,
                    'player_id' => $relationChange->player_id,
                    'recipient_id' => $relationChange->recipient_id,
                    'status' => $relationChange->status
                ]);
                Log::notice("TURN PROCESSING $turnSlug - created new PlayerRelation from $playerTicker to $recipientTicker with status $relationChange->status.");
            }
            // updates done, delete PlayerRelationChange
            try {
                $relationChange->delete();
                $gameRelations = PlayerRelation::where('game_id', $gameId)->get();
                $this->notifyPlayers($relationChange, $gameRelations);
            } catch(Exception $e) {
                Log::error("TURN PROCESSING $turnSlug - failed to delete PlayerRelationChanges $relationChange->id: ".$e->getMessage());
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
            $this->completeRelationChange($relationChangesToComplete, $turnSlug, $game->id);
        } else {
            Log::notice("TURN PROCESSING $turnSlug - No PlayerRelationChanges need to be completed.");
        }
        Log::notice("TURN PROCESSING $turnSlug - finished processing PlayerRelationChanges.");

    }

}
