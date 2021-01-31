<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerResource;
use App\Models\TechLevel;
use App\Models\User;
use App\Services\PlayerDefaultService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;


class PlayerSeeder extends Seeder
{

    /**
     * prepared player names and colours
     * @var array
     */
    private $playerData = [
        [
            'name' => 'Ad Astra Ad Aspera',
            'ticker' => 'AAAA',
            'colour' => 'ff4de4'
        ],
        [
            'name' => 'Fantastic Assembly Rotates Today',
            'ticker' => 'FART',
            'colour' => 'ad9300'
        ],
        [
            'name' => 'No More Mr Nice Guy',
            'ticker' => 'NICE',
            'colour' => '00596b'
        ],
        [
            'name' => 'Ecailles de lune',
            'ticker' => 'LUNE',
            'colour' => 'ff0d39'
        ],
        [
            'name' => 'Ora Pro Nobis Lucifer',
            'ticker' => 'SATAN',
            'colour' => '000000'
        ],
        [
            'name' => 'Am Ende Stirbst Du Allein',
            'ticker' => 'ELEND',
            'colour' => '787378'
        ],
        [
            'name' => 'THE GREAT DYING',
            'ticker' => 'DIE',
            'colour' => '861899'
        ],
        [
            'name' => 'Where dragons dwell',
            'ticker' => 'DRAGO',
            'colour' => 'deff26'
        ],
        [
            'name' => 'Stille skarpe knive',
            'ticker' => 'KNIFE',
            'colour' => 'e63727'
        ],
        [
            'name' => 'CH2OH.(CHOH)4.CHO',
            'ticker' => 'SUGAR',
            'colour' => 'e6b3e0'
        ],
        [
            'name' => 'Armchair Travellers',
            'ticker' => 'CHAIR',
            'colour' => '43e64b'
        ]
    ];



    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $userIds = range(1,11);
        $gameIds = range(1,2);
        $users = User::whereIn('id', $userIds)->get();
        $games = Game::whereIn('number', $gameIds)->get();
        $d = new PlayerDefaultService;

        foreach($games->reverse() as $game) {
            $playerData = collect($this->playerData);
            foreach($users as $user) {
                $player = $playerData->random();
                $playerData = $playerData->reject(function ($value) use ($player) {
                    return $value['ticker'] === $player['ticker'];
                });
                $player = Player::create([
                    'user_id' => $user->id,
                    'game_id' => $game->id,
                    'name' => $player['name'],
                    'ticker' => $player['ticker'],
                    'colour' => $player['colour']
                ]);
                PlayerResource::insert($d->resources($player->id));
                TechLevel::insert($d->techLevels($player->id));
                $user->selected_player = $player->id;
                $user->save();
            }
        }

    }
}
