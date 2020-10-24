<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EmpireController extends Controller
{
    public function gameData (Request $request)
    {
        $a = new ApiService;
        $defaultApiData = $a->defaultData($request);
        $returnData = [
            'stars' => []
        ];
        return response()->json(array_merge($defaultApiData, $returnData));
    }
}
