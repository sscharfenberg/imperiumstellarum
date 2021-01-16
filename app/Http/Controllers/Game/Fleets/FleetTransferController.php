<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Traits\Game\UsesFleetsVerification;
use Illuminate\Support\Facades\Auth;

class FleetTransferController extends Controller
{

    use UsesFleetsVerification;

    /**
     * @function change fleet name via xhr request
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $sourceId = $request->input(['sourceId']);
        $sourceShipIds = $request->input(['sourceShipIds']);
        $targetId = $request->input(['targetId']);
        $targetShipIds = $request->input(['targetShipIds']);

        // verify source and target are either fleet or shipyard
        // verify source and target belong to the player
        // verify source and target are at the same star location
        // verify all ship ids belong to source or target

        dd($sourceId);

    }
}
