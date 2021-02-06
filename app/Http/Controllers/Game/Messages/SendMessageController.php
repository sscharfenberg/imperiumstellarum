<?php

namespace App\Http\Controllers\Game\Messages;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\Game\UsesMessageVerification;

class SendMessageController extends Controller
{

    use UsesMessageVerification;

    /**
     * @function send fleet to destination
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $gameId = $request->route('game');
        $recipientId = $request->input(["recipientId"]);
        $subject = $request->input(["subject"]);
        $body = $request->input(["body"]);
        $repliesTo = $request->input(["repliesTo"]);

        // verify player and recipient are both members of the game
        // verify subject constraints
        // verify body constraints

        dd($repliesTo);

    }

}
