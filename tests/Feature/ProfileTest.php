<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
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
        $profile = Profile::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->getJson('/api/profiles');
        $response->dump();
        dd('done');

        $this->assertEquals($user->email, $response['email']);
        $this->assertEquals(Role::ADMIN, $response['roles'][0]['id']); $this->assertEquals($profile->user_id, $response['profile']['user_id']);
        $response->assertStatus(200);
    }
}
