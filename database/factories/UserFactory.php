<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => Carbon::now()->subDays(random_int(2, 30)),
            'locale' => random_int(0, 1) == 1 ? 'en' : 'de',
            'role' => random_int(0,9) == 9 ? 'mod' : 'user',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'game_mail_optin' => random_int(0,1) == 1,
            'drawer_open' => 1,
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now()->subDays(random_int(2, 30))
        ];
    }
}
