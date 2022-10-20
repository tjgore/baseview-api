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
    use RefreshDatabase;

    protected $seeder = RoleSeeder::class;

    /**
     * Test a user can create a school
     *
     * @return void
     */
    public function test_admin_user_can_create_school()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::INTERNAL_ADMIN);

        $schoolInput = School::factory()->make();

        $response = $this->actingAs($user)
        ->postJson('/api/schools/create', $schoolInput->toArray());

        $this->assertDatabaseHas('schools', [
            'name' => $schoolInput->name,
            'email' => $schoolInput->email,
        ]);

        $response->assertCreated()->assertJson([
            'message' => 'ok',
        ]);
    }

    public function test_admin_user_can_view_their_school()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::ADMIN);

        $school = School::factory()->create();
        $school->users()->attach($user->id);
        
        $response = $this->actingAs($user)->getJson("/api/schools/{$school->id}");

        $response->assertJson($school->toArray());
        $response->assertStatus(200);

    }
}
