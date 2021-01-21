<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Models\FleetMovement;
use App\Models\Game;
use App\Models\Player;
use App\Services\FleetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Traits\Game\UsesFleetsVerification;
use Illuminate\Support\Facades\Auth;

class MoveFleetController extends Controller
{

    use UsesFleetsVerification;

    /**
     * @function send fleet to destination
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $fl = new FleetService;
        $fleetId = $request->input(["fleetId"]);
        $destinationId = $request->input(["destinationId"]);
        $player = Player::find(Auth::user()->selected_player);
        $game = Game::find($request->route('game'));
        $fleet = $game->fleets->where('id', $fleetId)->first();
        $from = $game->stars->where('id', $fleet->star_id)->first();
        $destination = $game->stars->where('id', $destinationId)->first();

        // verification
        // TODO: not already moving!
        if (!$this->playerOwnsFleet($player, $fleetId)) {
            return response()
                ->json(['error' => __('game.fleets.errors.owner')], 419);
        }
        if (!$destination) {
            return response()
                ->json(['error' => __('game.fleets.errors.moveDestinationInvalid')], 419);
        }
        if (!$this->startNotEqualsEnd($from, $destination)) {
            return response()
                ->json(['error' => __('game.fleets.errors.startEqualsEnd')], 419);
        }

        // all good, create FleetMovement
        $fleetMovement = FleetMovement::create([
            'game_id' => $game->id,
            'player_id' => $player->id,
            'fleet_id' => $fleet->id,
            'star_id' => $destinationId,
            'until_arrival' => $fl->travelTime($from, $destination)
        ]);
        // remove star id from fleet since the fleet is now in transit.
        $fleet->star_id = null;
        $fleet->save();

        dd($from);

    }

}
