<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentCallbackController;

// URL: https://w3site.id/api/paymenku-callback
Route::post('/paymenku-callback', [PaymentCallbackController::class, 'callback']);
