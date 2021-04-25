<?php

namespace App\Http\Controllers\Game\Starchart;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerRelation;
use App\Services\ApiService;
use App\Services\FleetService;
use App\Services\FormatApiResponseService;
use App\Services\PlayerRelationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StarchartController extends Controller
{

    /**
     * @function api gameData for "research" area
     * @param Request $request
     * @return JsonResponse
     */
    public function gameData (Request $request): JsonResponse
    {
        $a = new ApiService;
        $p = new PlayerRelationService;
        $f = new FormatApiResponseService;
        $fl = new FleetService;
        $defaultApiData = $a->defaultData($request);
        $gameId = $request->route('game');
        $game = Game::find($gameId);
        $players = Player::where('game_id', $gameId)
            ->where('dead', false)
            ->get();
        $stars = Game::find($gameId)->stars;
        $player = Player::find(Auth::user()->selected_player);
        $playerStars = $player->stars;
        $fleetMovements = $player->fleetMovements;
        $gameRelations = PlayerRelation::where('game_id', $gameId)->get();

        $returnData = [
            'stars' => $stars->map(function ($star) use ($f) {
                return $f->formatStar($star);
            }),
            'players' => $players->map(function ($player) use ($f) {
                return $f->formatPlayer($player);
            }),
            'relations' => $p->formatAllPlayerRelations($player, $gameRelations, $players),
            'dimensions' => $game->dimensions,
            'playerStars' => $playerStars->map(function ($star) use ($f) {
                return $f->formatStar($star);
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
            'shipyards' => $player->shipyards->map(function ($shipyard) use ($f) {
                return $f->formatShipyard($shipyard);
            }),
            'foreignFleets' => $fl->getForeignFleets($player)->map(function ($fleet) use ($f, $player, $gameRelations) {
                return $f->formatForeignFleet($player, $fleet, $gameRelations);
            })
        ];
        return response()->json(array_merge($defaultApiData, $returnData));
    }

}
