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

class HarvesterController extends Controller
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
     * @function verify there is an empty slot for the harvester
     * @param Player $player
     * @param Planet $planet
     * @param string $resourceType
     * @return false
     */
    private function harvesterInstallable(Player $player, Planet $planet, string $resourceType)
    {
        $numPlayerHarvesters = count($player->harvesters
            ->where('planet_id', $planet->id)
            ->where('resource_type', $resourceType)
        );
        $resourceSlots = array_filter($planet->resources, function($res) use ($resourceType) {
            return $res["resourceType"] === $resourceType;
        });
        $numSlots = array_values($resourceSlots)[0]['slots'];
        return $numSlots > $numPlayerHarvesters;
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
            config('rules.harvesters.'.$type.'.costs'), function($resType) {
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
            config('rules.harvesters.'.$type.'.costs'), function($resType) {
            return $resType !== 'turns';
        }, ARRAY_FILTER_USE_KEY);
        $r = new ResourceService();
        $r->subtractResources($player, $costs);
    }

    /**
     * @function handle change food consumption xhr request
     * @param Request $request
     * @return JsonResponse
     */
    public function install (Request $request)
    {
        $player = Player::find(Auth::user()->selected_player);
        $input = $request->input();
        $planet = Planet::find($input['planetId']);
        $resourceType = $input['resourceType'];

        // verification
        if (!$this->playerOwnsPlanet($player, $planet)) {
            return response()
                ->json(['error' => __('game.empire.errors.harvester.owner')], 419);
        }
        if (!$this->harvesterInstallable($player, $planet, $resourceType)) {
            return response()
                ->json(['error' => __('game.empire.errors.harvester.slot')], 419);
        }
        if (!$this->playerCanAfford($player, $resourceType)) {
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

        $f = new FormatApiResponseService;
        $r = new ResourceService;
        return response()->json([
            'harvester' => $f->formatHarvester($harvester),
            'resources' => $r->getResources($player),
        ]);

    }

}
