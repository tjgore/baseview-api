<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class UserInvited extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invite;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invite)
    {
        $this->invite = $invite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $inviteLink = sprintf("%s/%s/%s", 
            config('app.frontend_url'), 
            'invite',
            $this->invite->token,
        );
                
        return $this
            ->subject("{$this->invite->school->name} Invite")
            ->view('emails.user-invited')->with([
                'inviteLink' => $inviteLink, 
            ]);
    }
}
