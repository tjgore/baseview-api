<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $general = [
            'preferred_name' => fake()->name(),
            'gender' => 'Female',
            'dob' => fake()->date('Y-m-d', now()->subYears(10)),
            'mobile' => fake()->phoneNumber(),
            'address' => fake()->city()
        ];

        return [
            'user_id' => 1,
            'general' => $general,
        ];
    }
}
