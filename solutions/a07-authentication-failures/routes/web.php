<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'store'])
    ->middleware('throttle:login');
