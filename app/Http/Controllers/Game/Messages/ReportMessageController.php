<?php

namespace App\Http\Controllers\Game\Messages;

use App\Http\Controllers\Controller;
use App\Http\Traits\Game\UsesMessageVerification;
use App\Models\Player;
use App\Services\ApiService;
use App\Services\FormatApiResponseService;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportMessageController extends Controller
{

    use UsesMessageVerification;

    /**
     * @function delete a message
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $messageId = $request->input(['messageId']);
        $comment = $request->input(['comment']);
        dd($messageId);
    }

}
