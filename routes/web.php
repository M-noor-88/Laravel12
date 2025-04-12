<?php

use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/1', function () {
    return view('donation_form');
});


Route::get('/campaigns/{id}/donate', [DonationController::class, 'showPaymentForm'])->name('donation.form');
Route::post('/campaigns/{id}/intent', [DonationController::class, 'createPaymentIntent'])->name('donation.intent');

