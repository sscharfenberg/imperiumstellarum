<?php

namespace App\Actions\Turn\Encounter;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\Game;
use Exception;

class ProcessEncounter
{

    /**
     * @function find out if any encounters need to be processed and trigger processing.
     * @param Game $game
     * @param string $turnSlug
     * @param Collection $encounter
     * @throws Exception
     * @return void
     */
    public function handle(Game $game, Collection $encounter, string $turnSlug)
    {
        echo "$turnSlug processing encounter at star ".$encounter['star']['name']."\n";
        Log::channel('encounter')
            ->debug(
                "start processing of encounter at ["
                .$encounter['star']->owner->ticker."] "
                .$encounter['star']['name'].".\n"
                .$encounter->toJson(JSON_PRETTY_PRINT)
            );
        foreach($encounter['defenderShips'] as $ship) {
            echo "$ship->name\n";
        }
        dd(count($encounter['defenderShips']));
    }

}
