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
        return count($planet->shipyards) === 0;
    }

    /**
     * @function verify player has the necessary resources
     * @param Player $player
     * @param string $type
     * @return bool
     */
    private function playerCanAfford (Player $player, string $type)
    {
        $costs = array_filter(
            config('rules.shipyards.hullTypes.'.$type.'.costs'), function($resType) {
            return $resType !== 'turns';
        }, ARRAY_FILTER_USE_KEY);
        $r = new ResourceService();
        if (!$r->playerCanAfford($player, $costs)) return false;
        return true;
    }

    /**
     * @function pay by subtracting resources from the player
     * @param Player $player
     * @param string $type
     */
    private function pay (Player $player, string $type)
    {
        $costs = array_filter(
            config('rules.shipyards.hullTypes.'.$type.'.costs'), function($resType) {
            return $resType !== 'turns';
        }, ARRAY_FILTER_USE_KEY);
        $r = new ResourceService();
        $r->subtractResources($player, $costs);
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

        // verify
        if (!$this->playerOwnsPlanet($player, $planet)) {
            return response()
                ->json(['error' => __('game.empire.errors.shipyard.owner')], 419);
        }
        if (!$this->shipyardInstallable($planet)) {
            return response()
                ->json(['error' => __('game.empire.errors.shipyard.alreadyInstalled')], 419);
        }
        if (!$this->playerCanAfford($player, $type)) {
            return response()
                ->json(['error' => __('game.common.errors.noFunds')], 419);
        }

        // pay for the harvester
        $this->pay($player, $type);

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

        // provide information to client about new shipyard and resources
        $f = new FormatApiResponseService;
        $r = new ResourceService;
        return response()->json([
            'shipyard' => $f->formatShipyard($shipyard),
            'resources' => $r->getResources($player),
            'message' => __('game.empire.shipyardInstalled', [ 'num' => $turns ])
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

        return response()->json([
            'message' => 'not yet implemented'
        ]);
    }

}
