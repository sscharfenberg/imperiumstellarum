<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;

class MessageService {

    /**
     * size of chunks for database operations
     * @var int
     */
    private $chunkSize = 5;

    /**
     * @function get a player's inbox (incoming messages)
     * @param string $playerId
     * @param string $gameId
     * @return Message[]|Collection
     */
    public function getPlayerInbox (string $playerId, string $gameId): Collection
    {
        return Message::where('game_id', $gameId)
            ->whereHas('recipients', function (Builder $query) use ($playerId) {
                $query->where('recipient_id', '=', $playerId)
                    ->where('deleted', '=', false);
            })
            ->with('recipients')
            ->get();
    }

    /**
     * @function get a player's outbox (sent messages)
     * @param string $playerId
     * @param string $gameId
     * @return Message[]|Collection
     */
    public function getPlayerOutbox (string $playerId, string $gameId): Collection
    {
        return Message::where('game_id', $gameId)
            ->where('sender_id', $playerId)
            ->where('sender_deleted', '=', false)
            ->with('recipients')
            ->get();
    }

    /**
     * @function get the users that have a player in a game.
     * @param String $gameId
     * @return Collection
     */
    public function getGameUsers (String $gameId): Collection
    {
        return User::whereHas('players', function (Builder $query) use ($gameId) {
            $query->where('game_id', '=', $gameId)
                ->where('dead', '=', false);
        })->get();
    }

    /**
     * @function create message
     * @param string $gameId
     * @param string|null $senderId
     * @param string|null $repliesToId
     * @param string $subject
     * @param string $body
     * @return Message
     */
    private function createMessage (
        string $gameId,
        ?string $senderId,
        ?string $repliesToId,
        string $subject,
        string $body
    ): Message
    {
        return Message::create([
            'game_id' => $gameId,
            'sender_id' => $senderId,
            'message_id' => $repliesToId,
            'subject' => $subject,
            'body' => $body
        ]);
    }

    /**
     * @function create message recipients
     * @param string $gameId
     * @param string $messageId
     * @param array $recipientIds
     * @return void
     */
    private function createRecipients (string $gameId, string $messageId, array $recipientIds)
    {
        $recipients = array_map(function ($recipientId) use ($gameId, $messageId) {
            return [
                'id' => Uuid::uuid4(),
                'game_id' => $gameId,
                'message_id' => $messageId,
                'recipient_id' => $recipientId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $recipientIds);
        $chunks = array_chunk($recipients, $this->chunkSize);
        foreach ($chunks as $chunk) {
            MessageRecipient::insert($chunk);
        }
    }


    /**
     * @function send message from player
     * @param string $gameId
     * @param string $senderId
     * @param string|null $repliesToId
     * @param array $recipientIds
     * @param string $subject
     * @param string $body
     * @return void
     */
    public function sendPlayerMessage (
        string $gameId,
        string $senderId,
        ?string $repliesToId,
        array $recipientIds,
        string $subject,
        string $body
    )
    {
        $message = $this->createMessage($gameId, $senderId, $repliesToId, $subject, $body);
        $this->createRecipients($gameId, $message->id, $recipientIds);
    }

    /**
     * @function create message by [system] to player
     * @param string $gameId
     * @param array $recipientIds
     * @param string $subject
     * @param string $body
     * @return void
     */
    public function sendSystemMessage (
        string $gameId,
        array $recipientIds,
        string $subject,
        string $body
    )
    {
        $message = $this->createMessage($gameId, null, null, $subject, $body);
        $this->createRecipients($gameId, $message->id, $recipientIds);
    }

}
