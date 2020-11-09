<?php

namespace App\Actions\Turn;

use App\Models\Game;
use App\Models\Shipyard;
use App\Models\Player;
use App\Services\FormatApiResponseService;
use App\Services\ResourceService;
use Illuminate\Support\Facades\Log;


class BuildShipyards
{

    /**
     * @function handle population growth
     * @param Game $game
     * @return void
     */
    public function handle(Game $game)
    {
        $decrementedShipyards = Shipyard::where('game_id', $game->id)
            ->where('until_complete', '>', '0')
            ->decrement('until_complete');
        if ($decrementedShipyards) {
            Log::notice("Decreased 'until_complete' for $decrementedShipyards shipyards.");
        } else {
            Log::notice("No shipyards building.");
        }
    }

}
