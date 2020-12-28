<?php

namespace App\Http\Controllers\Game\Shipyards;

use App\Http\Controllers\Controller;
use App\Models\Blueprint;
use App\Models\Player;
use App\Models\Shipyard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use App\Http\Traits\Game\UsesShipyardsVerification;
use Illuminate\Support\Facades\Auth;

class CreateConstructionContractController extends Controller
{

    use UsesShipyardsVerification;

    /**
     * @function create a new blueprint from xhr request
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function handle (Request $request): JsonResponse
    {
        $blueprintId = $request->input(['blueprint']);
        $blueprint = Blueprint::find($blueprintId);
        $shipyardId = $request->input(['shipyard']);
        $shipyard = Shipyard::find($shipyardId);
        $amount = $request->input(['amount']);
        $player = Player::find(Auth::user()->selected_player);

        var_dump($blueprint);
        var_dump($amount);
        dd($shipyard);

        // new verifications:
        // ensure blueprint exists
        // ensure shipyard exists
        // ensure amount is within bounds
        // ensure shipyard does not already have a contract
        // ensure blueprint can be built in the shipyard
        // ensure player can afford initial resource costs for first ship



    }

}
