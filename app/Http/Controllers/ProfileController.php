<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\ProfileService;
use App\Models\Profile;
use App\Models\User;
use App\Models\School;
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
     * Update auth user profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $this->validateProfile($request);

        $user = $request->user();

        app(ProfileService::class)->update($validated, $user);

        return $this->ok(201);
    }

    /**
     * Update another user's profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUserProfile(School $school, Profile $profile, Request $request): JsonResponse
    {
        $validated = $this->validateProfile($request);

        app(ProfileService::class)->update($validated, $profile->user);

        return $this->ok(201);
    }

    /**
     * Validate profile request data
     *
     * @param Request $request
     * @return array
     */
    protected function validateProfile(Request $request) :array
    {
        return $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'preferred_name' => 'string|required',
            'gender' => ['required', 'string', Rule::in(Profile::GENDER)],
            'dob' => 'required|date_format:Y-m-d',
            'address' => 'string|required',
            'mobile' => 'string|nullable',
            'job_title' => 'string|nullable',
        ]);
    }
}
