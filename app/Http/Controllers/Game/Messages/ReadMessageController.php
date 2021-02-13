<?php

namespace App\Http\Controllers\Game\Messages;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Player;
use App\Services\FormatApiResponseService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Traits\Game\UsesMessageVerification;
use Illuminate\Support\Facades\Auth;

class ReadMessageController extends Controller
{

    use UsesMessageVerification;

    /**
     * @function send fleet to destination
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $f = new FormatApiResponseService;
        $player = Player::find(Auth::user()->selected_player);
        $gameId = $request->route('game');
        $messageId = $request->input(['messageId']);

        // verification
        if (!$this->messageBelongsToPlayer($messageId, $player->id, $gameId)) {
            return response()
                ->json(['error' => __('game.messages.errors.messageOwner')], 419);
        }

        // mark message as read
        $message = Message::where('game_id', '=', $gameId)
            ->where('id', '=', $messageId)
            ->where('player_id', $player->id)
            ->first();
        $message->read = true;
        $message->save();

        return response()->json([
            'inbox' => $inbox = $player->inbox->map(function ($message) use ($f) {
                return $f->formatMessage($message);
            })
        ]);

    }


}
