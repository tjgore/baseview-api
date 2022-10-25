<?php

namespace App\Services;

use App\Models\Invite;
use Illuminate\Support\Str;
use App\Mail\UserInvited;
use Illuminate\Support\Facades\Mail;


class InviteService {
    
    /**
     * Create an invite for the given data
     *
     * @param array $inviteData
     * @return void
     */
    public function createInvite(array $inviteData)
    {
        $invite = new Invite;

        $invite->first_name = $inviteData['first_name'];
        $invite->last_name = $inviteData['last_name'];
        $invite->email = $inviteData['email'];
        $invite->school_id = $inviteData['school_id'];
        $invite->role_id = $inviteData['role'] ?? $inviteData['roles'][0]; // @TODO this will eventually always be an array, right now we accept integers too
        $invite->token = Str::uuid();
        $invite->created_by_id = $inviteData['user_id'];
        $invite->expires_at = now()->addDay();

        $invite->save();

        // @TODO Maybe convert to an event in model any time invite is created send email.
        Mail::to($invite->email)->send(new UserInvited($invite));
    }
}