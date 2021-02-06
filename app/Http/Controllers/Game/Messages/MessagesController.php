<?php

namespace App\Http\Controllers\Game\Messages;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerRelation;
use App\Services\ApiService;
use App\Services\FormatApiResponseService;
use App\Services\PlayerRelationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function MongoDB\BSON\toJSON;

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
        $defaultApiData = $a->defaultData($request);
        $player = Player::find(Auth::user()->selected_player);
        $gameId = $request->route('game');
        $allPlayers = Player::where('game_id', $gameId)->with('user')->get();
        $players = $allPlayers->filter(function($p) use ($player) {
            return $p->id !== $player->id;
        });
        $gameRelations = PlayerRelation::where('game_id', $gameId)->get();

        $returnData = [
            'inbox' => [],
            'outbox' => [],
            'players' => $players->map(function ($player) use ($f) {
                return $f->formatPlayer($player);
            })->values(),
            'relations' => $p->formatAllPlayerRelations($player->id, $gameRelations, $allPlayers),
        ];

        return response()->json(array_merge($defaultApiData, $returnData));
    }

}
