<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Campaign;
use \Stripe\Exception\SignatureVerificationException;


class DonationControllerWeb extends Controller
{

    public function showCampaigns()
    {
        $campaigns = Campaign::whereNotNull('stripe_account_id')->get();
        return view('campaigns.index', compact('campaigns'));
    }



    public function checkout(Request $request)
    {
        Log::info('checkout method called');
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'campaign_id' => 'nullable|exists:campaigns,id',
        ]);

        $campagin=Campaign::find($request->campaign_id);

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $request->amount * 100, // amount in cents
                'currency' => 'usd',
                'description' => 'Donation for campaign',
                'metadata' => [
                    'campaign_id' => $request->campaign_id ?? 'none',
                    'user_id' => $request->user()->id ?? 'guest',
                ],
            ]);
            return view('donation.confirm', [
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
                'amount' => $request->amount,
                'campaign' => $campagin,
                'stripeKey' => config('services.stripe.key'),

            ]);
        } catch (\Exception $e) {
            Log::error('Stripe error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Stripe error: ' . $e->getMessage()]);
        }
    }
}
