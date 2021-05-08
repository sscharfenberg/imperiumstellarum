<?php

namespace Database\Seeders;

use App\Models\Fleet;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Database\Seeder;

class FleetSeeder extends Seeder
{

    /**
     * random prepared fleet names
     * @var array
     */
    private $fleetNames = [
        '1st Fleet',
        '2nd Fleet',
        '3rd Fleet',
        '4th Fleet',
        '1st Guards',
        '2nd Guards',
        '3rd Guards',
        '4th Guards',
        '1st Flotilla',
        '2nd Flotilla',
        '3rd Flotilla',
        'Royal Chevaliers',
        'Queen\'s Dragoon Guards',
        'Royal Lancers',
        'Queen\'s Royal Hussars',
        'Kings Light Dragoons',
        'The Light Brigade',
        'High Seas Fleet'
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $games = [
            Game::find('6c28e221-2bed-4b79-8847-e21aa640dbf9'),
            Game::find('0dd7c0fa-d049-46c9-bf31-1cdc819663f9')
        ];
        foreach($games as $game) {
            $players = $game->players;
            // the players that are not userId 1,2,3
            $players = $players->filter(function($player) {
                return !in_array($player['user_id'], [1,2,3]);
            })->values();
            // the fleets of the rest of the players
            foreach($players as $player) {
                $star = $player->stars->first();
                for ($i = 0; $i < 3; $i++) {
                    Fleet::create([
                        'game_id' => $game->id,
                        'player_id' => $player->id,
                        'star_id' => $star->id,
                        'name' => $this->fleetNames[random_int(0, count($this->fleetNames) - 1)],
                    ]);
                }
            }
        }
    }

}
