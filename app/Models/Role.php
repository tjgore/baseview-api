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

    const ROLE_ID_NAMES = [
        self::INTERNAL_ADMIN => [
            'name' => 'superAdmin',
            'nice_name' => 'Internal Admin'
        ],
        self::ADMIN => [
            'name' => 'admin',
            'nice_name' => 'Admin'
        ],
        self::TEACHER => [
            'name' => 'teacher',
            'nice_name' => 'Teacher'
        ],
        self::STUDENT => [
            'name' => 'student',
            'nice_name' => 'Student'
        ]
    ];

    const ALL = [
        self::INTERNAL_ADMIN, 
        self::ADMIN,
        self::TEACHER,
        self::STUDENT
    ];

    const SCHOOL_ROLES = [self::ADMIN, self::TEACHER, self::STUDENT];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeSchoolRoles($query)
    {
        $query->whereIn('id', self::SCHOOL_ROLES);
    }
}
