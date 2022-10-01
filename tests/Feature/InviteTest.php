<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\School;
use App\Models\Invite;
use App\Models\Role;
use Database\Seeders\RoleSeeder;


class InviteTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = RoleSeeder::class;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_belonging_to_school_can_create_invite()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::ADMIN);

        // create a school that belongs to the user
        $school = School::factory()->create();
        $school->users()->attach($user->id);
        

        // create invite
        $inviteInput = Invite::factory()->make([
            'school' => $school->id,
            'role' => Role::TEACHER
        ]);
        
        $response = $this->actingAs($user)->postJson('/api/invites', $inviteInput->toArray());      

        $this->assertDatabaseHas('invites', [
            'school_id' => $school->id,
            'email' => $inviteInput->email,
            'created_by_id' => $user->id
        ]);

        $response->assertCreated();
    }

    public function test_user_not_at_school_can_not_create_invite()
    {
        $user = User::factory()->create();
        $school = School::factory()->create();
        $user->roles()->attach(Role::ADMIN);

        $inviteInput = Invite::factory()->make([
            'school' => $school->id,
            'role' => Role::TEACHER
        ]);
        
        $response = $this->actingAs($user)->postJson('/api/invites', $inviteInput->toArray());

        $this->assertDatabaseMissing('invites', [
            'school_id' => $school->id,
            'email' => $inviteInput->email,
            'created_by_id' => $user->id
        ]);

        $response->assertStatus(403);
    }
}
