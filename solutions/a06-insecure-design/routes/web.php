<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->middleware(['auth', 'throttle:10,1']);
