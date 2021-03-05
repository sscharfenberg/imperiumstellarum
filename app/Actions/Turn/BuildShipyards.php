<?php

namespace App\Actions\Turn;

use App\Models\Game;
use App\Models\Shipyard;
use App\Services\FormatApiResponseService;
use App\Services\MessageService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;


class BuildShipyards
{

    /**
     * @function notify shipyard owners that the shipyard is now completed.
     * @param Collection $shipyards
     * @param string $turnSlug
     * @param string $gameId
     * @return void
     */
    private function notifyCompletedShipyardOwners (Collection $shipyards, string $turnSlug, string $gameId)
    {
        $m = new MessageService;
        $f = new FormatApiResponseService;
        $gameUsers = $m->getGameUsers($gameId);
        foreach($shipyards as $shipyard) {
            $player = $shipyard->player;
            $messageLocale = $gameUsers
                ->where('id', '=', $player->user_id)
                ->first()
                ->locale;
            $planet = $shipyard->planet;
            $star = $planet->star;
            $starname = $star->name." - ".$f->convertLatinToRoman($planet->orbital_index);
            $m->sendNotification(
                $shipyard->player,
                'game.messages.sys.shipyards.shipyardCompleted.subject',
                'game.messages.sys.shipyards.shipyardCompleted.body',
                [
                    'name' => $star->name." - ".$f->convertLatinToRoman($planet->orbital_index),
                    'type' => __('game.common.hulls.'.$shipyard->type, [], $messageLocale)
                ]
            );
            $shipyard->notified = true;
            $shipyard->save();
            Log::notice("TURN PROCESSING $turnSlug - construction of $shipyard->type shipyard on planet $starname is completed, owner notified.");
        }
    }

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

        // send player notifications
        $completedShipyards = Shipyard::where('game_id', '=', $game->id)
            ->where('until_complete', '=', 0)
            ->where('notified', '=', false)
            ->get();
        if (count($completedShipyards) > 0) {
            $this->notifyCompletedShipyardOwners($completedShipyards, $turnSlug, $game->id);
        }

    }

}
