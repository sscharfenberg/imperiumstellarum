<?php

namespace App\Http\Controllers\Game\Starchart;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Services\ApiService;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StarchartController extends Controller
{

    /**
     * @function api gameData for "research" area
     * @param Request $request
     * @return JsonResponse
     */
    public function gameData (Request $request)
    {
        $a = new ApiService;
        $defaultApiData = $a->defaultData($request);
        $gameId = Auth::user()->players->find(Auth::user()->selected_player)->game->id;
        $players = Player::where('game_id', $gameId)->get();
        $stars = Game::find($gameId)->stars;

        $f = new FormatApiResponseService;
        $returnData = [
            'stars' => $stars->map(function ($star) use ($f) {
                return $f->formatStar($star);
            }),
            'players' => $players->map(function ($player) use ($f) {
                return $f->formatPlayer($player);
            }),
        ];
        return response()->json(array_merge($defaultApiData, $returnData));
    }

}
