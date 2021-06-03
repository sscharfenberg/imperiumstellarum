<?php

namespace App\Http\Controllers\Game\Empire;

use App\Http\Controllers\Controller;
use App\Models\Planet;
use App\Models\Player;
use App\Models\Shipyard;
use App\Services\FormatApiResponseService;
use App\Services\ResourceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\Game\UsesEmpireVerification;

class ShipyardController extends Controller
{

    use UsesEmpireVerification;

    /**
     * @function handle build shipyard xhr request
     * @param Request $request
     * @return JsonResponse
     */
    public function install (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $input = $request->input();
        $planet = Planet::find($input['planetId']);
        $type = $input['type'];
        $r = new ResourceService;
        $f = new FormatApiResponseService;

        // calculate costs
        $costs = array_filter(
            config('rules.shipyards.hullTypes.'.$type.'.costs'), function($resType) {
            return $resType !== 'turns';
        }, ARRAY_FILTER_USE_KEY);

        // verify
        if (!$this->playerOwnsPlanet($player, $planet)) {
            return response()
                ->json(['error' => __('game.empire.errors.shipyard.owner')], 419);
        }
        if (!$this->shipyardInstallable($planet)) {
            return response()
                ->json(['error' => __('game.empire.errors.shipyard.alreadyInstalled')], 419);
        }
        if (!$this->verifyPlanetHasPopulation($planet)) {
            return response()
                ->json(['error' => __('game.empire.errors.shipyard.population')], 419);
        }
        if (!$r->playerCanAfford($player, $costs)) {
            return response()
                ->json(['error' => __('game.common.errors.noFunds')], 419);
        }

        // pay for the shipyard installation
        $r->subtractResources($player, $costs);

        // create the shipdyard
        $turns = config('rules.shipyards.hullTypes.'.$type.'.costs.turns');
        $shipyard = Shipyard::create([
            'planet_id' => $planet->id,
            'star_id' => $planet->star->id,
            'game_id' => $planet->game->id,
            'player_id' => $player->id,
            'type' => $type,
            'until_complete' => $turns,
            'notified' => false
        ]);

        Log::channel('api')
            ->info("Empire $player->ticker has started building a shipyard: ".json_encode($shipyard, JSON_PRETTY_PRINT));

        // provide information to client about new shipyard and resources
        return response()->json([
            'shipyard' => $f->formatShipyard($shipyard),
            'resources' => $r->getResources($player),
            'message' => __('game.empire.shipyardInstalling', [ 'num' => $turns ])
        ]);
    }


    /**
     * @function handle upgrade shipyard xhr request
     * @param Request $request
     * @return JsonResponse
     */
    public function upgrade (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $input = $request->input();
        $planet = Planet::find($input['planetId']);
        $type = $input['type'];
        $r = new ResourceService;
        $f = new FormatApiResponseService;

        // calculate costs
        $costs = array_filter(
            config('rules.shipyards.hullTypes.'.$type.'.upgradeCosts'), function($resType) {
            return $resType !== 'turns';
        }, ARRAY_FILTER_USE_KEY);

        // verify
        if (!$this->playerOwnsPlanet($player, $planet)) {
            return response()
                ->json(['error' => __('game.empire.errors.shipyard.owner')], 419);
        }
        if (!$this->shipyardUpgradeable($planet, $type)) {
            return response()
                ->json(['error' => __('game.empire.errors.shipyard.noUpgrade')], 419);
        }
        if (!$r->playerCanAfford($player, $costs)) {
            return response()
                ->json(['error' => __('game.common.errors.noFunds')], 419);
        }

        // pay for the shipyard upgrade
        $r->subtractResources($player, $costs);

        // upgrade the shipyard
        $shipyard = $planet->shipyard;
        $shipyard->type = $type;
        $shipyard->notified = false;
        $turns = config('rules.shipyards.hullTypes.'.$type.'.upgradeCosts.turns');
        $shipyard->until_complete = $turns;
        $shipyard->save();

        // provide information to client about new shipyard and resources
        return response()->json([
            'shipyard' => $f->formatShipyard($shipyard),
            'resources' => $r->getResources($player),
            'message' => __('game.empire.shipyardUpgrading', [ 'num' => $turns ])
        ]);
    }

}
