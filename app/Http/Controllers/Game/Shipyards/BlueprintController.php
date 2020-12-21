<?php

namespace App\Http\Controllers\Game\Shipyards;

use App\Http\Controllers\Controller;
use App\Models\Blueprint;
use App\Models\BlueprintTechLevel;
use App\Models\Player;
use App\Services\FormatApiResponseService;
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
     * @return bool
     */
    private function isClassNameValid (string $name): bool
    {
        return is_string($name)
            && strlen($name) >= config('rules.ships.className.min')
            && strlen($name) <= config('rules.ships.className.max');
    }

    /**
     * @function check if the class name is already used
     * @param Player $player
     * @param string $name
     * @return bool
     */
    private function isClassNameUnique (Player $player, string $name): bool
    {
        return count($player->blueprints->where('name', $name)) === 0;
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
        $f = new FormatApiResponseService;

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
        if (!$this->isClassNameUnique($player, $name)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.blueprint.classNameUsed')], 419);
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
            'modules' => implode("  ", $modules),
            'name' => $name
        ]);
        // create tech levels for the blueprint
        $playerTechLevels = $player->techLevels;
        foreach(array_keys(config('rules.tech.areas')) as $type) {
            BlueprintTechLevel::create([
                'game_id' => $player->game_id,
                'blueprint_id' => $blueprint->id,
                'type' => $type,
                'level' => $playerTechLevels->where('type', $type)->first()->level
            ]);
        }

        return response()->json([
            'blueprint' => $f->formatBlueprint($blueprint),
            'resources' => $r->getResources($player),
            'message' => __('game.shipyards.blueprintSaved')
        ]);
    }

}
