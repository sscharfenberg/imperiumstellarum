<?php

namespace App\Http\Controllers\Game;

use App\Models\Player;
use App\Models\StorageUpgrade;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\ResourceService;
use App\Services\ApiService;

class StorageUpgradeController extends Controller
{

    /**
     * @function verify that level is installable
     * (next level = current level +1, current level !== maxLevel)
     * @param Player $player
     * @param string $type
     * @param int $level
     * @return bool
     */
    private function levelIsInstallable (Player $player, string $type, int $level): bool
    {
        $res = $player->resources()->where('resource_type', $type)->first();
        $maxLevel = array_key_last(config('rules.player.resourceTypes.'.$type));
        if ($level !== $res->storage_level + 1) return false;
        if ($level > $maxLevel) return false;
        return true;
    }

    /**
     * @function verify player has the necessary resources
     * @param Player $player
     * @param string $type
     * @param int $level
     * @return bool
     */
    private function playerCanAfford (Player $player, string $type, int $level): bool
    {
        $costs = array_filter(
            config('rules.player.resourceTypes.'.$type.'.'.$level.'.costs'), function($resType) {
                return $resType !== 'turns';
        }, ARRAY_FILTER_USE_KEY);
        $r = new ResourceService();
        if (!$r->playerCanAfford($player, $costs)) return false;
        return true;
    }

    /**
     * @function verify player does not already have a storage upgrade
     * of the resource type building
     * @param Player $player
     * @param string $type
     * @return bool
     */
    private function buildAvailable (Player $player, string $type): bool
    {
        $upgradesBuilding = $player->storageUpgrades()->where('resource_type', $type)->get();
        if (count($upgradesBuilding) !== 0) return false;
        return true;
    }

    /**
     * @function subtract the resources needed for the upgrade
     * @param Player $player
     * @param string $type
     * @param int $level
     * @return void
     */
    private function pay (Player $player, string $type, int $level)
    {
        $costs = array_filter(
            config('rules.player.resourceTypes.'.$type.'.'.$level.'.costs'), function($resType) {
            return $resType !== 'turns';
        }, ARRAY_FILTER_USE_KEY);
        $r = new ResourceService();
        $r->subtractResources($player, $costs);
    }


    /**
     * @function handle storage upgrade installation
     * @param Request $request
     * @return JsonResponse
     */
    public function install (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $input = $request->input();

        if (!$this->levelIsInstallable($player, $input['type'], $input['level'])) {
            return response()
                ->json(['error' => __('game.common.errors.header.level')], 419);
        }
        if (!$this->playerCanAfford($player, $input['type'], $input['level'])) {
            return response()
                ->json(['error' => __('game.common.errors.noFunds')], 419);
        }
        if (!$this->buildAvailable($player, $input['type'])) {
            return response()
                ->json(['error' => __('game.common.errors.header.alreadyBuilding')], 419);
        }

        // pay for the storage upgrade with resources
        $this->pay($player, $input['type'],  $input['level']);

        // create the storage upgrade order
        $untilComplete = config(
            'rules.player.resourceTypes.'.$input['type'].'.'.$input['level'].'.costs.turns'
        );
        StorageUpgrade::create([
            'player_id' => $player->id,
            'game_id' => $player->game->id,
            'resource_type' => $input['type'],
            'new_level' => $input['level'],
            'until_complete' => $untilComplete
        ]);

        Log::info('API: Empire ['.$player->ticker.'] ordered a '.$input['type'].' storage upgrade @ lvl'.$input['level']);

        $r = new ResourceService();
        $a = new ApiService();
        return response()->json([
            'storageUpgrades' => $a->storageUpgrades($player),
            'resources' => $r->getResources($player),
            'message' => __('game.common.storageUpgradeOrdered', ['num' => $untilComplete])
        ], 200);

    }
}
