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
use App\Http\Traits\Game\UsesTotalPopulation;
use App\Http\Traits\Game\UsesShipyardsVerification;

class CreateBlueprintController extends Controller
{

    use UsesTotalPopulation, UsesShipyardsVerification;

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
    public function handle (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $hullType = $request->input(['hullType']);
        $modules = $request->input(['modules']);
        $name = $request->input(['name']);
        $r = new ResourceService;
        $f = new FormatApiResponseService;

        // verification
        if (!$this->blueprintMaxNotReached($player)) {
            return response()
                ->json(['error' => __('game.shipyards.errors.blueprint.bpMax')], 419);
        }
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
