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

    const INTERNAL_ADMIN_NAME = 'superAdmin';
    const ADMIN_NAME = 'admin';
    const TEACHER_NAME = 'teacher';
    const STUDENT_NAME = 'student';

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
