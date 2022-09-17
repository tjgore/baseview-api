<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $roles = Role::ROLES;
        array_walk($roles, function($roleName, $key) {
            return Role::updateOrCreate(
                ['name' => $key],
                ['nice_name' => $roleName]
            );
        });
    }
}
