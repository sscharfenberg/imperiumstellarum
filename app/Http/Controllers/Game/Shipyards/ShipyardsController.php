<?php

namespace App\Http\Controllers\Game\Shipyards;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Services\ApiService;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipyardsController extends Controller
{
    /**
     * @function api gameData for "research" area
     * @param Request $request
     * @return JsonResponse
     */
    public function gameData (Request $request): JsonResponse
    {
        $a = new ApiService;
        $defaultApiData = $a->defaultData($request);
        $user = Auth::user();
        $player = $user->players->find($user->selected_player);
        $shipyards = $player->shipyards;

        $f = new FormatApiResponseService;
        $returnData = [
            'shipyards' => $shipyards->map(function ($shipyard) use ($f) {
                return $f->formatShipyard($shipyard);
            }),
        ];
        return response()->json(array_merge($defaultApiData, $returnData));
    }
}
