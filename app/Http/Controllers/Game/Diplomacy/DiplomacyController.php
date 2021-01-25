<?php

namespace App\Http\Controllers\Game\Diplomacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Player;
use App\Models\PlayerRelation;

use App\Services\ApiService;
use App\Services\FormatApiResponseService;
use App\Services\PlayerRelationService;

class DiplomacyController extends Controller
{

    /**
     * @function api gameData for "diplomacy" endpoint
     * @param Request $request
     * @return JsonResponse
     */
    public function gameData(Request $request):JsonResponse
    {
        $a = new ApiService;
        $p = new PlayerRelationService;
        $f = new FormatApiResponseService;
        $player = Player::find(Auth::user()->selected_player);
        $gameId = $request->route('game');
        $defaultApiData = $a->defaultData($request);
        $players = Player::where('game_id', $gameId)->get();
        $gameRelations = PlayerRelation::where('game_id', $gameId)->get();

        $returnData = [
            'players' => $players->map(function ($player) use ($f) {
                return $f->formatPlayer($player);
            }),
            'relations' => $p->formatAllPlayerRelations($player->id, $gameRelations, $players)
        ];

        return response()->json(array_merge($defaultApiData, $returnData));
    }

}
