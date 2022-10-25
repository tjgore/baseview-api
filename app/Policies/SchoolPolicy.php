<?php

namespace App\Policies;

use App\Models\School;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Services\PermissionService;

class SchoolPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, School $school)
    {
        return PermissionService::create($user->roleIds())->checkPermissions('schools', 'view-school');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return PermissionService::create($user->roleIds())->checkPermissions('schools', 'create-school');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, School $school)
    {
        return PermissionService::create($user->roleIds())->checkPermissions('schools', 'update-school');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, School $school)
    {
        return PermissionService::create($user->roleIds())->checkPermissions('schools', 'delete-school');
    }
}
