<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invite;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\School;
use Illuminate\Support\Facades\Gate;


class InviteController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'string|nullable',
            'email' => 'string|email|required',
            'school_id' => 'required|integer|exists:schools,id',
            'role_id' => [ 'required', 'integer', Rule::in(Role::SCHOOL_ROLES)],
        ]);

        $school = School::find($request->school_id);
        Gate::authorize('school', $school);

        $invite = new Invite;
        $invite->first_name =  $request->first_name;
        $invite->last_name = $request->last_name;
        $invite->email = $request->email;
        $invite->school_id = $school->id;
        $invite->role_id = $request->role_id;
        $invite->token = Str::uuid();
        $invite->created_by_id = $request->user()->id;
        $invite->expires_at = now()->addDay();
        $invite->save();

        return $this->ok(201);
    }
}
