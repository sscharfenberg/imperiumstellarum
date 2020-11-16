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

class ShipyardController extends Controller
{

    /**
     * @function check if player owns the star that the planet belongs to
     * @param Player $player
     * @param Planet $planet
     * @return bool
     */
    private function playerOwnsPlanet(Player $player, Planet $planet)
    {
        $playerStar = $player->stars->find($planet->star->id);
        if ($playerStar) return true;
        return false;
    }

    /**
     * @function verify the planet does not already have a shipyard
     * @param Planet $planet
     * @return bool
     */
    private function shipyardInstallable(Planet $planet)
    {
        return $planet->shipyard === null;
    }

    /**
     * @function verify the indicated type is indeed the direct upgrade without skipping a shipyard type
     * @param Planet $planet
     * @param string $type
     * @return boolean
     */
    private function shipyardUpgradeable(Planet $planet, string $type)
    {
        $shipyard = $planet->shipyard;
        if (!$shipyard) return false;
        if ($type === "medium" && $shipyard->small && !$shipyard->medium && !$shipyard->large && !$shipyard->xlarge) return true;
        if ($type === "large" && $shipyard->small && $shipyard->medium && !$shipyard->large && !$shipyard->xlarge) return true;
        if ($type === "xlarge" && $shipyard->small && $shipyard->medium && $shipyard->large && !$shipyard->xlarge) return true;
        return false;
    }

    /**
     * @function handle build shipyard xhr request
     * @param Request $request
     * @return JsonResponse
     */
    public function install (Request $request)
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
            'game_id' => $planet->game->id,
            'player_id' => $player->id,
            'small' => true,
            'medium' => $type === 'medium' || $type === 'large' || $type === 'xlarge',
            'large' => $type === 'large' || $type === 'xlarge',
            'xlarge' => $type === 'xlarge',
            'until_complete' => $turns,
        ]);

        Log::info("Empire $player->ticker has started building a shipyard: ".json_encode($shipyard, JSON_PRETTY_PRINT));

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
    public function upgrade (Request $request)
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
        if ($type === "medium") $shipyard->medium = true;
        elseif ($type === "large") $shipyard->large = true;
        elseif ($type === "xlarge") $shipyard->xlarge = true;
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
