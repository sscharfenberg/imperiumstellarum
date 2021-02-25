<?php

namespace App\Http\Controllers\Game\Shipyards;

use App\Http\Controllers\Controller;
use App\Models\Blueprint;
use App\Models\ConstructionContract;
use App\Models\Planet;
use App\Models\Player;
use App\Models\Shipyard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Http\Traits\Game\UsesShipyardsVerification;
use App\Services\ResourceService;
use App\Services\ShipService;
use App\Services\FormatApiResponseService;

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
        $modules = explode("  ", $blueprint->modules);
        $hullType = $blueprint->hull_type;
        $r = new ResourceService;
        $s = new ShipService;
        $f = new FormatApiResponseService;

        // verification
        if (!$this->isBlueprintPlayerOwned($blueprintId, $player)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.blueprint.owner')], 419);
        }
        if (!$this->isShipyardPlayerOwned($shipyardId, $player)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.constructionContract.shipyardOwner')], 419);
        }
        if (!$this->isShipAmountValid($amount)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.constructionContract.amount')], 419);
        }
        if (!$this->isShipyardReady($shipyard)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.constructionContract.shipyardConstructing')], 419);
        }
        if (!$this->canShipyardBuildBlueprint($shipyard, $blueprint)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.constructionContract.shipyard')], 419);
        }
        $resourceCosts = $r->getShipResourceCosts($hullType, $modules);
        if (!$r->playerCanAfford($player, $resourceCosts)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.constructionContract.funds')], 419);
        }
        if (
            isset($resourceCosts['population']) &&
            !$s->shipyardHasSufficientPopulation($shipyard, $resourceCosts['population'])
        ) {
            return response()
                ->json(['error' => __('game.shipyards.errors.constructionContract.population')], 419);
        }

        // all good, calculate ship
        $turns = $r->getShipBuildDuration($hullType, $modules);
        $ship = $s->calculateShipStats($blueprint, $shipyardId);

        // pay for first ship
        $r->subtractResources($player, $resourceCosts);
        if (isset($resourceCosts['population'])) {
            $s->subtractPopulation($shipyard, $resourceCosts['population']);
        }

        $contract = ConstructionContract::create([
            'game_id' => $player->game_id,
            'player_id' => $player->id,
            'blueprint_id' => $blueprintId,
            'shipyard_id' => $shipyardId,
            'hull_type' => $hullType,
            'amount' => $amount,
            'amount_left' => $amount,
            'turns_per_ship' => $turns,
            'turns_left' => $turns,
            'costs_minerals' => $resourceCosts['minerals'],
            'costs_energy' => $resourceCosts['energy'],
            'costs_population' => $resourceCosts['population'] ?? 0,
            'paid_minerals' => $resourceCosts['minerals'],
            'paid_energy' => $resourceCosts['energy'],
            'paid_population' => $resourceCosts['population'] ?? 0,
            'cached_ship' => $ship
        ]);

        return response()->json([
            'constructionContract' => $f->formatConstructionContract($contract),
            'resources' => $r->getResources($player),
            'shipyards' => $player->shipyards->map(function ($shipyard) use ($f) {
                return $f->formatShipyard($shipyard);
            }),
            'message' => __('game.shipyards.constructionContractInstalled', [ 'turns' => $turns ])
        ]);

    }

}
