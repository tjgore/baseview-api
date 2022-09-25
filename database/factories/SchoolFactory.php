<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->city(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'principal' => fake()->name(),
            'vice_principal' => fake()->name(),
            'about' => fake()->sentences(3, true),
            'slogan' => fake()->catchPhrase()
        ];
    }
}
