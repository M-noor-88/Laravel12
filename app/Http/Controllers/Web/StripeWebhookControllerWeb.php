<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use App\Models\Donation;
use App\Models\Campaign;
use \Stripe\Exception\SignatureVerificationException;

class StripeWebhookControllerWeb extends Controller
{
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        if (empty($sigHeader))
            return response()->json(['error' => 'Missing signature header'], 400);

        if (empty($endpointSecret))
            return response()->json(['error' => 'Webhook secret not configured'], 500);

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            Log::info('Stripe webhook received: ' . $event->type);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }
        Log::info('Stripe webhook received: '. $event->type);

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;
            $campaignId = $intent->metadata->campaign_id ?? null;
            $amount = $intent->amount / 100;

            if ($campaignId) {
                $campaign = Campaign::find($campaignId);
                if ($campaign) {
                    $campaign->current_amount += $amount;
                    $campaign->save();

                    Donation::create([
                        'campaign_id' => $campaign->id,
                        'amount' => $amount,
                        'payment_status' => 'paid',
                        'payment_intent_id' => $intent->id,
                    ]);
                }
            }
        }

        Log::info('Stripe webhook processed: '. $event->type);
        //  Always return a 200 response if the event is successfully handled
        return response()->json([
            'message' => 'Webhook received successfully',
            'status' => 'success']
        );
    }


}


