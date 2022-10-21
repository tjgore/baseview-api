<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;
use \Illuminate\Support\Collection;

class ProfilePolicy
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

    /**
     * If user is an internal admin give them full access
     *
     * @param User $user
     * @return void
     */
    public function before(User $user)
    {
        if ($user->isInternalAdmin()) {
            return true;
        }
    }

    /**
     * Determine if user can get all account based on user role and filters provided
     *
     * @param User $user
     * @return void
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
