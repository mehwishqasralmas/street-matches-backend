<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use App\Models\User as UserModel;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserModel $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.auth.password.forget', [
          'userFullName' => $this->user->first_name . $this->user->last_name,
          'token' => $this->token
        ]);
    }
}
