<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\School;
use App\Models\Role;
use Database\Seeders\RoleSeeder;

class SchoolTest extends TestCase
{
    /**
     * Test a user can create a school
     *
     * @return void
     */
    public function test_admin_user_can_create_school()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::ADMIN);

        $schoolInput = School::factory()->make();

        $response = $this->actingAs($user)
        ->postJson('/api/schools/create', $schoolInput->toArray());

        $hasSchool = School::where('email', $schoolInput->email)->exists();

        $this->assertTrue($hasSchool);

        $response->assertCreated()->assertJson([
            'message' => 'ok',
        ]);
    }
}
