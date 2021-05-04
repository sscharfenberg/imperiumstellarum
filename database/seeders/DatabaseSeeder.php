<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $start = hrtime(true);
        $this->call([
            UserSeeder::class,
            //SuspensionSeeder::class
            GameSeeder::class,
            StarsAndPlanetsSeeder::class,
            PlayerSeeder::class,
            StartGameSeeder::class,
            FleetSeeder::class,
            PlayerRelationSeeder::class,
            ShipSeeder::class,
            /**
             * => use either EncounterSeeder (attacker gets repelled) or EncounterSeederKillPlayer (Attacker successful)
             * if you do not use any EncounterSeeder, make sure the $players->filter function (in ShipSeeder and FleetSeeder)
             * is commented out, otherwise userid 1,2,3 will not get any fleets/ships
             * if you use either EncounterSeeder, uncomment $players->filter function.
             */
            //EncounterSeeder::class,
            //EncounterSeederKillPlayer::class,
            /**
             * => use WinnerSeeder to simulate a game that ends once the turn is calculated.
             */
            WinnerSeeder::class
        ]);
        $execution = hrtime(true) - $start;
        echo("Database seeding took \033[92m".$execution/1e+9."\033[39m seconds.\n\n");
    }
}
