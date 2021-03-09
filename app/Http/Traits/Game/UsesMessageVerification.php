<?php

namespace App\Http\Traits\Game;

use App\Models\Game;
use App\Models\MessageReport;
use App\Models\Player;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;

trait UsesMessageVerification
{

    /**
     * @function verify the recipient constraints (min/max) are met
     * @param array $recipientIds
     * @return bool
     */
    public function recipientConstraintsMet (array $recipientIds): bool
    {
        $rules = config('rules.messages.recipients');
        return count($recipientIds) >= $rules['min']
            && count($recipientIds) <= $rules['max'];
    }

    /**
     * @function check if the supplied UUIDs are all valid
     * @param array $recipientIds
     * @return bool
     */
    public function recipientsContainOnlyValidUuids (array $recipientIds): bool
    {
        $recipients = collect($recipientIds)->filter(function ($recipient) {
            return Uuid::isValid($recipient);
        });
        return count($recipientIds) === count($recipients);
    }

    /**
     * @function check if all recipients exists and are enlisted to the game
     * @param array $recipientIds
     * @param Game $game
     * @return bool
     */
    public function recipientsAreEnlisted (array $recipientIds, Game $game): bool
    {
        $recipients = Player::where('game_id',$game->id)
            ->where('dead', false)
            ->whereIn('id', $recipientIds)
            ->get();
        return count($recipientIds) === count($recipients);
    }

    /**
     * @function verify that the subject requirements are met.
     * @param string $subject
     * @return bool
     */
    public function subjectConstraintsMet (string $subject): bool
    {
        $rules = config('rules.messages.subject');
        return is_string($subject)
            && strlen($subject) >= $rules['min']
            && strlen($subject) <= $rules['max'];
    }

    /**
     * @function verify that the body requirements are met.
     * @param string $body
     * @return bool
     */
    public function bodyConstraintsMet (string $body): bool
    {
        $rules = config('rules.messages.body');
        return is_string($body)
            && strlen($body) >= $rules['min']
            && strlen($body) <= $rules['max'];
    }

    /**
     * @function verify that the message is in the players inbox
     * @param string $messageId
     * @param Player $player
     * @return bool
     */
    public function playerOwnsInboxMessage (string $messageId, Player $player): bool
    {
        $m = new MessageService;
        $inbox = $m->getPlayerInbox($player->id, $player->game_id);
        return $inbox->containsStrict('id', $messageId);
    }

    /**
     * @function check if the messages exist and have the player as sender_id
     * @param array $messageIds
     * @param Player $player
     * @return bool
     */
    public function messagesBelongToSender (array $messageIds, Player $player): bool
    {
        $m = new MessageService;
        $inbox = $m->getPlayerOutbox($player->id, $player->game_id);
        $messagesToDelete = $inbox->whereIn('id', $messageIds);
        return count($messagesToDelete) === count($messageIds);
    }

    /**
     * @function verify that the message is in the players inbox
     * @param array $messageIds
     * @param Player $player
     * @return bool
     */
    public function playerOwnsInboxMessages (array $messageIds, Player $player): bool
    {
        $m = new MessageService;
        $inbox = $m->getPlayerInbox($player->id, $player->game_id);
        $messages = $inbox->whereIn('id', $messageIds);
        return count($messages) === count($messageIds);
    }

    /**
     * @function verify that the comment requirements are met.
     * @param string $body
     * @return bool
     */
    public function commentConstraintsMet (string $body): bool
    {
        $rules = config('rules.messages.reportComment');
        return is_string($body)
            && strlen($body) >= $rules['min']
            && strlen($body) <= $rules['max'];
    }

    /**
     * @function verify that the player has not already reported this message
     * @param string $messageId
     * @param Player $player
     * @return bool
     */
    public function playerHasNoReportForMessage (string $messageId, Player $player): bool
    {
        return !MessageReport::where('game_id', '=', $player->game_id)
            ->where('message_id', '=', $messageId)
            ->where('reporter_id', '=', $player->id)
            ->first();
    }

}
