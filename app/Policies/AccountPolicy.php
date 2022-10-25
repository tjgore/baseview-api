<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use App\Models\School;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Services\PermissionService;

class AccountPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can get all account based on user role and filters provided
     *
     * @param User $user
     * @return boolean
     */
    public function viewAll(User $user)
    {
        $requiredPermission = sprintf('accounts:role=%s&limit=%s',  request()->query('role'), request()->query('limit'));

        return PermissionService::create($user->roleIds())
            ->accountsPermission($requiredPermission);
    }

    /**
     * Determine if user can create a new user account
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user)
    {
        return PermissionService::create($user->roleIds())
            ->accountsPermission('create-account');
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
        $requiredPermission = sprintf('view-account:%s',  $userAccount->roleNames()->implode(','));

        return PermissionService::create($user->roleIds())
            ->accountsPermission($requiredPermission);
    }

    /**
     * Determine if user can delete a user account
     *
     * @param User $user
     * @return boolean
     */
    public function delete(User $user, User $userAccount)
    {
        $requiredPermission = sprintf('delete-account:%s',  $userAccount->roleNames()->implode(','));

        return PermissionService::create($user->roleIds())
            ->accountsPermission($requiredPermission);
    }

}
