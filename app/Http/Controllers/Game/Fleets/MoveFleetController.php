<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Models\FleetMovement;
use App\Models\Game;
use App\Models\Player;
use App\Services\FleetService;
use App\Services\FormatApiResponseService;
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
        $f = new FormatApiResponseService;
        $fl = new FleetService;
        $fleetId = $request->input(["fleetId"]);
        $destinationId = $request->input(["destinationId"]);
        $player = Player::find(Auth::user()->selected_player);
        $game = Game::find($request->route('game'));
        $fleet = $game->fleets->where('id', $fleetId)->first();
        $from = $game->stars->where('id', $fleet->star_id)->first();
        $destination = $game->stars->where('id', $destinationId)->first();

        // verification
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
        if (!$this->fleetIsStationary($fleet)) {
            return response()
                ->json(['error' => __('game.fleets.errors.fleetAlreadyMoving')], 419);
        }

        // all good, create FleetMovement
        $travelTime = $fl->travelTime($from, $destination);
        FleetMovement::create([
            'game_id' => $game->id,
            'player_id' => $player->id,
            'fleet_id' => $fleet->id,
            'star_id' => $destinationId,
            'until_arrival' => $travelTime
        ]);
        // remove star id from fleet since the fleet is now in transit.
        $fleet->star_id = null;
        $fleet->save();

        $updatedPlayer = Player::find(Auth::user()->selected_player);
        return response()->json([
            'fleets' => $updatedPlayer->fleets->map(function ($fleet) use ($f) {
                return $f->formatFleet($fleet);
            }),
            'fleetMovements' => $updatedPlayer->fleetMovements->map(function ($fleetMovement) use ($f) {
                return $f->formatFleetMovement($fleetMovement);
            }),
            'message' => __('game.fleets.fleetMoving', ['turns' => $travelTime])
        ]);

    }

}
