<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invite;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\School;
use App\Models\Profile;
use App\Services\InviteService;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\ProfileService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;


class InviteController extends Controller
{
    /**
     * Create an account from invite 
     *
     * @param Invite $invite
     * @param Request $request
     * @return JsonResponse
     */
    public function createAccount(Invite $invite, Request $request) :JsonResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191'],
            'password' => 'required|alpha_num|min:8|confirmed',
            'preferred_name' => 'string|required',
            'gender' => ['required', 'string', Rule::in(Profile::GENDER)],
            'dob' => 'required|date_format:Y-m-d',
            'address' => 'string|required',
            'mobile' => 'string|nullable',
            'job_title' => 'string|nullable',
        ]);

        $userExists = User::where('email', $request->email)->whereNull('password')->exists();

        $user = User::updateOrCreate(
            ['email' => $request->email],
            [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            ]
        );

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['general' => app(ProfileService::class)->setGeneral($validated)]
        );

        if (!$userExists) {
            $user->addToSchool($invite->school_id, $invite->role_id);
        }

        $invite = $this->acceptInvitation($invite);

        event(new Registered($user));

        Auth::login($user);

        return response()->json($user);

        return $this->ok();
    }

    /**
     * Create a new user invite
     *
     * @param School $school
     * @param Request $request
     * @return JsonResponse
     */
    public function create(School $school, Request $request) :JsonResponse
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

    /**
     * Find invite by token
     *
     * @param Invite $invite
     * @return JsonResponse
     */
    public function find(Invite $invite) :JsonResponse
    {  // return response()->json($invite);

        $user = User::where('email', $invite->email)->first();

        $inviteData = $user->exists() ? 
        app(ProfileService::class)->format($user) : 
        [
            'first_name' => $invite->first_name,
            'last_name' => $invite->last_name,
            'email' => $invite->email,
        ];

        return response()->json($inviteData);
    }

    /**
     * Accept user invitation
     *
     * @param Invite $invite
     * @return Invite
     */
    private function acceptInvitation(Invite $invite) :Invite
    {
        $invite->accepted = true;
        $invite->save();

        return $invite;
    }
}
