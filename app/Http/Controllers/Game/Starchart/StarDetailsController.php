<?php

namespace App\Http\Controllers\Game\Starchart;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StarDetailsController extends Controller
{
    /**
     * @function handle star details request
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request)
    {
        $game = Game::find($gameId = $request->route("game"));
        $star = $game->stars->find($request->route("star"));
        $numPlanets = count($star->planets);
        $population = $star->planets->sum('population');

        return response()->json([
            'planets' => $numPlanets,
            'population' => floor($population)
        ]);
    }
}
