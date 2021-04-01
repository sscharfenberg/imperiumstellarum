<?php

namespace App\Http\Controllers\Game\Encounters;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use App\Models\MessageReport;
use App\Models\Player;
use App\Models\PlayerRelation;
use App\Services\ApiService;
use App\Services\EncounterService;
use App\Services\FormatApiResponseService;
use App\Services\MessageService;
use App\Services\PlayerRelationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncountersController extends Controller
{

    /**
     * @function api gameData for "encounters" endpoint
     * @param Request $request
     * @return JsonResponse
     */
    public function gameData(Request $request):JsonResponse
    {
        $a = new ApiService;
        $f = new FormatApiResponseService;
        $p = new PlayerRelationService;
        $e = new EncounterService;
        $player = Player::find(Auth::user()->selected_player);
        $gameId = $request->route('game');
        $allPlayers = Player::where('game_id', $gameId)
            ->where('dead', false)
            ->with('user')
            ->get();
        $gameRelations = PlayerRelation::where('game_id', $gameId)->get();
        $encounters = $e->getPlayerEncounters($player, $gameId);

        $returnData = [
            'encounters' => $encounters->map(function ($encounter) use ($f) {
                return $f->formatEncounter($encounter);
            }),
            'players' => $allPlayers->map(function ($player) use ($f) {
                return $f->formatPlayer($player);
            })->values(),
            'relations' => $p->formatAllPlayerRelations($player->id, $gameRelations, $allPlayers),
        ];

        return response()->json(array_merge($a->defaultData($request), $returnData));
    }


}
