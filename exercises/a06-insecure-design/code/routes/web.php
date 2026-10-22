<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::post('/checkout', [CheckoutController::class, 'store']);
