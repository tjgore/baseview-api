<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function get()
    {
        $userProfile = request()->user()
            ->with(['profile', 'roles'])
            ->first();

        return response()->json($userProfile);    
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'preferred_name' => 'string|required',
            'gender' => 'string|required',
            'dob' => 'required|date_format:Y/m/d',
            'address' => 'string|required',
            'mobile' => 'string|nullable',
            'job_title' => 'string|nullable',
        ]);

        $profile = $request->user()->profile;

        $profile->general = $validated;

        $profile->save();
    }
}
