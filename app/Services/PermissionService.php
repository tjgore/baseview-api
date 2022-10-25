<?php

namespace App\Services;

use App\Models\Role;
use \Illuminate\Support\Collection;
use Illuminate\Support\Str;


class PermissionService {

    const ADMIN = [
        'accounts' => [
            'accounts:[role=teacher&limit=all,role=student&limit=all,role=admin&limit=all]',
            'view-account:[admin,student,teacher]',
            'create-account',
            'delete-account:[student,teacher]'
        ],
        'profiles' => [
            'update-profile:[admin,teacher,student]',
        ],
        'schools' => [
            'view-school',
            'delete-school',
            'create-school',
            'update-school'
        ]
    ];

    const TEACHER = [
        'accounts' => [
            'accounts:[role=teacher&limit=all,role=student&limit=all,role=student&limit=my,role=admin&limit=all]',
            'view-account:[admin,teacher,student]',
            'create-account',
            'delete-account:[student]'
        ],
        'profiles' => [
            'update-profile:[student]'
        ],
        'schools' => [
            'view-school'
        ]
    ];

    const STUDENT = [
        'accounts' => [
            'accounts:role=teacher&limit=all',
            'accounts:role=teacher&limit=my',
        ],
        'schools' => [
            'view-school'
        ]
    ];

    const ROLES = [
        Role::ADMIN => self::ADMIN,
        Role::TEACHER => self::TEACHER,
        Role::STUDENT => self::STUDENT
    ];

    protected Collection $roleIds;

    private function __construct(Collection $roleIds)
    {
        $this->roleIds = $roleIds;
    }

    /**
     * Create permission service
     *
     * @param Collection $roleIds
     * @return PermissionService
     */
    public static function create(Collection $roleIds) :PermissionService
    {
        return new self($roleIds);
    }

    /**
     * Check auth user accounts permissions
     *
     * @param Collection $roleIds
     * @param string $requiredPermission
     * @return boolean
     */
    public function accountsPermission(string $requiredPermission) :bool
    {
        return $this->checkPermissions('accounts', $requiredPermission);
    }

    /**
     * Check auth user profiles permissions
     *
     * @param string $requiredPermission
     * @return boolean
     */
    public function profilesPermission(string $requiredPermission) :bool
    {
        return $this->checkPermissions('profiles', $requiredPermission);
    }

    /**
     * Check permission
     *
     * @param string $type
     * @param string $requiredPermission
     * @return boolean
     */
    public function checkPermissions(string $type, string $requiredPermission) :bool
    {
        return $this->roleIds
            ->map(fn ($roleId) => self::ROLES[$roleId][$type])
            ->flatten()
            ->filter(fn ($permission) => $this->isAllowed($permission, $requiredPermission))
            ->isNotEmpty();
    }

    /**
     * Get permission params from permission string
     *
     * @param string $permissions
     * @return array
     */
    private function getParams(string $permissions) :array
    {
        $params = Str::contains($permissions, ':[') ? Str::betweenFirst($permissions, ':[', ']') : Str::after($permissions, ':');

        return explode(",", $params);
    }

    /**
     * The auth user given permission is present and permitted 
     *
     * @param string $permission
     * @param string $requiredPermission
     * @return boolean
     */
    private function isAllowed(string $permission, string $requiredPermission) :bool
    {
        $typesMatch = Str::before($permission, ':') === Str::before($requiredPermission, ':');

        $hasPermission = collect($this->getParams($requiredPermission))
            ->intersect($this->getParams($permission))
            ->isNotEmpty();

        return $permission === $requiredPermission || ($typesMatch && $hasPermission);
    }
}