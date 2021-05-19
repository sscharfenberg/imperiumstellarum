<?php

namespace App\Http\Controllers\Game\Messages;

use App\Http\Controllers\Controller;
use App\Models\MessageRecipient;
use App\Models\Player;
use App\Services\ApiService;
use App\Services\FormatApiResponseService;

use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Traits\Game\UsesMessageVerification;
use Illuminate\Support\Facades\Auth;

class ReadMessageController extends Controller
{

    use UsesMessageVerification;

    /**
     * @function mark message as read/unread
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $f = new FormatApiResponseService;
        $m = new MessageService;
        $a = new ApiService;
        $player = Player::find(Auth::user()->selected_player);
        $gameId = $request->route('game');
        $messageId = $request->input(['messageId']);
        $read = $request->input(['read']);

        // verification
        if (!$this->playerOwnsInboxMessage($messageId, $player)) {
            return response()
                ->json(['error' => __('game.messages.errors.messageOwner')], 419);
        }
        if (!is_bool($read)) {
            return response()
                ->json(['error' => __('game.messages.errors.bool')], 419);
        }

        // mark message as read
        $messageRecipient = MessageRecipient::where('game_id', '=', $gameId)
            ->where('message_id', '=', $messageId)
            ->where('recipient_id', '=', $player->id)
            ->first();
        $messageRecipient->read = $read;
        $messageRecipient->save();

        // return fresh json to client
        $inbox = $m->getPlayerInbox($player->id, $gameId);
        return response()->json([
            'inbox' => $inbox->map(function ($message) use ($f, $player) {
                return $f->formatInboxMessage($message, $player->id);
            }),
            'unreadMessages' => $a->unreadMessages($player->id, $gameId)
        ]);

    }


}
