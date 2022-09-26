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
        foreach(Role::ROLE_ID_NAMES as $id => $role) {
            Role::updateOrCreate(
                ['id' => $id],
                [
                    'name' => $role['name'],
                    'nice_name' => $role['nice_name']
                ]
            );
        }
    }
}
