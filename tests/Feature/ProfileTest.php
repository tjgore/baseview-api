<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Database\Seeders\RoleSeeder;


class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = RoleSeeder::class;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_get_user_profile()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::ADMIN);
        $profile = $user->profile;

        $response = $this->actingAs($user)->getJson('/api/profiles');

        $this->assertEquals($user->email, $response['email']);
        $this->assertEquals(Role::ADMIN, $response['roles'][0]['id']); $this->assertEquals($profile->user_id, $response['profile']['user_id']);
        $response->assertStatus(200);
    }

    public function test_can_update_my_profile()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::ADMIN);

        $data = [
            'first_name' => 'Henry',
            'last_name' => 'Joseph',
            'email' => 'henry@example.com',
            'preferred_name' => 'HJ',
            'gender' => 'Male',
            'dob' => '2000-10-05',
            'address' => 'St.Johns',
            'mobile' => '',
            'job_title' => '',
        ];

        $response = $this->actingAs($user)->putJson('/api/profiles', $data);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => $data['first_name'],
            'email' => $data['email'],
        ]);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'general->preferred_name' => $data['preferred_name'],
        ]);

        $response->assertCreated();

    } 
}
