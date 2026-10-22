<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/documents/{id}', [DocumentController::class, 'show']);
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);
});
