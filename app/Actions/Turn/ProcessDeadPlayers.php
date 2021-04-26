<?php

namespace App\Actions\Turn;

use App\Models\Blueprint;
use App\Models\ConstructionContract;
use App\Models\Fleet;
use App\Models\Game;
use App\Models\Player;

use App\Models\PlayerRelationChange;
use App\Models\PlayerResource;
use App\Models\Research;
use App\Models\StorageUpgrade;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class ProcessDeadPlayers
{

    /**
     * @function handle dead player. mark as dead, delete fleets.
     * @param Game $game
     * @param Player $player
     * @param string $turnSlug
     * @throws Exception
     */
    private function handleDeadPlayer (Game $game, Player $player, string $turnSlug)
    {
        $player->dead = true;
        $player->save();
        Log::channel('turn')->notice("$turnSlug player [$player->ticker] $player->name is now dead.");

        // fleets
        $deletedFleets = Fleet::where('game_id','=', $game->id)
            ->where('player_id', '=', $player->id)
            ->delete();
        Log::channel('turn')->notice("$turnSlug deleted $deletedFleets fleets.");

        // blueprints
        $deletedContracts = ConstructionContract::where('game_id','=', $game->id)
            ->where('player_id', '=', $player->id)
            ->delete();
        Log::channel('turn')->notice("$turnSlug deleted $deletedContracts construction contracts.");

        // playerRelationChanges
        $deletedRelationChanges = PlayerRelationChange::where('game_id','=', $game->id)
            ->where('player_id', '=', $player->id)
            ->orWhere('recipient_id', '=', $player->id)
            ->delete();
        Log::channel('turn')->notice("$turnSlug deleted $deletedRelationChanges player relation changes.");

        // player resource entries
        $deletedResources = PlayerResource::where('player_id', '=', $player->id)
            ->update(['storage' => 0]);
        Log::channel('turn')->notice("$turnSlug set $deletedResources player resource types to storage=0.");

        // research
        $deletedResearch = Research::where('game_id','=', $game->id)
            ->where('player_id', '=', $player->id)
            ->delete();
        Log::channel('turn')->notice("$turnSlug deleted $deletedResearch researches.");

        // storage upgrades
        $deletedStorageUpgrades = StorageUpgrade::where('game_id','=', $game->id)
            ->where('player_id', '=', $player->id)
            ->delete();
        Log::channel('turn')->notice("$turnSlug deleted $deletedStorageUpgrades storage upgrades.");

        // TODO: send notification
        // TODO: send email to user

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
        $corpses = Player::where('game_id', '=', $game->id)
            ->whereDoesntHave('stars') // player has not stars
            ->whereDoesntHave('ships') // and no ships
            ->with('fleets')
            ->get();
        if (count($corpses) > 0) {
            Log::channel('turn')->notice("$turnSlug found ".count($corpses)." players with no stars and no ships.");
            $corpses->each( function ($player) use ($game, $turnSlug) {
                $this->handleDeadPlayer($game, $player, $turnSlug);
            });
        } else {
            Log::channel('turn')->notice("$turnSlug no players with no stars and no ships. Everything cool.");
        }
    }


}
