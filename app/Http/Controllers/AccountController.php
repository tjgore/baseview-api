<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Gate;
use App\Models\School;
use Illuminate\Validation\Rule;


class AccountController extends Controller
{
    public function getAll(School $school, Request $request)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'limit' => ['required', Rule::in(['all', 'my'])],
            'search' => 'nullable|string'
        ]);
        $search = $request->search;

        Gate::authorize('viewAll', Profile::class);

        $accounts = $school->users()
            ->join('role_user', 'users.id', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', 'roles.id')
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->where('school_user.school_id', $school->id)
            ->where('roles.name', $request->role)
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('users.first_name', 'like', "%$search%")
                        ->orWhere('users.email', 'like', "%$search%");
                });
            })
            ->select([
                'users.first_name',
                'users.last_name',
                'users.email',
                'roles.nice_name as role'
            ])->paginate(self::TOTAL_RESULTS);

        return response()->json($accounts);
    }
}
