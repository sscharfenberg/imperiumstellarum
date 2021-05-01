<?php

namespace App\Actions\Game;

use App\Models\FinishedGame;
use App\Models\FinishedGameParticipant;
use App\Models\Game;
use App\Models\Player;

use Illuminate\Support\Collection;

class ProcessFinishedGame {


    /**
     * size of chunks for database operations
     * @var int
     */
    private $chunkSize = 20;


    /**
     * @function format json for player shipyards
     * @param Collection $shipyards
     * @return array
     */
    private function formatPlayerShipyards (Collection $shipyards): array
    {
        $playerShipyards = [];
        $numSmall = count($shipyards->filter(function($ship) {
            return $ship['type'] === 'small';
        }));
        $numMedium = count($shipyards->filter(function($ship) {
            return $ship['type'] === 'medium';
        }));
        $numLarge = count($shipyards->filter(function($ship) {
            return $ship['type'] === 'large';
        }));
        $numXlarge = count($shipyards->filter(function($ship) {
            return $ship['type'] === 'xlarge';
        }));
        if ($numSmall > 0) $playerShipyards['small'] = $numSmall;
        if ($numMedium > 0) $playerShipyards['medium'] = $numMedium;
        if ($numLarge > 0) $playerShipyards['large'] = $numLarge;
        if ($numXlarge > 0) $playerShipyards['xlarge'] = $numXlarge;
        return $playerShipyards;
    }

    /**
     * @function format json for player ships
     * @param Collection $ships
     * @return array
     */
    private function formatPlayerShips (Collection $ships): array
    {
        $p = new \App\Actions\Turn\Encounter\PersistTurn;
        return $p->formatShips($ships);
    }


    /**
     * @function prepare & insert "FinishedGameParticipants" into database
     * @param FinishedGame $game
     * @param Collection $players
     * @param string $turnSlug
     */
    private function processFinishedGameParticipant (FinishedGame $game, Collection $players, string $turnSlug)
    {
        $participants = $players->map(function ($player) use ($game) {
            return [
                'id' => $player->id,
                'game_id' => $game->id,
                'name' => $player->name,
                'ticker' => $player->ticker,
                'colour' => $player->colour,
                'died' => $player->dead,
                'population' => $player->population,
                'stars' => count($player->stars),
                'ships' => count($player->ships) > 0
                    ? json_encode($this->formatPlayerShips($player->ships))
                    : null,
                'shipyards' => count($player->shipyards) > 0
                    ? json_encode($this->formatPlayerShipyards($player->shipyards))
                    : null,
                'created_at' => now(),
                'updated_at' => now()
            ];
        })->toArray();
        $chunks = array_chunk($participants, $this->chunkSize);
        foreach($chunks as $chunk) {
            FinishedGameParticipant::insert($chunk);
        }
    }


    /**
     * @function process a finished game by creating appropriate database entries.
     * @param Game $game
     * @param Player $winner
     * @param string $turnSlug
     */
    public function processGame (Game $game, Player $winner, string $turnSlug)
    {
        $finishedGame = FinishedGame::create([
            'number' => $game->number,
            'dimensions' => $game->dimensions,
            'turns' => $game->turns->max('number'),
            'winner_id' => $winner->id,
            'start_date' => $game->start_date,
            'end_date' => now()
        ]);
        $players = Player::where('game_id', '=', $game->id)
            ->with('stars')
            ->with('ships')
            ->with('shipyards')
            ->get();

        // count the number of pl
        $players = $players->map(function ($player) {
            $stars = $player->stars;
            $population = 0;
            $stars->each(function ($star) use (&$population) {
                $population += $star->planets->sum('population');
            });
            $player->population = $population;
            return $player;
        });

        // create FinishedGameParticipants
        $this->processFinishedGameParticipant($finishedGame, $players, $turnSlug);
    }

}
