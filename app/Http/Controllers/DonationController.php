<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Campaign;


class DonationController extends Controller
{
    public function showPaymentForm($campaignId)
{
    $campaign = Campaign::findOrFail($campaignId);

    return view('donation_form', [
        'campaign' => $campaign,
        'stripeKey' => config('services.stripe.key')
    ]);
}

public function createPaymentIntent(Request $request, $campaignId)
{
    $campaign = Campaign::findOrFail($campaignId);

    Stripe::setApiKey(config('services.stripe.secret'));

    $paymentIntent = PaymentIntent::create([
        'amount' => $request->amount * 100, // in cents
        'currency' => 'usd',
        'payment_method_types' => ['card'],
        'transfer_data' => [
            'destination' => $campaign->stripe_account_id,
        ],
    ]);

    return response()->json([
        'clientSecret' => $paymentIntent->client_secret,
    ]);
}

}



