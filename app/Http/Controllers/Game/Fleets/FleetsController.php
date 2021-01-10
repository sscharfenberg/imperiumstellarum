<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Http\Traits\Game\UsesTotalPopulation;
use App\Models\Player;
use App\Services\ApiService;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FleetsController extends Controller
{

    use UsesTotalPopulation;

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
        $numMaxFleets = $this->getTotalPopulation($player) * config('rules.fleets.num.factor');
        $max = config('rules.fleets.num.max');
        if ($numMaxFleets > $max) $numMaxFleets = $max;
        $playerStars = $player->stars;

        $returnData = [
            'shipyards' => $player->shipyards->map(function ($shipyard) use ($f) {
                return $f->formatShipyard($shipyard);
            }),
            'fleets' => $player->fleets->map(function ($fleet) use ($f) {
                return $f->formatFleet($fleet);
            }),
            'ships' => $player->ships->map(function ($ship) use ($f) {
                return $f->formatShip($ship);
            }),
            'stars' => $playerStars->map(function ($star) use ($f) {
                return $f->formatStar($star);
            }),
            'maxFleets' => round($numMaxFleets),
        ];
        return response()->json(array_merge($defaultApiData, $returnData));

    }

}
