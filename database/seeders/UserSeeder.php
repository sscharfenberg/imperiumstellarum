<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'email' => 'ash@imperiumstellarum.io',
            'email_verified_at' => Carbon::now(),
            'locale' => 'de',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'game_mail_optin' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'email' => 'ally@gmail.com',
            'email_verified_at' => Carbon::now(),
            'locale' => 'de',
            'role' => 'user',
            'password' => Hash::make('password'),
            'game_mail_optin' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('users')->insert([
            'id' => 3,
            'email' => 'enemy@gmail.com',
            'email_verified_at' => Carbon::now(),
            'locale' => 'de',
            'role' => 'user',
            'password' => Hash::make('password'),
            'game_mail_optin' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);


        User::factory()
            ->times(7)
            ->create();
    }
}
