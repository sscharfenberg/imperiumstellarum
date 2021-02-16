<?php

namespace App\Http\Traits\Game;

use App\Models\Game;
use App\Models\Message;
use App\Models\Player;
use Illuminate\Database\Eloquent\Builder;
use App\Services\MessageService;
use Ramsey\Uuid\Uuid;

trait UsesMessageVerification
{


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
     * @param string $playerId
     * @param string $gameId
     * @return bool
     */
    public function messageBelongsToPlayer (string $messageId, string $playerId, string $gameId): bool
    {
        $m = new MessageService;
        $inbox = $m->getPlayerInbox($playerId, $gameId);
        return !!$inbox->containsStrict('id', $messageId);
    }

}
