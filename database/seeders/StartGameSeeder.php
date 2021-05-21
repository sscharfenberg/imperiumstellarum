<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Turn;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Actions\Game\StartGame;

class StartGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {

        $games = [
            Game::find('6c28e221-2bed-4b79-8847-e21aa640dbf9')
        ];
        $s = new StartGame;

        foreach($games as $game) {
            // create initial turn
            Turn::create([
                'game_id' => $game->id,
                'number' => 0,
                'due' => Carbon::now()->addMinutes($game->turn_duration)
            ]);
            $s->seedPlayerStars($game);
        }

    }
}
