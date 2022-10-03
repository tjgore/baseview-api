<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invite;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\School;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvited;

class InviteController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'string|nullable',
            'email' => 'string|email|required',
            'school' => 'required|integer|exists:schools,id',
            'role' => [ 'required', 'integer', Rule::in(Role::SCHOOL_ROLES)],
        ]);

        $school = School::find($request->school);
        Gate::authorize('school', $school);

        $invite = new Invite;
        $invite->first_name =  $request->first_name;
        $invite->last_name = $request->last_name;
        $invite->email = $request->email;
        $invite->school_id = $school->id;
        $invite->role_id = $request->role;
        $invite->token = Str::uuid();
        $invite->created_by_id = $request->user()->id;
        $invite->expires_at = now()->addDay();
        $invite->save();

        Mail::to($invite->email)->send(new UserInvited($invite));

        return $this->ok(201);
    }

    public function findByToken(string $token)
    {
        $invite = Invite::where('token', $token)
            ->where('accepted', false)
            ->where('expires_at', '>', now())
            ->first();

        return response()->json($invite);
    }
}
