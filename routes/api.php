<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoogleAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\DonationController_Api;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\StripeWebhookController_Api;

Route::post('checkout', [DonationController_Api::class, 'checkout']);
Route::get('donations/success', [DonationController_Api::class, 'success']);
Route::post('payment',[StripeWebhookController_Api::class,'createPaymentIntent' ]);
Route::post('stripe/webhook', [StripeWebhookController_Api::class, 'handleWebhook']);


////use App\Http\Controllers\Api\GoogleAuthController;

Route::post('google/send-email', [GoogleAuthController::class, 'sendEmail']);
Route::match(['get', 'post'], '/google/callback', [GoogleAuthController::class, 'handleCallback']);

