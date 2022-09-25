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
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_belonging_to_school_can_create_invite()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::ADMIN);

        // user create a school
        $schoolInput = School::factory()->make();

        $response = $this->actingAs($user)
        ->postJson('/api/schools/create', $schoolInput->toArray());

        $school = School::where('email', $schoolInput->email)->first();

        $response->assertCreated();

        // create invite
        $inviteInput = Invite::factory()->make([
            'school_id' => $school->id,
            'role_id' => Role::TEACHER
        ]);
        
        $response = $this->actingAs($user)->postJson('/api/invites', $inviteInput->toArray());

        $response->assertStatus(201);
    }

    public function test_user_not_at_school_can_not_create_invite()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $school = School::factory()->create();
        $user->roles()->attach(Role::ADMIN);

        $inviteInput = Invite::factory()->make([
            'school_id' => $school->id,
            'role_id' => Role::TEACHER
        ]);
        
        $response = $this->actingAs($user)->postJson('/api/invites', $inviteInput->toArray());

        $hasInvite = Invite::where('school_id', $school->id)->exists();

        $this->assertFalse($hasInvite);

        $response->assertStatus(403);
    }
}
