<?php

namespace App\Http\Controllers\Game\Messages;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\Player;
use App\Services\ApiService;
use App\Services\FormatApiResponseService;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Traits\Game\UsesMessageVerification;
use Illuminate\Support\Facades\Auth;

class DeleteMessageController extends Controller
{

    use UsesMessageVerification;

    /**
     * @function set softdelete flag on inbox message (message_recipients.deleted)
     * @param array $messageIds
     * @param Player $player
     */
    private function deleteInboxMessages (array $messageIds, Player $player)
    {
        $recipients = MessageRecipient::where('game_id', '=', $player->game_id)
            ->whereIn('message_id', $messageIds)
            ->where('recipient_id', '=', $player->id)
            ->get();
        foreach($recipients as $recipient) {
            $recipient->deleted = true;
            $recipient->save();
        }
    }

    /**
     * @function set softdelete flog on outbox message (messages.sender_deleted)
     * @param array $messageIds
     * @param Player $player
     */
    private function deleteOutboxMessages (array $messageIds, Player $player)
    {
        $messages = Message::where('game_id', '=', $player->game_id)
            ->where('sender_id', '=', $player->id)
            ->whereIn('id', $messageIds)
            ->get();
        foreach($messages as $message) {
            $message->sender_deleted = true;
            $message->save();
        }
    }

    /**
     * @function delete a message
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $m = new MessageService;
        $f = new FormatApiResponseService;
        $a = new ApiService;
        $player = Player::find(Auth::user()->selected_player);
        $messageIds = $request->input(['messageIds']);
        $mailbox = $request->input(['mailbox']);

        // verification
        if ($mailbox === 'out' && !$this->messagesBelongToSender($messageIds, $player)) {
            return response()
                ->json(['error' => __('game.messages.errors.messageOwner')], 419);
        }

        if (
            ($mailbox === 'in' || $mailbox === 'sys')
            && !$this->playerOwnsInboxMessages($messageIds, $player)
        ) {
            return response()
                ->json(['error' => __('game.messages.errors.messageOwner')], 419);
        }

        // handle deletion
        if ($mailbox === 'in' || $mailbox === 'sys') {
            $this->deleteInboxMessages($messageIds, $player);
        }
        else if ($mailbox === 'out') {
            $this->deleteOutboxMessages($messageIds, $player);
        }

        // get new mailboxes
        $outbox = $m->getPlayerOutbox($player->id, $player->game_id);
        $inbox = $m->getPlayerInbox($player->id, $player->game_id);

        // return response to client
        return response()->json([
            'inbox' => $inbox->map(function ($message) use ($f, $player) {
                return $f->formatInboxMessage($message, $player->id);
            }),
            'outbox' => $outbox->map(function ($message) use ($f) {
                return $f->formatOutboxMessage($message);
            }),
            'unreadMessages' => $a->unreadMessages($player->id, $player->game_id),
            'message' => trans_choice('game.messages.messageDeleted', count($messageIds), [
                'num' => count($messageIds)
            ])
        ]);
    }

}
