<?php

namespace App\Http\Controllers\Game\Empire;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FormatApiResponseService;

class EmpireController extends Controller
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

        $f = new FormatApiResponseService;
        $returnData = [
            'stars' => $stars->map(function ($star) use ($f) {
                return $f->formatStar($star);
            }),
            'planets' => $planets->map(function ($planet) use ($f) {
                return $f->formatPlanet($planet);
            }),
            'harvesters' => $player->harvesters->map(function ($harvester) use ($f) {
                return $f->formatHarvester($harvester);
            })
        ];
        return response()->json(array_merge($defaultApiData, $returnData));
    }
}
