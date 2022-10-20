<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\School;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class InviteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'school_id' => School::factory(),
            'role_id' => fake()->numberBetween(2, 4),
            'created_by_id' => User::factory(),
            'token' => Str::uuid(),
            'expires_at' =>  now()->addDay(),
            'accepted' => false,
        ];
    }
}
