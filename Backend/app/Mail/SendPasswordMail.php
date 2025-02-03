<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

public $name;
public $password;

public function __construct($name, $password)
{
    $this->name = $name;
    $this->password = $password;
}

public function build()
{
    return $this->subject('Your temporary password')
                ->view('emails.send_password');  // La vue avec les variables $name et $password
}
    
}
