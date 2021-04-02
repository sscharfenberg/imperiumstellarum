<?php

namespace App\Http\Controllers\Game\Encounters;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use App\Models\Player;
use App\Models\PlayerRelation;
use App\Services\ApiService;
use App\Services\EncounterService;
use App\Services\FormatApiResponseService;
use App\Services\PlayerRelationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncounterDetailsController extends Controller
{

    /**
     * @function api gameData for "encounters" endpoint
     * @param Request $request
     * @return JsonResponse
     */
    public function getDetails(Request $request):JsonResponse
    {
        $a = new ApiService;
        $f = new FormatApiResponseService;
        $e = new EncounterService;
        //$p = new PlayerRelationService;
        $encounterId = $request->route('encounter');
        $player = Player::find(Auth::user()->selected_player);
        $gameId = $request->route('game');
        $encounters = $e->getPlayerEncounters($player, $gameId);
        $encounter = $encounters->where('id', '=', $encounterId)->first();
        $allPlayers = Player::where('game_id', $gameId)
            ->where('dead', false)
            ->with('user')
            ->get();

        // verification
        if (count($encounters) === 0 || !$encounter) {
            return response()
                ->json(['error' => __('game.encounters.errors.noEncounter')], 419);
        }

        $returnData = [
            'encounters' => $encounters->map(function ($encounter) use ($f) {
                return $f->formatEncounter($encounter);
            }),
            'encounterDetails' => $f->formatEncounterDetails($encounter),
            'players' => $allPlayers->map(function ($player) use ($f) {
                return $f->formatPlayer($player);
            })->values(),
        ];

        return response()->json(array_merge($a->defaultData($request), $returnData));
    }


}
