<?php

namespace App\Actions\Turn;

use App\Models\Game;

use App\Models\Planet;
use App\Models\Player;
use App\Models\User;
use App\Services\MessageService;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Notifications\GameFinished;

class ProcessWinners
{


    /**
     * @function update the game that is finished.
     * @param Game $game
     * @param Player $winner
     * @param string $turnSlug
     */
    private function updateFinishedGame (Game $game, Player $winner, string $turnSlug)
    {
        $game->finished = true;
        $game->active = false;
        $game->save();
        Log::channel('turn')->info("$turnSlug game set to 'finished'.");
        // process finished game
        $g = new \App\Actions\Game\ProcessFinishedGame;
        $g->processGame($game, $winner, $turnSlug);
    }


    /**
     * @function send ingame notifications to all players that the game has ended.
     * @param Game $game
     * @param Player $winner
     * @param string $turnSlug
     */
    private function sendNotifications (Game $game, Player $winner, string $turnSlug)
    {
        $m = new MessageService;
        $gameNumber = $game->number;
        $turn = $game->turns->max('number');
        $game->players->each(function ($player) use ($gameNumber, $turn, $winner, $m, $turnSlug) {
            $m->sendNotification(
                $player,
                'game.messages.sys.gameOver.subject',
                'game.messages.sys.gameOver.body',
                [
                    'number' => $gameNumber,
                    'winner' => "[$winner->ticker] $winner->name",
                    'turn' => $turn
                ]
            );
            Log::channel('turn')->notice("$turnSlug notification sent to player [$player->ticker] $player->name.");
        });
    }


    /**
     * @function send emails to all players that have optin activated
     * @param Game $game
     * @param Player $winner
     * @param string $turnSlug
     */
    private function sendEmails (Game $game, Player $winner, string $turnSlug)
    {
        $userIds = $game->players->map(function ($player) {
            return $player->user_id;
        });
        $recipients = User::whereIn('id', $userIds)->get()
            ->filter( function($user) {
                return $user->game_mail_optin;
            });
        $gameNumber = $game->number;
        $turn = $game->turns->max('number');
        $recipients->each( function ($recipient) use ($gameNumber, $turn, $winner, $turnSlug) {
            $recipient->notify(
                (new GameFinished(
                    $gameNumber, $turn, "[$winner->ticker] $winner->name")
                )->locale($recipient->locale)
            );
            Log::channel('turn')->notice("$turnSlug email sent to user $recipient->email");
        });
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
        // get all planets with population
        $populatedPlanets = Planet::where('game_id', '=', $game->id)
            ->where('population', '>', 0)
            ->whereHas('star', function (Builder $query) {
                $query->whereNotNull('player_id');
            })
            ->with('star')
            ->get();
        $totalPopulation = $populatedPlanets->sum('population');
        Log::channel('turn')->notice("$turnSlug total population is $totalPopulation.");

        // get all non-dead players
        $players = Player::where('game_id', '=', $game->id)
            ->where('dead', false)
            ->get();
        $playerPopulation = $players->pluck('id')
            ->mapWithKeys( function($playerId) {
                return [$playerId => 0];
            });

        // loop populated planets and add population to player population "score"
        $populatedPlanets->each( function ($planet) use (&$playerPopulation) {
            $ownerId = $planet->star->player_id;
            $population = $playerPopulation->get($ownerId) + $planet->population;
            $playerPopulation->put($ownerId, $population);
        });
        Log::channel('turn')->notice(
            "$turnSlug population scores are: ".json_encode($playerPopulation->sortDesc(), JSON_PRETTY_PRINT)
        );

        // get player and population
        $playerIdWithPeakPopulation = $playerPopulation->sortDesc()->keys()->first();
        $playerWithPeakPopulation = $players->where('id', '=', $playerIdWithPeakPopulation)->first();
        $peakPopulation = $playerPopulation->sortDesc()->first();
        Log::channel('turn')->notice(
            "$turnSlug player with highest population is '[$playerWithPeakPopulation->ticker] "
            .$playerWithPeakPopulation->name."' with $peakPopulation total population."
        );

        // victory condition: population
        // TODO: military victory? political victory?
        if ($peakPopulation >= $totalPopulation * config('rules.winner.populationShare')) {
            Log::channel('turn')->info(
                "\n\n\n===============================================================\n"
                ."$turnSlug '[$playerWithPeakPopulation->ticker] "
                .$playerWithPeakPopulation->name."' has won the game!"
                ."\n===============================================================\n\n"
            );

            // update game
            //$this->updateFinishedGame($game, $playerWithPeakPopulation, $turnSlug);

            // send notifications to all players.
            //$this->sendNotifications($game, $playerWithPeakPopulation, $turnSlug);

            // send emails to players with game_mail_optin
            $this->sendEmails($game, $playerWithPeakPopulation, $turnSlug);

        } else {
            Log::channel('turn')->info(
                "$turnSlug player does not have sufficient population to qualify for a game win."
            );
        }
    }


}
