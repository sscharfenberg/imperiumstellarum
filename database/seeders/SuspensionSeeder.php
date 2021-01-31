<?php

namespace Database\Seeders;

use App\Models\Suspension;
use Illuminate\Database\Seeder;

class SuspensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * this seeder is (optionally) used to test suspensions
     *
     * @return void
     */
    public function run()
    {
        Suspension::factory()
            ->times(250)
            ->create();
    }
}
