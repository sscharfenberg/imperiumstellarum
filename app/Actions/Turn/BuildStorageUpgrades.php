<?php

namespace App\Actions\Turn;

use App\Models\Game;
use App\Models\StorageUpgrade;
use App\Services\MessageService;
use Illuminate\Support\Facades\Log;

class BuildStorageUpgrades
{


    /**
     * @function handle finished storage upgrades
     * @param StorageUpgrade $upgrade
     * @param string $turnSlug
     * @return void
     */
    private function finishedUpgrade (StorageUpgrade $upgrade, string $turnSlug)
    {
        $m = new MessageService;
        $ticker = $upgrade->player->ticker;
        $type = $upgrade->resource_type;
        Log::info("TURN PROCESSING $turnSlug - finalizing $type storage upgrade for empire [$ticker]");
        $playerResource = $upgrade->player->resources
            ->where('resource_type', $upgrade->resource_type)->first();
        $player = $upgrade->player;
        try {
            $playerResource->storage_level = $upgrade->new_level;
            $playerResource->save();
            $upgrade->delete();
            $messageLocale = $player->user->locale;
            $m->sendNotification(
                $player,
                'game.messages.sys.storageUpgrade.finished.subject',
                'game.messages.sys.storageUpgrade.finished.body',
                [
                    'type' => __('game.common.resourceTypes.'.$upgrade->resource_type, [], $messageLocale),
                    'level' => $upgrade->new_level,
                    'capacity' => config('rules.player.resourceTypes.energy.'.$upgrade->new_level.'.amount')
                ]
            );
            Log::info("TURN PROCESSING $turnSlug - Empire [$ticker] has upgraded $type storage to level $upgrade->new_level");
        } catch (\Exception $e) {
            Log::error("TURN PROCESSING $turnSlug - Exception while handling a finished storage upgrade:\n". $e->getMessage());
        }
    }


    /**
     * @function handle storage upgrades
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    public function handle(Game $game, string $turnSlug)
    {
        // decrement 'until_complete' by 1
        $decrementedUpgrades = StorageUpgrade::where('game_id', $game->id)
            ->where('until_complete', '>', '0')
            ->decrement('until_complete');

        if ($decrementedUpgrades) {
            Log::notice("TURN PROCESSING $turnSlug - Decreased 'until_complete' for $decrementedUpgrades storage upgrades.");
        } else {
            Log::notice("TURN PROCESSING $turnSlug - No storage upgrades building.");
        }

        // handle finished upgrades
        $finishedUpgrades = StorageUpgrade::where('game_id', $game->id)
            ->where('until_complete', '=', 0)
            ->get();
        foreach($finishedUpgrades as $storageUpgrade) {
            $this->finishedUpgrade($storageUpgrade, $turnSlug);
        }
    }


}
