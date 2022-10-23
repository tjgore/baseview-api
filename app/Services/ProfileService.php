<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\User;

class ProfileService {
    
    /**
     * Set profile general data
     *
     * @param array $generalData
     * @return array
     */
    public function setGeneral(array $generalData) :array
    {
        return [
            'preferred_name' => $generalData['preferred_name'],
            'gender' => $generalData['gender'],
            'dob' => $generalData['dob'],
            'mobile' => $generalData['mobile'],
            'address' => $generalData['address']
        ];
    }

    /**
     * Update Profile - user model and profile model
     *
     * @param array $requestData
     * @param User $user
     * @return void
     */
    public function update(array $requestData, User $user) :void
    {
        $user->first_name = $requestData['first_name'];
        $user->last_name = $requestData['last_name'];
        $user->email = $requestData['email'];

        $user->save();

        $profile = $user->profile;

        $this->createNew($profile, $user);

        $profile->general = $this->setGeneral($requestData);

        $profile->save();
    }

    /**
     * Create a new profile model if the given does not exists.
     *
     * @param Profile|null $profile
     * @param User $user
     * @return Profile
     */
    public function createNew(Profile $profile = null, User $user) :Profile
    {
        if ($profile) {
            return $profile;
        }

        $profile = new Profile;
        $profile->user_id = $user->id;

        return $profile;
    }

    /**
     * Format profile response data
     *
     * @param User $profile
     * @return array
     */
    public function format(User $user) :array
    {  
        $profile = $user->profile;
        $general = $profile->general;
        $roles = $user->roles;

        return [
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => "$user->first_name $user->last_name", 
            'email' => $user->email,
            'profile_id' => $profile->id,
            'dob' => $general['dob'],
            'gender' => $general['gender'],
            'mobile' => $general['mobile'],
            'address' => $general['address'],
            'preferred_name' => $general['preferred_name'],
            'created_at' => $user->created_at,
            'roles' => [
                'ids' => $roles->pluck('id'),
                'nice_name' => $roles->pluck('nice_name'),
            ]
        ];
    }
}