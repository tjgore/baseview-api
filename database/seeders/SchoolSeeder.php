<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\User;
use App\Models\Role;

class SchoolSeeder extends Seeder
{
    const TOTAL_SCHOOLS = 2;

    // Totals per school
    const TOTAL_USERS = 50;
    const TOTAL_ADMINS = 5;
    const TOTAL_TEACHERS = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        School::factory()->count(self::TOTAL_SCHOOLS)
            ->create()
            ->each(function ($school) {
                $users = $this->createUsersWithRole();

                $userIds = $users->pluck('id')->toArray();
                $school->users()->attach($userIds);
            });
    }

    private function getRole($total)
    {
        if ($total <= self::TOTAL_ADMINS) {
            return Role::ADMIN;
        } else if ($total <= self::TOTAL_TEACHERS) {
            return Role::TEACHER;
        } else {
            return Role::STUDENT;
        }
    }

    private function createUsersWithRole()
    {
        return User::factory()->count(self::TOTAL_USERS)
            ->create()
            ->each(function ($user, $key) {
                $user->roles()->attach($this->getRole($key + 1));
            });
    }
}
