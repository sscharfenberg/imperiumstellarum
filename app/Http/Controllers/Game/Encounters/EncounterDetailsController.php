<?php

namespace App\Http\Controllers\Game\Encounters;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use App\Models\Player;
use App\Models\PlayerRelation;
use App\Services\ApiService;
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
        //$p = new PlayerRelationService;
        //$player = Player::find(Auth::user()->selected_player);
        $gameId = $request->route('game');
        $encounters = Encounter::where('game_id', '=', $gameId)
            //->whereNotNull('processed_at')
            ->get();

        // TODO: make sure the player is involved in the encounter

        $returnData = [
            'encounters' => $encounters->map(function ($encounter) use ($f) {
                return $f->formatEncounter($encounter);
            }),
            'encounter' => [],
        ];

        return response()->json(array_merge($a->defaultData($request), $returnData));
    }


}
