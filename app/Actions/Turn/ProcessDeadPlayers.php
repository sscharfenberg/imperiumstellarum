<?php

namespace App\Actions\Turn;

use App\Models\ConstructionContract;
use App\Models\Fleet;
use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerRelation;
use App\Models\PlayerRelationChange;
use App\Models\PlayerResource;
use App\Models\Research;
use App\Models\StorageUpgrade;
use App\Models\Turn;
use App\Services\MessageService;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Notifications\GameOver;

class ProcessDeadPlayers
{


    /**
     * @function send email to user of dead player
     * @param Game $game
     * @param Player $player
     * @param string $turnSlug
     */
    private function sendEmail (Game $game, Player $player, string $turnSlug)
    {
        $user = $player->user;
        $user->notify(
            (new GameOver($game->number, "[$player->ticker] $player->name"))->locale($user->locale)
        );
        Log::channel('turn')->notice(
            "$turnSlug sent email to player [$player->ticker] $player->name ($user->email)."
        );
    }

    /**
     * @function send notification to dead player
     * @param Player $player
     * @param string $turnSlug
     */
    private function sendNotification (Player $player, string $turnSlug)
    {
        $m = new MessageService;
        $m->sendNotification(
            $player,
            'game.messages.sys.playerDied.subject',
            'game.messages.sys.playerDied.body',
            []
        );
        Log::channel('turn')->notice("$turnSlug notification sent to player [$player->ticker] $player->name.");
    }


    /**
     * @function handle dead player. mark as dead, delete fleets.
     * @param Game $game
     * @param Player $player
     * @param string $turnSlug
     * @throws Exception
     */
    private function handleDeadPlayer (Game $game, Player $player, string $turnSlug)
    {
        $turn = Turn::where('game_id', '=', $game->id)
            ->get()
            ->max('number');
        $player->dead = true;
        $player->died_turn = $turn;
        $player->save();
        Log::channel('turn')->notice("$turnSlug player [$player->ticker] $player->name died on turn t$turn.");

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

        // PlayerRelations
        $deletedRelations = PlayerRelation::where('game_id','=', $game->id)
            ->where('player_id', '=', $player->id)
            ->orWhere('recipient_id', '=', $player->id)
            ->delete();
        Log::channel('turn')->notice("$turnSlug deleted $deletedRelations player relations.");
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

        // send notification
        $this->sendNotification($player, $turnSlug);

        // send email to user
        if ($player->user->game_mail_optin) {
            $this->sendEmail($game, $player, $turnSlug);
        }
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
            ->where('dead', '=', false)
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
