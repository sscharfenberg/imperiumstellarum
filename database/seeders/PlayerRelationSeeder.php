<?php

namespace Database\Seeders;

use App\Models\Fleet;
use App\Models\Game;
use App\Models\PlayerRelation;
use Illuminate\Database\Seeder;

class PlayerRelationSeeder extends Seeder
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
            Game::find('6c28e221-2bed-4b79-8847-e21aa640dbf9'),
            Game::find('0dd7c0fa-d049-46c9-bf31-1cdc819663f9')
        ];

        foreach($games as $game) {
            $players = $game->players;

            foreach($players as $player) {
                $star = $player->stars->first();
                $players = $game->players->reject(function($p) use ($player) {
                    return $p->id === $player->id;
                });

                for ($i = 0; $i < 4; $i++) {
                    $recipient = $players->random();
                    PlayerRelation::create([
                        'game_id' => $game->id,
                        'player_id' => $player->id,
                        'recipient_id' => $recipient->id,
                        'status' => random_int(0, 2),
                    ]);
                    $players = $players->reject(function ($p) use ($recipient) {
                        return $p->id === $recipient->id;
                    });
                }
            }

        }

    }
}
