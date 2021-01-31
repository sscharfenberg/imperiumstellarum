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
            ShipSeeder::class
        ]);
        $execution = hrtime(true) - $start;
        echo("Database seeding took \033[92m".$execution/1e+9."\033[39m seconds.\n\n");
    }
}
