<?php

namespace App\Http\Controllers\api;

use Google_Client;
use Google_Service_Oauth2;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\GoogleAuthEmail;
use Google\Service\Gmail;
use Google\Client;
use Google\Service\Gmail\Message;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\error;

class GoogleAuthController extends Controller
{
     private $client;


     public function __construct()
     {
         $this->client = new Client();
         $this->client->setClientId(config('services.google.client_id'));
         $this->client->setClientSecret(config('services.google.client_secret'));
         $this->client->setRedirectUri(config('services.google.redirect_uri'));
         $this->client->addScope(Gmail::GMAIL_SEND);
         $this->client->setAccessType('offline');
         $this->client->setPrompt('consent');
     }

    public function sendEmail(Request $request)
    {

        Log::info($request->all());

        $token = json_decode(env('GOOGLE_ACCESS_TOKEN'), true);
        $this->client->setAccessToken($token);
        Log::info($token);

          // Automatically refresh if expired
    if ($this->client->isAccessTokenExpired()) {
        $newToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
        $token = array_merge($token, $newToken);
        $this->client->setAccessToken($token);

        // Optionally log new token
        Log::info('Refreshed token', $token);
    }


        $gmail= new Gmail($this->client);

        $from = 'zed.kreshati.2001@gmail.com'; // your authorized sender (must match the authenticated Gmail account)
        $to= $request->input('email');
        $subject= 'Google Authentication test';
        // ✅ Render Blade view
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $htmlBody = view('google_auth', ['verificationCode' => $verificationCode])->render();



        // Construct raw email
        $rawMessageString  = "From: $from\r\n";
        $rawMessageString .= "To: $to\r\n";
        $rawMessageString .= "Subject: $subject\r\n";
        $rawMessageString .= "MIME-Version: 1.0\r\n";
        $rawMessageString .= "Content-Type: text/html; charset=utf-8\r\n\r\n";
        $rawMessageString .= $htmlBody;


        $rawMessage=base64_encode($rawMessageString);
        $rawMessage= str_replace(['+', '/', '='], ['-', '_', ''], $rawMessage);

        $message= new Message();
        $message->setRaw($rawMessage);


        try {
            $gmail->users_messages->send('me', $message);
            return response()->json(['message' => 'Email sent successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
        }

    }
}
