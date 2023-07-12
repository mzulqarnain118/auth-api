<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class User extends Mailable
{
    public $name;
    public $email;

    public function __construct(User $user)
    {
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function build()
    {
        return $this->view('email.user');
    }
}
