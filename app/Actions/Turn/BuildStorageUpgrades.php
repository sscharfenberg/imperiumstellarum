<?php

namespace App\Actions\Turn;

use App\Models\Game;
use App\Models\StorageUpgrade;
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
        $ticker = $upgrade->player->ticker;
        $type = $upgrade->resource_type;
        Log::info("TURN PROCESSING $turnSlug - finalizing $type storage upgrade for empire [$ticker]");
        $playerResource = $upgrade->player->resources
            ->where('resource_type', $upgrade->resource_type)->first();
        $playerResource->storage_level = $upgrade->new_level;
        $playerResource->save();
        try {
            $upgrade->delete();
        } catch (\Exception $e) {
            Log::error("TURN PROCESSING $turnSlug - Exception while handling a finished storage upgrade:\n". $e->getMessage());
        }
        Log::info("TURN PROCESSING $turnSlug - Empire [$ticker] has upgraded $type storage to level $upgrade->new_level");
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
        $decrementedUpgrades = StorageUpgrade::where('game_id', $game->id)->decrement('until_complete');
        if ($decrementedUpgrades) {
            Log::notice("TURN PROCESSING $turnSlug - Decreased 'until_complete' for $decrementedUpgrades storage upgrades.");
        } else {
            Log::notice("TURN PROCESSING $turnSlug - No storage upgrades building.");
        }

        // handle finished upgrades
        $finishedUpgrades = StorageUpgrade::where('game_id', $game->id)
            ->where('until_complete', 0)->get();
        foreach($finishedUpgrades as $storageUpgrade) {
            $this->finishedUpgrade($storageUpgrade, $turnSlug);
        }
    }


}
