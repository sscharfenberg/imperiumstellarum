<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Models\Fleet;
use App\Models\Player;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Traits\Game\UsesFleetsVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateFleetController extends Controller
{

    use UsesFleetsVerification;

    /**
     * @function create a new blueprint from xhr request
     * @param Request $request
     * @return JsonResponse
     */
    public function handle (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $starId = $request->input(['location']);
        $name = $request->input(['name']);
        $f = new FormatApiResponseService;

        // verification
        if (!$this->playerOwnsStar($player, $starId)) {
            return response()
                ->json(['error' => __('game.fleets.errors.starOwner')], 419);
        }
        if (!$this->isFleetNameValid($name)) {
            return response()
                ->json(['error' => __('game.fleets.errors.name')], 419);
        }
        if (!$this->isFleetNameUnique($player, $name)) {
            return response()
                ->json(['error' => __('game.fleets.errors.nameUnique')], 419);
        }

        // create fleet
        $fleet = Fleet::create([
            'game_id' => $player->game->id,
            'player_id' => $player->id,
            'star_id' => $starId,
            'name' => $name,
        ]);
        Log::channel('api')
            ->info("Empire $player->ticker in g".$player->game->number." created a fleet: \n".json_encode($fleet, JSON_PRETTY_PRINT));

        // send answer to client
        $updatedPlayer = Player::find(Auth::user()->selected_player);
        return response()->json([
            'fleets' => $updatedPlayer->fleets->map(function ($fleet) use ($f) {
                return $f->formatFleet($fleet);
            }),
            'message' => __('game.fleets.fleetCreated', ['name' => $name])
        ]);
    }

}
