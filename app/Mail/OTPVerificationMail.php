<?php

namespace App\Mail;
use App\Mail\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OTPVerificationMail extends Mailable
{
    public $name;
    public $email;
    public $otp;

    public function __construct(string $name, string $email, int $otp)
    {
        $this->name = $name;
        $this->email = $email;
        $this->otp = $otp;
    }


    public function build()
    {
        return $this->view('email.otp', [
        'name' => $this->name,
        'email' => $this->email,
        'otp' => $this->otp,
    ]);

    }

        public function getEmail()
    {
        return $this->email;
    }

}

