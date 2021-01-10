<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Http\Traits\Game\UsesFleetsVerification;
use Exception;
use App\Models\Fleet;
use App\Models\Player;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeleteFleetController extends Controller
{

    use UsesFleetsVerification;

    /**
     * @function change fleet name via xhr request
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function handle (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $fleetId = $request->input(['id']);
        $fleet = Fleet::find($fleetId);
        $f = new FormatApiResponseService;

        // verification
        if (!$this->playerOwnsFleet($player, $fleetId) || !$fleet) {
            return response()
                ->json(['error' => __('game.fleets.errors.owner')], 419);
        }
        if (!$this->isFleetEmpty($fleet)) {
            return response()
                ->json(['error' => __('game.fleets.errors.notEmpty')], 419);
        }

        // all good, delete fleet
        // do delete
        try {
            $fleet->delete();
            Log::info("Empire $player->ticker has deleted fleet $fleet->name");
            // send answer to client
            $updatedPlayer = Player::find(Auth::user()->selected_player);
            return response()->json([
                'fleets' => $updatedPlayer->fleets->map(function ($fleet) use ($f) {
                    return $f->formatFleet($fleet);
                }),
                'message' => __('game.fleets.deleted')
            ]);
        } catch (Exception $e) {
            Log::error('Exception while attempting to delete a fleet:\n'. $e->getMessage());
            return response()->json(['error' => __('game.common.errors.deletion')], 419);
        }
    }

}
