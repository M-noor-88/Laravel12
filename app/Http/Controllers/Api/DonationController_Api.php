<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Donation;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Stripe\Stripe;


    class DonationController_Api extends Controller
    {

        public function checkout(Request $request)
        {
            Log::info('checkout method called');
            $request->validate([
                'amount' => 'required|numeric|min:1',
                'campaign_id' => 'nullable|exists:campaigns,id',
            ]);

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
                return response()->json([
                    'success' => true,
                    'message' => 'PaymentIntent created successfully.',
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_intent_id' => $paymentIntent->id,
                ]);

            } catch (\Exception $e) {
                Log::error('Stripe PaymentIntent creation failed: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Could not initiate donation payment.',
                    'details' => $e->getMessage()
                ], 500);
            }
        }
    }
