<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Invite;


class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password' => 'required|alpha_num|min:8|confirmed',
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'invite_id' => 'nullable|integer|exists:invites,id',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $this->addUserToSchool($request->invite_id, $user);

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }

    /**
     * Add User to a school
     *
     * @param integer $inviteId
     * @param User $user
     * @return void
     */
    private function addUserToSchool(int $inviteId, User $user)
    {
        if (!$inviteId) {
            return;  
        }

        $invite = Invite::where('id', $inviteId)->where('accepted', false)->first();
        $user->roles()->attach($invite->role_id);
        $user->schools()->attach($invite->school_id);
    }
}
