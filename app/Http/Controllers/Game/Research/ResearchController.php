<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use App\Http\Traits\Game\UsesTotalPopulation;

class ResearchController extends Controller
{

    use UsesTotalPopulation;

    /**
     * @function api gameData for "research" area
     * @param Request $request
     * @return JsonResponse
     */
    public function gameData (Request $request)
    {
        $a = new ApiService;
        $defaultApiData = $a->defaultData($request);
        $player = Auth::user()->players->find(Auth::user()->selected_player);

        $f = new FormatApiResponseService;
        $returnData = [
            'totalPopulation' => round($this->getTotalPopulation($player), 8),
            'techLevels' => $player->techLevels->map(function ($techLevel) use ($f) {
                return $f->formatTechLevel($techLevel);
            }),
            'researchJobs' => $player->researches->map(function ($research) use ($f) {
                return $f->formatResearchJob($research);
            })
        ];
        return response()->json(array_merge($defaultApiData, $returnData));
    }
}
