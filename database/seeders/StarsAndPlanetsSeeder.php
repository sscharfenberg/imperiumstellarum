<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Star;
use App\Services\SeedGameService;
use Illuminate\Database\Seeder;

class StarsAndPlanetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws \Exception
     * @return void
     */
    public function run()
    {
        $s = new SeedGameService;

        $games = [
            Game::find('6c28e221-2bed-4b79-8847-e21aa640dbf9'),
            Game::find('0dd7c0fa-d049-46c9-bf31-1cdc819663f9')
        ];

        foreach($games as $game) {
            $s->seedStars($game);
            $s->seedPlanets($game);
            $game->map = null;
            $game->save();
        }

    }
}
