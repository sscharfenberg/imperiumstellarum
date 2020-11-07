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
     * @return void
     */
    private function handleInstallations (Game $game)
    {
        $decrementedHarvesters = Harvester::where('game_id', $game->id)
            ->where('until_complete', '>', '0')
            ->decrement('until_complete');
        if ($decrementedHarvesters) {
            Log::notice("Decreased 'until_complete' for $decrementedHarvesters harvesters.");
        } else {
            Log::notice("No harvesters building.");
        }
    }


    /**
     * @function harvester resource production
     * @param Game $game
     * @return void
     */
    private function handleResourceProduction (Game $game)
    {
        $players = Player::where('game_id', $game->id)->get();
        $harvesters = Harvester::where('game_id', $game->id)
            ->where('until_complete', '0')->get();
        $r = new ResourceService;
        $f = new FormatApiResponseService;
        foreach ($players as $player) {
            $playerResources = $player->resources;

            // get player harvesters
            $harvesters = $harvesters->filter(function($harvester) use ($player) {
                return $harvester->player_id === $player->id;
            });

            // loop all player harvesters and add production to resources.
            foreach ($harvesters as $harvester) {
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

            Log::notice('Empire '.$player->ticker.' resources after production: '.json_encode($resourcesAfterProduction));
        }
    }


    /**
     * @function handle population growth
     * @param Game $game
     * @return void
     */
    public function handle(Game $game)
    {
        // decrement 'until_complete' by 1
        $this->handleInstallations($game);

        // produce resources
        $this->handleResourceProduction($game);

    }


}