<?php

namespace App\Http\Controllers\Game\Encounters;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use App\Models\Player;
use App\Models\PlayerRelation;
use App\Models\Star;
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
        $p = new PlayerRelationService;
        $encounterId = $request->route('encounter');
        $player = Player::find(Auth::user()->selected_player);
        $gameId = $request->route('game');
        $encounters = $e->getPlayerEncounters($player, $gameId);
        $encounter = $encounters->where('id', '=', $encounterId)->first();
        $gameRelations = PlayerRelation::where('game_id', $gameId)->get();
        $allPlayers = Player::where('game_id', $gameId)
            ->where('dead', false)
            ->with('user')
            ->get();
        $encounterStarIds = $encounters->map( function ($encounter) {
            return $encounter->star_id;
        });
        $stars = Star::where('game_id', '=', $gameId)
            ->whereIn('id', $encounterStarIds)
            ->get();

        // verification
        if (count($encounters) === 0 || !$encounter) {
            return response()
                ->json(['error' => __('game.encounters.errors.noEncounter')], 419);
        }

        $returnData = [
            'encounters' => $encounters->map(function ($encounter) use ($f, $player) {
                return $f->formatPlayerEncounter($encounter, $player);
            }),
            'encounterDetails' => $f->formatEncounterDetails($encounter, $player),
            'players' => $allPlayers->map(function ($player) use ($f) {
                return $f->formatPlayer($player);
            })->values(),
            'relations' => $p->formatAllPlayerRelations($player, $gameRelations, $allPlayers),
            'stars' => $stars->map( function ($star) use ($f) {
                return $f->formatStar($star);
            })
        ];

        return response()->json(array_merge($a->defaultData($request), $returnData));
    }


}
