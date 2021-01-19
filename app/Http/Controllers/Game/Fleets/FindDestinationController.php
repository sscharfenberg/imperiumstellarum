<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Models\Star;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Traits\Game\UsesFleetsVerification;
use App\Services\FleetService;

class FindDestinationController extends Controller
{

    use UsesFleetsVerification;

    /**
     * @function find a destination system by coords
     * @param Request $request
     * @return JsonResponse
     */
    public function byCoords (Request $request): JsonResponse
    {
        $x = (int)$request->input(["x"]);
        $y = (int)$request->input(["y"]);
        $fromId = $request->input(["fromId"]);
        $game = Game::find($request->route('game'));
        $from = Star::find($fromId);
        $fl = new FleetService;
        $f = new FormatApiResponseService;

        // verification
        if (strlen($request->input(["x"])) === 0 || strlen($request->input(["y"])) === 0 || strlen($request->input(["fromId"])) === 0) {
            return response()
                ->json(['error' => __('game.fleets.errors.findDestinationReqMissing')], 419);
        }
        if (!$this->coordsValid($game, $x, $y)) {
            return response()
                ->json(['error' => __('game.fleets.errors.coordsInvalid')], 419);
        }
        if (!$from) {
            return response()
                ->json(['error' => __('game.fleets.errors.moveSourceInvalid')], 419);
        }
        // get the actual star from db
        $destination = Star::where('coord_x', $x)->where('coord_y', $y)->get()->first();
        if (!$destination) {
            return response()
                ->json(['error' => __('game.fleets.errors.coordsStarNotFound')], 419);
        }
        //if (!$this->startNotEqualsEnd($from, $destination)) {
        //    return response()
        //        ->json(['error' => __('game.fleets.errors.startEqualsEnd')], 419);
        //}

        $owner = Player::find($destination->player_id);
        return response()->json([
            'destination' => $f->formatDestinationStar($destination, $fl->travelTime($from, $destination)),
            'owner' => $owner ? $f->formatPlayer($owner) : []
        ]);
    }

}
