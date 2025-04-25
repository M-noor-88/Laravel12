<?php

namespace App\Mail;

use Google\Service\AppHub\Scope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GoogleAuthEmail extends Mailable
{
    use SerializesModels;

    public $email;
    public $authUrl;

    /**
     * Create a new message instance.
     *
     * @param string $email
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;


        $this->authUrl = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query([
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
            'prompt' => 'consent'
        ]);
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Mail\Mailable
     */
    public function build()
    {
        return $this->view('google_auth')
                    ->subject('Authenticate with Google')
                    ->with([
                        'email' => $this->email,
                        'authUrl' => $this->authUrl,
                    ]);
    }
}
