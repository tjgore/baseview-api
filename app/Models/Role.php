<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const INTERNAL_ADMIN = 1;
    const ADMIN = 2;
    const TEACHER = 3;
    const STUDENT = 4;

    const ROLES = [
        'superAdmin' => 'Internal Admin',
        'admin'     => 'Admin',
        'teacher'   => 'Teacher',
        'student'   => 'Student' 
    ];

    const SCHOOL_ROLES = [self::ADMIN, self::TEACHER, self::STUDENT];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeSchoolRoles($query)
    {
        $query->whereIn('id', self::SCHOOL_ROLES);
    }
}
