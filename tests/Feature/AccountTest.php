<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\School;
use App\Models\User;
use App\Models\Role;
use Database\Seeders\SchoolSeeder;
use Database\Seeders\RoleSeeder;

class AccountTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_students()
    {
        $this->artisan('config:clear');

        $this->seed([RoleSeeder::class, SchoolSeeder::class]);

        $school = School::first();

        $user = User::factory()->create();

        $user->schools()->attach($school->id);
        
        $user->roles()->attach(Role::INTERNAL_ADMIN);

        $response = $this->actingAs($user)->getJson("/api/schools/{$school->id}/accounts?role=student&limit=all");

        $response->assertJsonFragment(['nice_name' => 'Student']);

        $response->assertStatus(200);
    }
}
