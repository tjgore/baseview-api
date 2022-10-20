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
        $canFilter = false;

        $roles = $user->roles()->get();

        $roles->each(function ($role) use ($canFilter) {
            $filters = collect(self::FILTERS[$role->id]);

            if ($canFilter = $this->canUse($filters)) {
                return false;
            }
            
        });

        return $canFilter;
    }

    /**
     * Is the given user filter valid and can it be used.
     *
     * @param Collection $roleFilters
     * @return boolean
     */
    protected function canUse(Collection $roleFilters) : bool
    {
        $validFilter = false;

        $roleFilters->each(function ($filter) use ($validFilter) {
            $hasValidRole = $filter['role'] === request()->query('role');
            $hasValidLimit = in_array(request()->query('limit'), $filter['limit']);

            if ($hasValidRole && $hasValidLimit) {
                $validFilter = true;
                return false;
            }
        });

        return $validFilter;
    }
}
