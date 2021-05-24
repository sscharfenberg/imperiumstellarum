<?php

namespace Database\Seeders;

use App\Models\Fleet;
use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerRelation;
use App\Models\Ship;

use App\Services\ShipService;

use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Str;

class EncounterSeeder extends Seeder
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
     * @param Game $game
     * @param Player $player
     * @param Fleet $fleet
     * @param string $className
     * @throws \Exception
     * @return void
     */
    private function createArk (Game $game, Player $player, Fleet $fleet, string $className)
    {
        $s = new ShipService;
        Ship::create([
            'game_id' => $game->id,
            'player_id' => $player->id,
            'fleet_id' => $fleet->id,
            'hull_type' => 'ark',
            'name' => $s->randomShipName(),
            'class_name' => $className,
            'hp_shields_max' => 50,
            'hp_shields_current' => 50,
            'hp_armour_max' => 50,
            'hp_armour_current' => 50,
            'hp_structure_max' => 50,
            'hp_structure_current' => 50,
            'colony' => true,
            'ftl' => true,
            'acceleration' => 1
        ]);
    }

    /**
     * @param Game $game
     * @param Player $player
     * @param Fleet $fleet
     * @param string $className
     * @throws \Exception
     * @return void
     */
    private function createDestroyer (Game $game, Player $player, Fleet $fleet, string $className)
    {
        $s = new ShipService;
        Ship::create([
            'game_id' => $game->id,
            'player_id' => $player->id,
            'fleet_id' => $fleet->id,
            'hull_type' => 'small',
            'name' => $s->randomShipName(),
            'class_name' => $className,
            'dmg_railgun' => 14,
            'hp_shields_max' => 100,
            'hp_shields_current' => 100,
            'hp_armour_max' => 100,
            'hp_armour_current' => 100,
            'hp_structure_max' => 100,
            'hp_structure_current' => 100,
            'ftl' => true,
            'acceleration' => 8
        ]);
    }

    /**
     * @param Game $game
     * @param Player $player
     * @param Fleet $fleet
     * @param string $className
     * @throws \Exception
     * @return void
     */
    private function createCruiser (Game $game, Player $player, Fleet $fleet, string $className)
    {
        $s = new ShipService;
        Ship::create([
            'game_id' => $game->id,
            'player_id' => $player->id,
            'fleet_id' => $fleet->id,
            'hull_type' => 'medium',
            'name' => $s->randomShipName(),
            'class_name' => $className,
            'dmg_railgun' => 27,
            'hp_shields_max' => 150,
            'hp_shields_current' => 150,
            'hp_armour_max' => 150,
            'hp_armour_current' => 150,
            'hp_structure_max' => 150,
            'hp_structure_current' => 150,
            'ftl' => true,
            'acceleration' => 7
        ]);
    }


    /**
     * Run the database seeds.
     * @throws Exception
     * @return void
     */
    public function run()
    {

        $games = [
            Game::find('6c28e221-2bed-4b79-8847-e21aa640dbf9'),
            //Game::find('0dd7c0fa-d049-46c9-bf31-1cdc819663f9')
        ];

        // in each game, do-
        foreach($games as $game) {
            $ash = Player::where('game_id', '=', $game->id)
                ->where('user_id', '=', '1')->first();
            $ally = Player::where('game_id', '=', $game->id)
                ->where('user_id', '=', '2')->first();
            $enemy = Player::where('game_id', '=', $game->id)
                ->where('user_id', '=', '3')->first();

            /**
             * encounter star
             */
            $encounterStar = $ash->stars->first();


            /**
             * Player Relations
             */
            // ash|ally <> hostile
            PlayerRelation::create([
                'game_id' => $game->id,
                'player_id' => $ash->id,
                'recipient_id' => $enemy->id,
                'status' => 0,
            ]);
            PlayerRelation::create([
                'game_id' => $game->id,
                'player_id' => $enemy->id,
                'recipient_id' => $ally->id,
                'status' => 0,
            ]);
            // ash <> ally: allied
            PlayerRelation::create([
                'game_id' => $game->id,
                'player_id' => $ash->id,
                'recipient_id' => $ally->id,
                'status' => 2,
            ]);
            PlayerRelation::create([
                'game_id' => $game->id,
                'player_id' => $ally->id,
                'recipient_id' => $ash->id,
                'status' => 2,
            ]);

            /**
             * Main test account fleets and ships
             */

            for ($i = 0; $i < 3; $i++) {
                Fleet::create([
                    'game_id' => $game->id,
                    'player_id' => $ash->id,
                    'star_id' => $encounterStar->id,
                    'name' => $this->fleetNames[random_int(0, count($this->fleetNames) - 1)],
                ]);
            }
            $fleets = $ash->fleets;
            foreach($fleets as $index => $fleet) {
                // 2 arks for first fleet
                if ($index === 0) {
                    $className = 'A-'.strtoupper(Str::random(3));
                    for ($i = 0; $i < 2; $i++) {
                        $this->createArk($game, $ash, $fleet, $className);
                    }
                }
                // 10 destroyers for second fleet
                if ($index === 1) {
                    $className = 'DD-'.strtoupper(Str::random(3));
                    for ($i = 0; $i < 10; $i++) {
                        $this->createDestroyer($game, $ash, $fleet, $className);
                    }
                }
                // 4 cruisers, 6 destroyers for third fleet
                if ($index === 2) {
                    $className = 'CG-'.strtoupper(Str::random(3));
                    for ($i = 0; $i < 4; $i++) {
                        $this->createCruiser($game, $ash, $fleet, $className);
                    }
                    $className = 'DD-'.strtoupper(Str::random(3));
                    for ($i = 0; $i < 6; $i++) {
                        $this->createDestroyer($game, $ash, $fleet, $className);
                    }
                }
            }

            /**
             * allied player fleets and ships
             */

            for ($i = 0; $i < 3; $i++) {
                Fleet::create([
                    'game_id' => $game->id,
                    'player_id' => $ally->id,
                    'star_id' => $encounterStar->id,
                    'name' => $this->fleetNames[random_int(0, count($this->fleetNames) - 1)],
                ]);
            }
            $fleets = $ally->fleets;
            foreach($fleets as $index => $fleet) {
                // 2 arks for first fleet
                if ($index === 0) {
                    $className = 'A-'.strtoupper(Str::random(3));
                    for ($i = 0; $i < 2; $i++) {
                        $this->createArk($game, $ally, $fleet, $className);
                    }
                }
                // 10 destroyers for second fleet
                if ($index === 1) {
                    $className = 'DD-'.strtoupper(Str::random(3));
                    for ($i = 0; $i < 10; $i++) {
                        $this->createDestroyer($game, $ally, $fleet, $className);
                    }
                }
                // 4 cruisers, 6 destroyers for third fleet
                if ($index === 2) {
                    $className = 'CG-'.strtoupper(Str::random(3));
                    for ($i = 0; $i < 4; $i++) {
                        $this->createCruiser($game, $ally, $fleet, $className);
                    }
                    $className = 'DD-'.strtoupper(Str::random(3));
                    for ($i = 0; $i < 6; $i++) {
                        $this->createDestroyer($game, $ally, $fleet, $className);
                    }
                }
            }

            /**
             * hostile player fleets and ships
             */

            for ($i = 0; $i < 3; $i++) {
                Fleet::create([
                    'game_id' => $game->id,
                    'player_id' => $enemy->id,
                    'star_id' => $encounterStar->id,
                    'name' => $this->fleetNames[random_int(0, count($this->fleetNames) - 1)],
                ]);
            }
            $fleets = $enemy->fleets;
            foreach($fleets as $index => $fleet) {
                // 2 arks for first fleet
                if ($index === 0) {
                    $className = 'A-'.strtoupper(Str::random(3));
                    for ($i = 0; $i < 2; $i++) {
                        $this->createArk($game, $enemy, $fleet, $className);
                    }
                }
                // 10 destroyers for second fleet
                if ($index === 1) {
                    $className = 'DD-'.strtoupper(Str::random(3));
                    for ($i = 0; $i < 10; $i++) {
                        $this->createDestroyer($game, $enemy, $fleet, $className);
                    }
                }
                // 4 cruisers, 6 destroyers for third fleet
                if ($index === 2) {
                    $className = 'CG-'.strtoupper(Str::random(3));
                    for ($i = 0; $i < 4; $i++) {
                        $this->createCruiser($game, $enemy, $fleet, $className);
                    }
                    $className = 'DD-'.strtoupper(Str::random(3));
                    for ($i = 0; $i < 6; $i++) {
                        $this->createDestroyer($game, $enemy, $fleet, $className);
                    }
                }
            }


        }



    }
}
