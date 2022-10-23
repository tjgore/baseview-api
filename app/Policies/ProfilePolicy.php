<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use App\Models\Profile;
use App\Models\School;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy extends BasePolicy
{
    use HandlesAuthorization;

    const UPDATE_USER = [
        Role::ADMIN => [Role::ADMIN, Role::TEACHER, Role::STUDENT],
        Role::TEACHER => [Role::STUDENT],
    ];

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Profile  $profile
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Profile $profile, School $school)
    {
        $updatingUser = $profile->user;
        
        $updateRoles = $user->roleIds()->map(function ($roleId) {
            return self::UPDATE_USER[$roleId];
        })->flatten();

        $permittedRoles = $updatingUser->roles()->whereIn('id', $updateRoles)->exists();

        return $user->id === $profile->user_id || $permittedRoles;
    }
}
