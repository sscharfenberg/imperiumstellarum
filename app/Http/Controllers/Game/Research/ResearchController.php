<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use App\Http\Traits\Game\UsesEffectiveResearch;

class ResearchController extends Controller
{

    use UsesEffectiveResearch;


    /**
     * @function get players total population
     * @param Player $player
     * @return mixed
     */
    private function getTotalPopulation(Player $player)
    {
        $stars = $player->stars;
        $planets = collect();
        foreach($stars as $star) {
            $planets = $planets->concat($star->planets);
        }
        return $planets->filter(function($planet) {
            return $planet->population > 0;
        })->reduce(function ($carry, $planet) {
            return $carry + $planet->population;
        });
    }


    /**
     * @function answer XHR with effective research
     * @param Request $request
     * @return JsonResponse
     */
    public function projectEffectiveResearch(Request $request)
    {
        $player = Auth::user()->players->find(Auth::user()->selected_player);
        $priority = $request->input(['priority']);
        $totalPopulation = $request->input(['totalPopulation']);
        if ($totalPopulation === 0) $totalPopulation = $this->getTotalPopulation($player);
        $effectiveResearch = $this->calculateEffectiveResearch($priority, $totalPopulation);
        return response()->json([
            'effectiveResearch' => $effectiveResearch
        ]);
    }


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
        $totalPopulation = $this->getTotalPopulation($player);

        //$f = new FormatApiResponseService;
        $returnData = [
            'totalPopulation' => round($totalPopulation, 8)
        ];
        return response()->json(array_merge($defaultApiData, $returnData));
    }
}
