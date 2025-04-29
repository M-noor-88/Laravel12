<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\DonationControllerweb;
use App\Http\Controllers\Web\StripeWebhookControllerweb;
use App\Http\Middleware\VerifyCsrfToken;
use Google\AccessToken\Verify;

Route::get('/', function () {
    return view('welcome');
});





Route::get('/campaigns', [DonationControllerweb::class, 'showCampaigns'])->name('campaigns.index');
Route::post('/donate/checkout', [DonationControllerweb::class, 'checkout'])->name('checkout');
Route::post('stripe/webhook', [StripeWebhookControllerweb::class, 'handleWebhook'])->middleware(VerifyCsrfToken::class)->name('stripe.webhook');

Route::get('/donate/success', function () {
    return view('donation.success');
})->name('donation.success');

Route::post('/donate/confirm', [StripeWebhookControllerweb::class, 'confirm']);


