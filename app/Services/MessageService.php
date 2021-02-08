<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageSent;
use Ramsey\Uuid\Uuid;

class MessageService {

    /**
     * size of chunks for database operations
     * @var int
     */
    private $chunkSize = 5;

    /**
     * @function create message(s) for inbox
     * @param string $gameId
     * @param string|null $senderId
     * @param array $recipientIds
     * @param string $subject
     * @param string $body
     * @return void
     */
    private function createInboxMessages (string $gameId, ?string $senderId, array $recipientIds, string $subject, string $body)
    {
        $messages = array_map(function($recipientId) use ($gameId, $senderId, $recipientIds, $subject, $body) {
            return [
                'id' => Uuid::uuid4(),
                'game_id' => $gameId,
                'player_id' => $recipientId,
                'sender_id' => $senderId,
                'message_id' => null,
                'subject' => $subject,
                'body' => $body,
                'recipient_ids' => json_encode($recipientIds),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $recipientIds);

        $chunks = array_chunk($messages, $this->chunkSize);
        foreach($chunks as $chunk) {
            Message::insert($chunk);
        }
    }

    /**
     * @function create message for outbox
     * @param string $gameId
     * @param string $senderId
     * @param array $recipientIds
     * @param string $subject
     * @param string $body
     * @return void
     */
    private function createOutboxMessage (string $gameId, string $senderId, array $recipientIds, string $subject, string $body)
    {
        MessageSent::create([
            'game_id' => $gameId,
            'player_id' => $senderId,
            'message_id' => null,
            'subject' => $subject,
            'body' => $body,
            'recipient_ids' => json_encode($recipientIds),
        ]);
    }


    /**
     * @function create message entries for inbox
     * @param string $gameId
     * @param string $senderId
     * @param array $recipientIds
     * @param string $subject
     * @param string $body
     * @return void
     */
    public function createMessages (string $gameId, string $senderId, array $recipientIds, string $subject, string $body)
    {
        $this->createInboxMessages($gameId, $senderId, $recipientIds, $subject, $body);
        $this->createOutboxMessage($gameId, $senderId, $recipientIds, $subject, $body);
    }

    /**
     * @function create message by [system] to player
     * @param string $gameId
     * @param array $recipientIds
     * @param string $subject
     * @param string $body
     * @return void
     */
    public function sendSystemMessage (string $gameId, array $recipientIds, string $subject, string $body)
    {
        $this->createInboxMessages($gameId, null, $recipientIds, $subject, $body);
    }

}
