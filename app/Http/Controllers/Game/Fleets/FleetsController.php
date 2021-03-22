<?php

namespace App\Http\Controllers\Game\Fleets;

use App\Http\Controllers\Controller;
use App\Http\Traits\Game\UsesTotalPopulation;
use App\Models\Player;
use App\Models\PlayerRelation;
use App\Models\Star;
use App\Services\ApiService;
use App\Services\FleetService;
use App\Services\FormatApiResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FleetsController extends Controller
{

    use UsesTotalPopulation;

    /**
     * @function api gameData for "research" area
     * @param Request $request
     * @return JsonResponse
     */
    public function gameData (Request $request): JsonResponse
    {
        $a = new ApiService;
        $f = new FormatApiResponseService;
        $fl = new FleetService;
        $player = Player::find(Auth::user()->selected_player);
        $gameId = $player->game_id;
        $defaultApiData = $a->defaultData($request);
        $numMaxFleets = $this->getTotalPopulation($player) * config('rules.fleets.num.factor');
        $max = config('rules.fleets.num.max');
        if ($numMaxFleets > $max) $numMaxFleets = $max;
        $playerStars = $player->stars;
        $players = Player::where('game_id', $gameId)
            ->where('dead', false)
            ->get();
        $fleetMovements = $player->fleetMovements;
        $fleetStarIds = $player->fleets->map(function ($fleet) {
            return $fleet->star_id;
        });
        $fleetStars = Star::where('game_id', $gameId)
            ->whereIn('id', $fleetStarIds)
            ->get();
        $allStars = $playerStars
            ->concat($fleetStars)
            ->unique('id')
            ->values(); // without values, the array keys are not correctly numbered, resulting in js confusion.
        $gameRelations = PlayerRelation::where('game_id', $gameId)->get();

        $returnData = [
            'shipyards' => $player->shipyards->map(function ($shipyard) use ($f) {
                return $f->formatShipyard($shipyard);
            }),
            'fleets' => $player->fleets->map(function ($fleet) use ($f) {
                return $f->formatFleet($fleet);
            }),
            'fleetMovements' => $fleetMovements->map(function ($fleetMovement) use ($f) {
                return $f->formatFleetMovement($fleetMovement);
            }),
            'ships' => $player->ships->map(function ($ship) use ($f) {
                return $f->formatShip($ship);
            }),
            'stars' => $allStars->map(function ($star) use ($f) {
                return $f->formatStar($star);
            }),
            'players' => $players->map(function ($player) use ($f) {
                return $f->formatPlayer($player);
            }),
            'maxFleets' => round($numMaxFleets),
            'foreignFleets' => $fl->getForeignFleets($player)->map(function ($fleet) use ($f, $player, $gameRelations) {
                return $f->formatForeignFleet($player, $fleet, $gameRelations);
            })
        ];
        return response()->json(array_merge($defaultApiData, $returnData));

    }

}
