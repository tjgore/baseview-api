<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\ProfileService;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Get authenticated users profile.
     *
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        $userProfile = User::with(['profile', 'roles'])->where('id', request()->user()->id)->first();

        return response()->json($userProfile);    
    }

    /**
     * Create a new profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'preferred_name' => 'string|required',
            'gender' => ['required', 'string', Rule::in(['Male', 'Female', 'Neither'])],
            'dob' => 'required|date_format:Y-m-d',
            'address' => 'string|required',
            'mobile' => 'string|nullable',
            'job_title' => 'string|nullable',
        ]);

        $profile = new Profile;

        $profile->user_id = $request->user()->id;
        $profile->general = app(ProfileService::class)->setGeneral($validated);

        $profile->save();

        return $this->ok(204);
    }

    /**
     * Update an existing profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'preferred_name' => 'string|required',
            'gender' => ['required', 'string', Rule::in(['Male', 'Female', 'Neither'])],
            'dob' => 'required|date_format:Y-m-d',
            'address' => 'string|required',
            'mobile' => 'string|nullable',
            'job_title' => 'string|nullable',
        ]);

        $user = $request->user();

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];

        $user->save();

        $profile = $user->profile;

        if (!$profile) {
            $profile = new Profile;
            $profile->user_id = $user->id;
        }

        $profile->general = app(ProfileService::class)->setGeneral($validated);

        $profile->save();

        return $this->ok(201);
    }
}
