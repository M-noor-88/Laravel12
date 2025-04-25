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
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $this->client->addScope(Gmail::GMAIL_SEND);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    public function sendEmail(Request $request)
    {
        $authUrl = $this->client->createAuthUrl();
        return response()->json(['auth_url' => $authUrl]);
    }


    public function handleCallback(Request $request)
    {

        Log::info($request->all());
        if (! request()->has('code')||! request()->has('email')) {
            return response()->json(['error' => 'Authorization code not provided'], 400);
        }


        $token= $this->client->fetchAccessTokenWithAuthCode(request('code'));
        Log::info($token);


        $this->client->setAccessToken($token);

        $gmail= new Gmail($this->client);

        $to= $request->input('email');
        $subject= 'Google Authentication test';
        $body= 'this is a test message';


        // Construct raw email
        $rawMessageString = "To: $to\r\n";
        $rawMessageString .= "Subject: $subject\r\n";
        $rawMessageString .= "MIME-Version: 1.0\r\n";
        $rawMessageString .= "Content-Type: text/plain; charset=utf-8\r\n\r\n";
        $rawMessageString .= $body;


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
