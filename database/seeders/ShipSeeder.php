<?php

namespace Database\Seeders;

use App\Models\Fleet;
use App\Models\Game;
use App\Models\Player;
use App\Models\Ship;
use App\Services\ShipService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShipSeeder extends Seeder
{

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
        $s = new ShipService;
        foreach($games as $game) {

            // players with userId !== 1,2,3
            $players = $game->players;
            // uncomment the next three lines when you want to use the detailed seeding in EncounterSeeder*
            //$players = $players->filter(function($player) {
            //    return !in_array($player['user_id'], [1,2,3]);
            //})->values();

            foreach($players as $player) {
                foreach($player->fleets as $index => $fleet) {

                    // 2 arks for first fleet
                    if ($index === 0) {
                        $className = 'A-'.strtoupper(Str::random(3));
                        for ($i = 0; $i < 2; $i++) {
                            $this->createArk($game, $player, $fleet, $className);
                        }
                    }

                    // 10 destroyers for second fleet
                    if ($index === 1) {
                        $className = 'DD-'.strtoupper(Str::random(3));
                        for ($i = 0; $i < 10; $i++) {
                            $this->createDestroyer($game, $player, $fleet, $className);
                        }
                    }

                    // 4 cruisers, 6 destroyers for third fleet
                    if ($index === 2) {
                        $className = 'CG-'.strtoupper(Str::random(3));
                        for ($i = 0; $i < 4; $i++) {
                            $this->createCruiser($game, $player, $fleet, $className);
                        }
                        $className = 'DD-'.strtoupper(Str::random(3));
                        for ($i = 0; $i < 6; $i++) {
                            $this->createDestroyer($game, $player, $fleet, $className);
                        }
                    }

                }
            }
        }


    }
}
