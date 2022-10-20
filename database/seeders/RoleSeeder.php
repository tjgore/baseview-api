<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;


class RoleSeeder extends Seeder
{
    const ROLE_ID_NAMES = [
        Role::INTERNAL_ADMIN => [
            'name' => Role::INTERNAL_ADMIN_NAME,
            'nice_name' => 'Internal Admin'
        ],
        Role::ADMIN => [
            'name' => Role::ADMIN_NAME,
            'nice_name' => 'Admin'
        ],
        Role::TEACHER => [
            'name' => Role::TEACHER_NAME,
            'nice_name' => 'Teacher'
        ],
        Role::STUDENT => [
            'name' => Role::STUDENT_NAME,
            'nice_name' => 'Student'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        foreach(self::ROLE_ID_NAMES as $id => $role) {
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
