<?php

namespace App\Http\Controllers\Game\Encounters;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use App\Models\MessageReport;
use App\Models\Player;
use App\Models\PlayerRelation;
use App\Models\Raid;
use App\Models\Star;
use App\Services\ApiService;
use App\Services\EncounterService;
use App\Services\FormatApiResponseService;
use App\Services\MessageService;
use App\Services\PlayerRelationService;
use Illuminate\Database\Eloquent\Builder;
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
        $playerId = $player->id;
        $gameId = $request->route('game');
        $allPlayers = Player::where('game_id', $gameId)
            ->with('user')
            ->get();
        $gameRelations = PlayerRelation::where('game_id', $gameId)->get();
        $encounters = $e->getPlayerEncounters($player, $gameId);
        $encounterStarIds = $encounters->map( function ($encounter) {
            return $encounter->star_id;
        });
        $raids = Raid::where('game_id', '=', $gameId)
            ->whereHas('players', function (Builder $query) use ($playerId) {
                $query->where('player_id', '=', $playerId);
            })
            ->with('players')
            ->get();
        $raidStarIds = $raids->map( function ($raid) {
            return $raid->star_id;
        });
        $starIds = $encounterStarIds->concat($raidStarIds)->all();
        $stars = Star::where('game_id', '=', $gameId)
            ->whereIn('id', $starIds)
            ->get();

        // prepare return data
        $returnData = [
            'encounters' => $encounters->map(function ($encounter) use ($f, $player) {
                return $f->formatPlayerEncounter($encounter, $player);
            }),
            'players' => $allPlayers->map(function ($player) use ($f) {
                return $f->formatPlayer($player);
            })->values(),
            'relations' => $p->formatAllPlayerRelations($player, $gameRelations, $allPlayers),
            'stars' => $stars->map( function ($star) use ($f) {
                return $f->formatStar($star);
            }),
            'raids' => $raids->map( function ($raid) use ($f, $player) {
                return $f->formatPlayerRaid($raid, $player);
            })
        ];

        return response()->json(array_merge($a->defaultData($request), $returnData));
    }


}
