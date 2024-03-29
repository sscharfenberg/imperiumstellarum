<?php

namespace App\Http\Controllers\Game\Empire;

use App\Http\Controllers\Controller;
use App\Models\Harvester;
use App\Models\Planet;
use App\Models\Player;
use App\Services\FormatApiResponseService;
use App\Services\ResourceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\Game\UsesEmpireVerification;

class HarvesterController extends Controller
{

    use UsesEmpireVerification;

    /**
     * @function pay by subtracting resources from the player
     * @param Player $player
     * @param string $type
     */
    private function pay (Player $player, string $type)
    {
        $costs = array_filter(
            config('rules.harvesters.'.$type.'.costs'), function($resType) {
            return $resType !== 'turns';
        }, ARRAY_FILTER_USE_KEY);
        $r = new ResourceService();
        $r->subtractResources($player, $costs);
    }

    /**
     * @function handle install harvester xhr request
     * @param Request $request
     * @return JsonResponse
     */
    public function install (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $input = $request->input();
        $planet = Planet::find($input['planetId']);
        $resourceType = $input['resourceType'];
        $f = new FormatApiResponseService;
        $r = new ResourceService;

        // verification
        if (!$this->playerOwnsPlanet($player, $planet)) {
            return response()
                ->json(['error' => __('game.empire.errors.harvester.owner')], 419);
        }
        if (!$this->harvesterInstallable($player, $planet, $resourceType)) {
            return response()
                ->json(['error' => __('game.empire.errors.harvester.slot')], 419);
        }
        if (!$this->playerCanAffordHarvester($player, $resourceType)) {
            return response()
                ->json(['error' => __('game.common.errors.noFunds')], 419);
        }

        // pay for the harvester
        $this->pay($player, $resourceType);

        // install harvester
        $harvesterRules = config('rules.harvesters.'.$resourceType);
        $resourceSlot = array_filter($planet->resources, function($res) use ($resourceType) {
            return $res["resourceType"] === $resourceType;
        });
        $efficiency = array_values($resourceSlot)[0]['value'];
        // we calculate harvester production so we don't have to calculate it during turn processing
        // (unless we decide to have tech levels for resources)
        $harvester = Harvester::create([
            'planet_id' => $planet->id,
            'game_id' => $planet->game->id,
            'player_id' => $player->id,
            'resource_type' => $resourceType,
            'production' => round($harvesterRules['baseProduction'] * $efficiency, 2),
            'until_complete' => $harvesterRules['costs']['turns'],
        ]);

        Log::channel('api')
            ->info("Empire $player->ticker has started building a harvester: ".json_encode($f->formatHarvester($harvester), JSON_PRETTY_PRINT));

        return response()->json([
            'harvester' => $f->formatHarvester($harvester),
            'resources' => $r->getResources($player),
            'message' => __('game.empire.harvesterInstalled')
        ]);

    }

}
