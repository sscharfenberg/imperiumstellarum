<?php

namespace App\Http\Controllers\Game\Messages;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerRelation;
use App\Services\ApiService;
use App\Services\FormatApiResponseService;
use App\Services\MessageService;
use App\Services\PlayerRelationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{

    /**
     * @function api gameData for "diplomacy" endpoint
     * @param Request $request
     * @return JsonResponse
     */
    public function gameData(Request $request):JsonResponse
    {
        $a = new ApiService;
        $f = new FormatApiResponseService;
        $p = new PlayerRelationService;
        $m = new MessageService;
        $defaultApiData = $a->defaultData($request);
        $player = Player::find(Auth::user()->selected_player);
        $gameId = $request->route('game');
        $allPlayers = Player::where('game_id', $gameId)
            ->where('dead', false)
            ->with('user')
            ->get();
        $gameRelations = PlayerRelation::where('game_id', $gameId)->get();
        $outbox = $m->getPlayerOutbox($player->id, $gameId);
        $inbox = $m->getPlayerInbox($player->id, $gameId);

        $returnData = [
            'inbox' => $inbox->map(function ($message) use ($f, $player) {
                return $f->formatInboxMessage($message, $player->id);
            }),
            'outbox' => $outbox->map(function ($message) use ($f) {
                return $f->formatOutboxMessage($message);
            }),
            'players' => $allPlayers->map(function ($player) use ($f) {
                return $f->formatPlayer($player);
            })->values(),
            'relations' => $p->formatAllPlayerRelations($player->id, $gameRelations, $allPlayers),
        ];

        return response()->json(array_merge($defaultApiData, $returnData));
    }

}
