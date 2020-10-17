<?php

namespace Database\Factories;

use App\Models\Suspension;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SuspensionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Suspension::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {
        return [
            'user_id' => random_int(2,1534),
            'until' => Carbon::now()->addDays(random_int(1,30)),
            'issuer_id' => random_int(1,9),
            'issuer_reason' => Str::random(15),
        ];
    }
}
