<?php

namespace App\Http\Controllers\Game\Empire;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $returnData = [
            'stars' => $stars->map(function ($star) {
                return [
                    'id' => $star->id,
                    'x' => $star->coord_x,
                    'y' => $star->coord_y,
                    'spectral' => $star->spectral,
                    'name' => $star->name
                ];
            }),
            'planets' => $planets->map(function ($planet) {
                return [
                    'id' => $planet->id,
                    'starId' => $planet->star_id,
                    'orbitalIndex' => $planet->orbital_index,
                    'type' => $planet->type,
                    'population' => $planet->population,
                    'resources' => $planet->resources
                ];
            })
        ];
        return response()->json(array_merge($defaultApiData, $returnData));
    }
}
