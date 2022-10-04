<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = RoleSeeder::class;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_school_roles_only()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/roles');

        $responseRoles = $response->collect();

        $schoolRoles = Role::whereIn('id', Role::SCHOOL_ROLES)->get()->toArray();

        $this->assertEquals($responseRoles, collect($schoolRoles));

        $response->assertStatus(200);
    }
}
