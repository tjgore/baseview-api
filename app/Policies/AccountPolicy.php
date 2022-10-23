<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use App\Models\School;
use Illuminate\Auth\Access\HandlesAuthorization;
use \Illuminate\Support\Collection;

class AccountPolicy extends BasePolicy
{
    use HandlesAuthorization;

    const ALL = 'all';
    const MY = 'my';

    const FILTERS = [
        Role::ADMIN => [
            [
                'role' => Role::TEACHER_NAME,
                'limit' => [self::ALL]
            ],
            [
                'role' => Role::STUDENT_NAME,
                'limit' => [self::ALL]
            ],
            [
                'role' => Role::ADMIN_NAME,
                'limit' => [self::ALL]
            ],
        ],
        Role::TEACHER => [
            [
                'role' => Role::TEACHER_NAME,
                'limit' => [self::ALL]
            ],
            [
                'role' => Role::STUDENT_NAME,
                'limit' => [self::ALL, self::MY]
            ],
            [
                'role' => Role::ADMIN_NAME,
                'limit' => [self::ALL]
            ],
        ],
        Role::STUDENT => [
            [
                'role' => Role::TEACHER_NAME,
                'limit' => [self::ALL, self::MY]
            ],
        ]
    ];

    const FIND_USER = [
        Role::ADMIN => Role::SCHOOL_ROLES,
        Role::TEACHER => Role::SCHOOL_ROLES,
        // @TODO when this is added we should handle what data a student can see for a teacher, only show name email, job title
        //Role::STUDENT => [Role::TEACHER, Role::ADMIN],
    ];

    /**
     * Determine if user can get all account based on user role and filters provided
     *
     * @param User $user
     * @return boolean
     */
    public function viewAll(User $user)
    {
        $roles = $user->roles()->get();

        return $roles->filter(function ($role) {
            $filters = collect(self::FILTERS[$role->id]);
            return $this->canUse($filters);
        })
        ->isNotEmpty();
    }

    /**
     * Determine if user can view another users account
     *
     * @param User $user
     * @param User $userAccount
     * @param School $school
     * @return boolean
     */
    public function view(User $user, User $userAccount)
    {
        $validRoles = $user->roleIds()->map(function ($roleId) {
            return self::FIND_USER[$roleId];
        })->flatten();

        return $userAccount->roles()->whereIn('id', $validRoles)->exists();
    }


    /**
     * Is the given user filter valid and can it be used.
     *
     * @param Collection $roleFilters
     * @return boolean
     */
    protected function canUse(Collection $roleFilters) : bool
    {
        return $roleFilters->filter(function ($filter) {
            $hasValidRole = $filter['role'] === request()->query('role');
            $hasValidLimit = in_array(request()->query('limit'), $filter['limit']);

            return $hasValidRole && $hasValidLimit;
        })
        ->isNotEmpty();
    }
}
