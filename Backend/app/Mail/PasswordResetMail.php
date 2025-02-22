<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $newPassword;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $newPassword
     */
    public function __construct(User $user, string $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }

    public function build()
    {
        return $this->subject('Your Password has been Reset')
                    ->view('emails.password_reset')  // Ensure this view exists
                    ->with([
                        'name' => $this->user->email,
                        'newPassword' => $this->newPassword,
                    ]);
    }
}

