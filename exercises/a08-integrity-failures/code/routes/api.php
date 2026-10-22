<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

use App\Http\Controllers\PreferencesController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/preferences', [PreferencesController::class, 'update'])->middleware('auth:sanctum');
Route::post('/webhooks/payments', [WebhookController::class, 'handle']);
