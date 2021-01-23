<?php

namespace App\Actions\Turn;

use App\Models\Game;
use App\Models\Shipyard;
use Illuminate\Support\Facades\Log;


class BuildShipyards
{

    /**
     * @function handle population growth
     * @param Game $game
     * @param string $turnSlug
     * @return void
     */
    public function handle(Game $game, string $turnSlug)
    {
        $decrementedShipyards = Shipyard::where('game_id', $game->id)
            ->where('until_complete', '>', '0')
            ->decrement('until_complete');
        if ($decrementedShipyards) {
            Log::notice("TURN PROCESSING $turnSlug - Decreased 'until_complete' for $decrementedShipyards shipyards.");
        } else {
            Log::notice("TURN PROCESSING $turnSlug - No shipyards building.");
        }
    }

}
