<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Http\Traits\Game\UsesEffectiveResearch;
use App\Http\Traits\Game\UsesTotalPopulation;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EffectiveResearchController extends Controller
{

    use UsesEffectiveResearch, UsesTotalPopulation;

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


}
