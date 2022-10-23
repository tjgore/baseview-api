<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invite;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\School;
use App\Services\InviteService;


class InviteController extends Controller
{
    public function create(School $school, Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'string|nullable',
            'email' => 'string|email|required',
            // @TODO add student later
            'role' => [ 'required', 'integer', Rule::in([Role::ADMIN, Role::TEACHER])],
        ]);

        app(InviteService::class)->createInvite([
            ...$validated,
            'user_id' => $request->user()->id,
            'school_id' => $school->id,
        ]);

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
