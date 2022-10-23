<?php

namespace App\Policies;

use App\Models\User;

class BasePolicy {
    
    /**
     * If user is an internal admin give them full access
     *
     * @param User $user
     * @return void
     */
    public function before(User $user)
    {
        if ($user->isInternalAdmin()) {
            return true;
        }
    }
}