<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResetCodePassword>
 */
class ResetCodePasswordFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->word(),
            'token' => $this->faker->word(),
            'expires_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
