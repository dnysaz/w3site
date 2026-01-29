<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransCallbackController;

/*
|--------------------------------------------------------------------------
| Midtrans Webhook Route
|--------------------------------------------------------------------------
| URL: https://w3site.id/api/midtrans-callback
*/
Route::post('/midtrans-callback', [MidtransCallbackController::class, 'handle']);