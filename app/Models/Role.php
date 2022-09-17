<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const INTERNAL_ADMIN = 1;
    const ADMIN_ID = 2;
    const TEACHER = 3;
    const STUDENT = 4;

    const ROLES = [
        'superAdmin' => 'Internal Admin',
        'admin'     => 'Admin',
        'teacher'   => 'Teacher',
        'student'   => 'Student' 
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
