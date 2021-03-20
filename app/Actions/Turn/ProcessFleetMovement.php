<?php

namespace App\Actions\Turn;

use App\Models\FleetMovement;
use App\Models\User;
use App\Services\MessageService;
use Exception;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;


class ProcessFleetMovement
{

    /**
     * @function complete fleet movements: set fleet star_id, delete fleet movement.
     * @param Collection $movements
     * @param string $turnSlug
     * @param string $gameId
     */
    private function completeFleetMovement(Collection $movements, string $turnSlug, string $gameId)
    {
        $m = new MessageService;
        foreach($movements as $movement) {
            $fleet = $movement->fleet;
            $destination = $movement->star;
            try {
                $movement->delete();
                $fleet->star_id = $destination->id;
                $fleet->save();
                Log::channel('turn')->notice("$turnSlug - Fleet $fleet->name has arrived at $destination->name.");
                $m->sendNotification(
                    $fleet->player,
                    'game.messages.sys.fleets.arrival.subject',
                    'game.messages.sys.fleets.arrival.body',
                    [
                        'name' => $fleet->name,
                        'location' => $destination->name." ($destination->coord_x/$destination->coord_y)"
                    ]
                );
                // TODO: check if fleet is at a player-owned location other than the player's own systems. Notify owner.
            } catch(Exception $e) {
                Log::channel('turn')->error("$turnSlug - failed to complete fleet movement $movement->id: ".$e->getMessage());
            }
        }
        Log::channel('turn')->notice("$turnSlug - finished looping all fleet movements.");
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
            Log::channel('turn')->notice("$turnSlug - Decreased 'until_arrival' for $movedFleets fleets.");
        } else {
            Log::channel('turn')->notice("$turnSlug - No moving fleets.");
        }

        // check if any FleetMovements are completed
        $completedMovements = FleetMovement::where('game_id', $game->id)
            ->where('until_arrival','=', '0')
            ->get();
        $num = count($completedMovements);
        if ($num > 0) {
            Log::channel('turn')->notice("$turnSlug - Completing movement of $num fleets.");
            $this->completeFleetMovement($completedMovements, $turnSlug, $game->id);
        } else {
            Log::channel('turn')->notice("$turnSlug - No fleet movement has been completed.");
        }
        Log::channel('turn')->notice("$turnSlug - finished processing fleet movements.");
    }

}
