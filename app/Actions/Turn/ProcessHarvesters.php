<?php

namespace App\Actions\Turn;

use App\Models\Game;
use App\Models\Harvester;
use App\Models\Player;
use App\Services\FormatApiResponseService;
use App\Services\ResourceService;
use Illuminate\Support\Facades\Log;


class ProcessHarvesters
{


    /**
     * @function decrease "until_complete" for building harvesters
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    private function handleInstallations (Game $game, string $turnSlug)
    {
        $decrementedHarvesters = Harvester::where('game_id', $game->id)
            ->where('until_complete', '>', '0')
            ->decrement('until_complete');
        if ($decrementedHarvesters) {
            Log::notice("TURN PROCESSING $turnSlug - Decreased 'until_complete' for $decrementedHarvesters harvesters.");
        } else {
            Log::notice("TURN PROCESSING $turnSlug - No harvesters building.");
        }
    }


    /**
     * @function harvester resource production
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    private function handleResourceProduction (Game $game, string $turnSlug)
    {
        $players = Player::where('game_id', $game->id)->get();
        $harvesters = Harvester::where('game_id', $game->id)
            ->where('until_complete', '=', '0')->get();
        $r = new ResourceService;
        $f = new FormatApiResponseService;
        foreach ($players as $player) {
            $playerResources = $player->resources;

            // get player harvesters
            $playerHarvesters = $harvesters->filter(function($harvester) use ($player) {
                return $harvester->player_id === $player->id;
            });

            // loop all player harvesters and add production to resources.
            foreach ($playerHarvesters as $harvester) {
                $res = $playerResources->where('resource_type', $harvester->resource_type)->first();
                $res->storage += $harvester->production;
            }

            // save player resources
            foreach($playerResources as $res) {
                $res->storage = $r->enforceStorageMax($res);
                $res->save();
            };

            $resourcesAfterProduction = $playerResources->map(function($res) use ($f) {
                return $f->formatPlayerResource($res);
            });

            Log::notice("TURN PROCESSING $turnSlug - Empire $player->ticker resources after production: ".json_encode($resourcesAfterProduction));
        }

        Log::notice("TURN PROCESSING $turnSlug - Looped all players for resource production from harvesters.");
    }


    /**
     * @function handle population growth
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    public function handle(Game $game, string $turnSlug)
    {
        // decrement 'until_complete' by 1
        $this->handleInstallations($game, $turnSlug);

        // produce resources
        $this->handleResourceProduction($game, $turnSlug);

    }


}
