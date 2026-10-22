<?php

use App\Http\Controllers\PreferencesController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/preferences', [PreferencesController::class, 'update'])->middleware('auth:sanctum');

// No session or token: the sender proves itself with an HMAC signature, checked in the controller.
Route::post('/webhooks/payments', [WebhookController::class, 'handle'])->middleware('throttle:60,1');
