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
        //$player = $user->players->find($user->selected_player);

        //$f = new FormatApiResponseService;
        $returnData = [];
        return response()->json(array_merge($defaultApiData, $returnData));
    }
}
