<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Services\ApiService;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FleetsController extends Controller
{

    /**
     * @function api gameData for "research" area
     * @param Request $request
     * @return JsonResponse
     */
    public function gameData (Request $request): JsonResponse
    {
        $a = new ApiService;
        $f = new FormatApiResponseService;
        $player = Player::find(Auth::user()->selected_player);
        $defaultApiData = $a->defaultData($request);
        $returnData = [
            'shipyards' => $player->shipyards->map(function ($shipyard) use ($f) {
                return $f->formatShipyard($shipyard);
            }),
            'fleets' => []
        ];
        return response()->json(array_merge($defaultApiData, $returnData));

    }

}
