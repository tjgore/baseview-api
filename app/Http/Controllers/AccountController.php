<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Profile;
use Illuminate\Support\Facades\Gate;
use App\Models\School;
use App\Models\Role;
use App\Models\User;
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

        $accounts = DB::table('users')
            ->join('school_user', 'users.id', 'school_user.user_id')
            ->join('role_user', 'users.id', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', 'roles.id')
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->where('school_user.school_id', $school->id)
            ->where('roles.name', $request->role)
            ->when($search, function ($query, $search) {
                $query->where('users.first_name', 'like', "%$search%")
                ->orWhere('users.email', 'like', "%$search%");
            })
            ->get([
                'users.first_name',
                'users.last_name',
                'users.email',
                'roles.nice_name'
            ]);

        return response()->json($accounts);
    }
}
