<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FormatApiResponseService;

class ResearchController extends Controller
{
    public function gameData (Request $request)
    {
        $a = new ApiService;
        $defaultApiData = $a->defaultData($request);
        $user = Auth::user();
        $player = $user->players->find($user->selected_player);
        $stars = $player->stars;
        $planets = collect();
        foreach($stars as $star) {
            $planets = $planets->concat($star->planets);
        }
        $totalPopulation = $planets->filter(function($planet) {
            return $planet->population > 0;
        })->reduce(function ($carry, $planet) {
            return $carry + $planet->population;
        });

        //$f = new FormatApiResponseService;
        $returnData = [
            'totalPopulation' => round($totalPopulation, 8)
        ];
        return response()->json(array_merge($defaultApiData, $returnData));
    }
}
