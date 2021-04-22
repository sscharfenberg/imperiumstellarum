<?php

namespace Database\Seeders;

use App\Models\Fleet;
use App\Models\Game;
use App\Models\Player;
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

            $rest = $players->filter(function($player) {
                return !in_array($player['user_id'], [1,2,3]);
            })->values();

            foreach($rest as $player) {
                $rest = $game->players->reject(function($p) use ($player) {
                    return $p->id === $player->id;
                });
                for ($i = 0; $i < 4; $i++) {
                    $recipient = $rest->random();
                    PlayerRelation::create([
                        'game_id' => $game->id,
                        'player_id' => $player->id,
                        'recipient_id' => $recipient->id,
                        'status' => random_int(0, 2),
                    ]);
                    $rest = $rest->reject(function ($p) use ($recipient) {
                        return $p->id === $recipient->id;
                    });
                }
            }

        }

    }
}
