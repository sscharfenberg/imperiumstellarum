<?php

namespace App\Actions\Turn;

use App\Models\FleetMovement;
use Exception;
use App\Models\Game;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;


class ProcessFleetMovement
{

    /**
     * @function complete fleet movements: set fleet star_id, delete fleet movement.
     * @param Collection $movements
     * @param string $turnSlug
     */
    private function completeFleetMovement(Collection $movements, string $turnSlug)
    {
        foreach($movements as $movement) {
            $fleet = $movement->fleet;
            $destination = $movement->star;
            try {
                $movement->delete();
                $fleet->star_id = $destination->id;
                $fleet->save();
                // TODO: send system message to player that a fleet has arrived.
                Log::notice("TURN PROCESSING $turnSlug - Fleet $fleet->name has arrived at $destination->name.");
            } catch(Exception $e) {
                Log::error("TURN PROCESSING $turnSlug - failed to complete fleet movement $movement->id: ".$e->getMessage());
            }
        }
        Log::notice("TURN PROCESSING $turnSlug - finished looping all fleet movements.");
    }


    /**
     * @function build ships
     * @param Game $game
     * @param string $turnSlug
     * @throws Exception
     * @return void
     */
    public function handle(Game $game, string $turnSlug)
    {
        $movedFleets = FleetMovement::where('game_id', $game->id)
            ->where('until_arrival', '>', '0')
            ->decrement('until_arrival');
        if ($movedFleets) {
            Log::notice("TURN PROCESSING $turnSlug - Decreased 'until_arrival' for $movedFleets fleets.");
        } else {
            Log::notice("TURN PROCESSING $turnSlug - No moving fleets.");
        }

        // check if any FleetMovements are completed
        $completedMovements = FleetMovement::where('game_id', $game->id)
            ->where('until_arrival','=', '0')
            ->get();
        $num = count($completedMovements);
        if ($num > 0) {
            Log::notice("TURN PROCESSING $turnSlug - Completing movement of $num fleets.");
            $this->completeFleetMovement($completedMovements, $turnSlug);
        } else {
            Log::notice("TURN PROCESSING $turnSlug - No fleet movement has been completed.");
        }
        Log::notice("TURN PROCESSING $turnSlug - finished processing fleet movements.");
    }

}
