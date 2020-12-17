<?php

namespace App\Http\Controllers\Game\Shipyards;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Services\ResourceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BlueprintController extends Controller
{

    /**
     * @function check if the hullType exists and the player has a shipyard that can build these ships.
     * @param string $hullType
     * @param Player $player
     * @return bool
     */
    private function isHullTypeValid (string $hullType, Player $player): bool
    {
        $allowedShipyards = [];
        $allShipyards = collect(config('rules.shipyards.hullTypes'));
        foreach ($allShipyards as $key => $shipyard) {
            // if the shipyard contains $hullType in construct array, add to $allowedShipyards
            if (in_array($hullType, $shipyard['construct'])) $allowedShipyards[] = $key;
        }
        return
            in_array($hullType, array_keys(config('rules.ships.hullTypes')))
            && count($player->shipyards->whereIn('type', $allowedShipyards)) > 0;
    }

    /**
     * @function create a new blueprint from xhr request
     * @param Request $request
     * @return JsonResponse
     */
    public function create (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $hullType = $request->input(['hullType']);
        $r = new ResourceService;

        // verification
        if (!$this->isHullTypeValid($hullType, $player)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.hullType')], 419);
        }


        // ensure # of modules is not > than max
        // ensure all modules exist
        // strlen of className
        // ensure player has enough resources to pay resource cost

        return response()->json([
            'blueprint' => ['fuddel'],
            'resources' => $r->getResources($player)
        ]);
    }

}
