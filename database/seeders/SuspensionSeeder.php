<?php

namespace Database\Seeders;

use App\Models\Suspension;
use Illuminate\Database\Seeder;

class SuspensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Suspension::factory()
            ->times(2500)
            ->create();
    }
}
