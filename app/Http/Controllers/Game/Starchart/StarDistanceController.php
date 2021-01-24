<?php

namespace App\Http\Controllers\Game\Starchart;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Star;
use App\Services\FleetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StarDistanceController extends Controller
{

    /**
     * @function calculate fleet travelTimes and send to client
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $fl = new FleetService;
        $destinationId = $request->input(["destinationId"]);
        $fromIds = $request->input(["fromIds"]);
        $game = Game::find($request->route('game'));
        $destination = $game->stars->where('id', $destinationId)->first();
        $fromIds = collect($fromIds)->unique()->values();
        $answer = [];

        // no verification since distance / travelTimes is sort of public information.

        foreach($fromIds as $id) {
            $star = Star::find($id);
            $answer[] = [
                'fromId' => $id,
                'destinationId' => $destinationId,
                'travelTime' => $fl->travelTime($star, $destination)
            ];
        }

        return response()->json($answer);

    }

}
