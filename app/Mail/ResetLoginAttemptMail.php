<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetLoginAttemptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetUrl;

    public function __construct($user)
    {
        $this->user = $user;
        $this->resetUrl = route('admin.reset.login.attempt', ['email' => $user->email]);
    }

    public function build()
    {
        return $this->subject('Reset Your Login Attempts')

            ->view('admin.reset-link')
            ->with([
                'name' => $this->user->name,
                'resetUrl' => $this->resetUrl,
            ]);
    }
}
