<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\School;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;


class RoleController extends Controller
{
    public function all()
    {
        return response()->json(Role::schoolRoles()->get());
    }

    /**
     * Update the role for a user
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(School $school, User $user, Request $request) :JsonResponse
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => [ 'required', 'integer', Rule::in([Role::ADMIN, Role::TEACHER])],
        ]);

        $user->roles()->sync($request->roles);

        return $this->ok(204);
    }
}
