<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'store']);
