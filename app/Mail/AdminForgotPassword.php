<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminForgotPassword extends Mailable
{
    use Queueable, SerializesModels;
  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(protected array $detail)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $sub = "Admin Password reset link !";
        return $this->from('keith@gmail.com', 'KASP')
            ->subject($sub)
            ->view('admin.forgot-password-mail',  [
                'details' => $this->detail
            ]);
    }
}
