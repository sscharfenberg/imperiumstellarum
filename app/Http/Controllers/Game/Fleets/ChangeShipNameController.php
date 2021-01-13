<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Ship;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Traits\Game\UsesFleetsVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChangeShipNameController extends Controller
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
        $name = $request->input(['name']);
        $shipId = $request->input(['id']);
        $ship = Ship::find($shipId);
        $oldName = $ship->name;
        $f = new FormatApiResponseService;

        // verification
        if (!$this->playerOwnsShip($player, $shipId) || !$ship) {
            return response()
                ->json(['error' => __('game.fleets.errors.shipOwner')], 419);
        }
        if (!$this->isShipNameValid($name)) {
            return response()
                ->json(['error' => __('game.fleets.errors.shipName')], 419);
        }

        // all good, change fleet
        $ship->name = $name;
        $ship->save();
        Log::info("Empire $player->ticker in g".$player->game->number." renamed a ship from '$oldName' to '$name'.");

        // send answer to client
        $updatedPlayer = Player::find(Auth::user()->selected_player);
        return response()->json([
            'ships' => $updatedPlayer->ships->map(function ($ship) use ($f) {
                return $f->formatShip($ship);
            }),
            'message' => __('game.fleets.shipNameChanged')
        ]);
    }

}
