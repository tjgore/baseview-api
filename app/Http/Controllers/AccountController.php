<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Services\ProfileService;
use App\Models\School;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Services\InviteService;


class AccountController extends Controller
{
    /**
     * Get all accounts for a school based on role, limit, and search filters
     *
     * @param School $school
     * @param Request $request
     * @return void
     */
    public function getAll(School $school, Request $request) :JsonResponse
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'limit' => ['required', Rule::in(['all', 'my'])],
            'search' => 'nullable|string'
        ]);
        $search = $request->search;

        $accounts = $school->users()
            ->join('role_user', 'users.id', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', 'roles.id')
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->whereNull('users.deleted_at')
            ->where('school_user.school_id', $school->id)
            ->where('roles.name', $request->role)
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('users.first_name', 'like', "%$search%")
                        ->orWhere('users.email', 'like', "%$search%");
                });
            })
            ->select([
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                'roles.nice_name as role'
            ])->paginate(self::TOTAL_RESULTS);

        return response()->json($accounts);
    }


    /**
     * Create an account - user with profile with pending invite
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(School $school, Request $request) :JsonResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'preferred_name' => 'string|required',
            'gender' => ['required', 'string', Rule::in(Profile::GENDER)],
            'dob' => 'required|date_format:Y-m-d',
            'address' => 'string|required',
            'mobile' => 'string|nullable',
            'job_title' => 'string|nullable',
            'roles' => 'required|array',
            'roles.*' => [ 'required', 'integer', Rule::in(Role::SCHOOL_ROLES)],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        $user->profile()->create([
            'general' => app(ProfileService::class)->setGeneral($validated),
        ]);

        $user->roles()->attach($request->roles);
        $user->schools()->attach($school->id);

        app(InviteService::class)->createInvite([
            ...$validated,
            'user_id' => $user->id,
            'school_id' => $school->id,
        ]);

        return response()->json($user);
    }

    /**
     * Find account by id
     *
     * @param User $user
     * @return void
     */
    public function find(School $school, User $user)
    {
        $account = User::with(['profile', 'roles'])->firstWhere('id', $user->id);

        return response()->json(app(ProfileService::class)->format($account));
    }
}
