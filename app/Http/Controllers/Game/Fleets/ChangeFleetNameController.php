<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Http\Traits\Game\UsesFleetsVerification;
use App\Models\Fleet;
use App\Models\Player;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChangeFleetNameController extends Controller
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
        $fleetId = $request->input(['id']);
        $fleet = Fleet::find($fleetId);
        $oldName = $fleet->name;
        $f = new FormatApiResponseService;

        // verification
        if (!$this->playerOwnsFleet($player, $fleetId) || !$fleet) {
            return response()
                ->json(['error' => __('game.fleets.errors.owner')], 419);
        }
        if (!$this->isFleetNameValid($name)) {
            return response()
                ->json(['error' => __('game.fleets.errors.name')], 419);
        }
        if (!$this->isFleetNameUnique($player, $name)) {
            return response()
                ->json(['error' => __('game.fleets.errors.nameUnique')], 419);
        }

        // all good, change fleet
        $fleet->name = $name;
        $fleet->save();
        Log::info("Empire $player->ticker in g".$player->game->number." renamed a fleet from '$oldName' to '$name'.");

        // send answer to client
        $updatedPlayer = Player::find(Auth::user()->selected_player);
        return response()->json([
            'fleets' => $updatedPlayer->fleets->map(function ($fleet) use ($f) {
                return $f->formatFleet($fleet);
            }),
            'message' => __('game.fleets.fleetNameChanged')
        ]);
    }

}
