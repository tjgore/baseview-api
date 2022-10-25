<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use App\Models\Profile;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Services\PermissionService;

class ProfilePolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Profile $profile)
    {
        $requiredPermission = sprintf('update-profile:%s', $profile->user->roleNames()->implode(','));

        $permittedRoles = PermissionService::create($user->roleIds())
            ->profilesPermission($requiredPermission);

        return $user->id === $profile->user_id || $permittedRoles;
    }
}
