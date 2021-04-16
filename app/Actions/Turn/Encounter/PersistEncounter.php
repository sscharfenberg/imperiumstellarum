<?php

namespace App\Actions\Turn\Encounter;

use App\Models\EncounterTurn;

use App\Services\EncounterService;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PersistEncounter
{

    /**
     * @function handle persisting encounter - update/delete database entries.
     * @param Collection $encounter
     * @param string $turnSlug
     * @param EncounterTurn $encounterTurn
     * @return void
     */
    public function handle (Collection $encounter, string $turnSlug, EncounterTurn $encounterTurn)
    {

        echo "\n\nTODO: PERSISTING ENCOUNTER TO DATABASE\n";
        echo "remove these ships from the database:\n";
        dump($encounter['dead_ships']);

    }

}
