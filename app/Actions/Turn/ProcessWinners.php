<?php

namespace App\Actions\Turn;

use App\Models\Game;

use App\Models\Planet;
use App\Models\Player;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;


class ProcessWinners
{


    /**
     * @function update the game that is finished.
     * @param Game $game
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
            $this->updateFinishedGame($game, $playerWithPeakPopulation, $turnSlug);
            // ...
        } else {
            Log::channel('turn')->info(
                "$turnSlug player does not have sufficient population to qualify for a game win."
            );
        }
    }


}
