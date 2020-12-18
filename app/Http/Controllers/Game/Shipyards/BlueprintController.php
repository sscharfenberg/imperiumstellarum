<?php

namespace App\Http\Controllers\Game\Shipyards;

use App\Http\Controllers\Controller;
use App\Models\Blueprint;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\ResourceService;

class BlueprintController extends Controller
{

    /**
     * @function check if the hullType exists and the player has a shipyard that can build these ships.
     * @param string $hullType
     * @param Player $player
     * @return bool
     */
    private function isHullTypeValid (string $hullType, Player $player): bool
    {
        $allowedShipyards = [];
        $allShipyards = collect(config('rules.shipyards.hullTypes'));
        foreach ($allShipyards as $key => $shipyard) {
            // if the shipyard contains $hullType in construct array, add to $allowedShipyards
            if (in_array($hullType, $shipyard['construct'])) $allowedShipyards[] = $key;
        }
        return
            in_array($hullType, array_keys(config('rules.ships.hullTypes')))
            && count($player->shipyards->whereIn('type', $allowedShipyards)) > 0;
    }

    /**
     * @function ensure module max is not exceeded
     * @param string $hullType
     * @param int $numModules
     * @return bool
     */
    private function moduleMaxValid (string $hullType, int $numModules): bool
    {
        return $numModules <= config('rules.ships.hullTypes.'.$hullType.'.slots');
    }

    /**
     * @function ensure all modules are valid for this hullType
     * @param string $hullType
     * @param array $modules
     * @return bool
     */
    private function areModulesValid (string $hullType, array $modules): bool
    {
        $validModules = array_map(function($m) {
            return $m['techType'];
        }, array_filter(config('rules.modules'), function($module) use ($hullType) {
            return $module['hullType'] === $hullType;
        }));
        return count(array_diff($modules, $validModules)) === 0;
    }

    /**
     * @function validate input
     * @param string $name
     * @returns bool
     */
    private function isClassNameValid (string $name): bool
    {
        // TODO make sure the className is not already used by the player
        return is_string($name)
            && strlen($name) >= config('rules.ships.className.min')
            && strlen($name) <= config('rules.ships.className.max');
    }

    /**
     * @function verify player has the necessary research resources to finalize the blueprint
     * @param Player $player
     * @param int $researchCosts
     * @return bool
     */
    private function playerCanAfford (Player $player, int $researchCosts): bool
    {
        $r = new ResourceService();
        if (!$r->playerCanAfford($player, ['research' => $researchCosts])) return false;
        return true;
    }

    /**
     * @function pay the blueprint by subtracting resources from the player
     * @param Player $player
     * @param int $researchCosts
     * @return void
     */
    private function payBlueprint (Player $player, int $researchCosts)
    {
        $r = new ResourceService();
        $r->subtractResources($player, ['research' => $researchCosts]);
    }

    /**
     * @function that creates the module string
     * @param Player $player
     * @param array $modules
     * @return string
     */
    private function addTLsToBlueprint (Player $player, array $modules): string
    {
        $tls = $player->techLevels;
        $techAreas = array_keys(config('rules.tech.areas'));
        $m = array_map(function($mod) use ($tls, $techAreas) {
            if (in_array($mod, $techAreas)) {
                return $mod.'-'.$tls->where('type', 'laser')->first()->level;
            }
            return $mod;
        }, $modules);
        return implode("  ", $m);
    }

    /**
     * @function create a new blueprint from xhr request
     * @param Request $request
     * @return JsonResponse

     */
    public function create (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $hullType = $request->input(['hullType']);
        $modules = $request->input(['modules']);
        $name = $request->input(['name']);
        $r = new ResourceService;

        // verification
        if (!$this->isHullTypeValid($hullType, $player)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.blueprint.hullType')], 419);
        }
        if (!$this->moduleMaxValid($hullType, count($modules))) {
            return response()
                ->json(['error' => __('game.shipyards.errors.blueprint.moduleMax')], 419);
        }
        if (!$this->areModulesValid($hullType, $modules)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.blueprint.modulesInvalid')], 419);
        }
        if (!$this->isClassNameValid($name)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.blueprint.className')], 419);
        }
        $shipResourceCosts = $r->getShipResourceCosts($hullType, $modules);
        $researchCosts = (int)round(($shipResourceCosts['energy'] + $shipResourceCosts['minerals']) * 0.1);
        if (!$this->playerCanAfford($player, $researchCosts)) {
            return response()
                ->json(['error' => __('game.common.errors.noFunds')], 419);
        }

        // pay for finalization of the blueprint
        $this->payBlueprint($player, $researchCosts);

        // create blueprint
        $blueprint = Blueprint::create([
            'player_id' => $player->id,
            'game_id' => $player->game_id,
            'hull_type' => $hullType,
            'modules' => $this->addTLsToBlueprint($player, $modules),
            'name' => $name
        ]);

        return response()->json([
            'blueprint' => ['fuddel'],
            'resources' => $r->getResources($player)
        ]);
    }

}
