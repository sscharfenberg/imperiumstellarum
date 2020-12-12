<?php

namespace App\Http\Controllers\Game\Shipyards;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Services\ApiService;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipyardsController extends Controller
{

    /**
     * @function answer request for a random name
     * @param Request $request
     * @throws \Exception
     * @return JsonResponse
     */
    public function randomClassName (Request $request): JsonResponse
    {
        $type = $request->route('class');
        $className = "";
        $length = random_int(2, 4);
        $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        while (strlen($className) < $length) {
            $newLetter = $letters[random_int(0, strlen($letters) - 1)];
            $className = $className.$newLetter;
        }
        switch ($type) {
            case "ark":
                $className = "A-".$className;
                break;
            case "small":
                $className = "DD-".$className;
                break;
            case "medium":
                $className = "CG-".$className;
                break;
            case "large":
                $className = "BB-".$className;
                break;
            case "xlarge":
                $className = "DN-".$className;
                break;
        }

        return response()->json(['name' => $className]);
    }

    /**
     * @function api gameData for "research" area
     * @param Request $request
     * @return JsonResponse
     */
    public function gameData (Request $request): JsonResponse
    {
        $a = new ApiService;
        $defaultApiData = $a->defaultData($request);
        $user = Auth::user();
        $player = $user->players->find($user->selected_player);
        $shipyards = $player->shipyards;

        $f = new FormatApiResponseService;
        $returnData = [
            'shipyards' => $shipyards->map(function ($shipyard) use ($f) {
                return $f->formatShipyard($shipyard);
            }),
        ];
        return response()->json(array_merge($defaultApiData, $returnData));
    }
}