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
        $exactPop = $star->planets->sum('population');
        // quick and dirty population brackets.
        $population = "";
        if ($exactPop === 0) $population = 0;
        if ($exactPop > 0 && $exactPop <= 4) $population = "0-4";
        if ($exactPop > 4 && $exactPop <= 8) $population = "4-8";
        if ($exactPop > 8 && $exactPop <= 12) $population = "9-12";
        if ($exactPop > 12 && $exactPop <= 16) $population = "12-16";
        if ($exactPop > 12 && $exactPop <= 16) $population = "12-16";
        if ($exactPop > 16 && $exactPop <= 20) $population = "16-20";
        if ($exactPop > 20 && $exactPop <= 24) $population = "20-24";
        if ($exactPop > 24 && $exactPop <= 28) $population = "24-28";
        if ($exactPop > 28 && $exactPop <= 32) $population = "28-32";
        if ($exactPop > 32 && $exactPop <= 36) $population = "32-36";
        if ($exactPop > 36 && $exactPop <= 40) $population = "36-40";
        if ($exactPop > 40) $population = "40+";

        return response()->json([
            'planets' => $numPlanets,
            'population' => $population
        ]);
    }
}
