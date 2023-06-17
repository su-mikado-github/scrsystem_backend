<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateStaffMail extends Mailable {
    use Queueable, SerializesModels;

    private $user;

    private $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $password)
    {
        //
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->text('mail.create_staff')
            ->subject(__('messages.subject.create_staff'))
            ->with('user', $this->user)
            ->with('password', $this->password)
        ;
    }
}
