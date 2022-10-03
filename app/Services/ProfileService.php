<?php

namespace App\Services;

use App\Models\Profile;

class ProfileService {
    
    public function setGeneral(array $generalData)
    {
        return [
            'preferred_name' => $generalData['preferred_name'],
            'gender' => $generalData['gender'],
            'dob' => $generalData['dob'],
            'mobile' => $generalData['mobile'],
            'address' => $generalData['address']
        ];
    }
}