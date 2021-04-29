<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Player;
use App\Models\Star;
use Illuminate\Database\Seeder;

class WinnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $games = [
            Game::find('6c28e221-2bed-4b79-8847-e21aa640dbf9'),
            Game::find('0dd7c0fa-d049-46c9-bf31-1cdc819663f9')
        ];

        foreach($games as $game) {

            $ash = Player::where('game_id', '=', $game->id)
                ->where('user_id', '=', 1)
                ->first();
            $stars = Star::where('game_id', '=', $game->id)
                ->where('player_id', '=', $ash->id)
                ->get();
            $planets = collect();
            $stars->each( function($star) use (&$planets) {
                $planets = $planets->concat($star->planets);
            });
            $planets = $planets->filter(function($planet) {
                return $planet->population > 0;
            });
            $planets->each(function ($planet) {
                $planet->population = 61;
                $planet->save();
            });

        }

    }
}
