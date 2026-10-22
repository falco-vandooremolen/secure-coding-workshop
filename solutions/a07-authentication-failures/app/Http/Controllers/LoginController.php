<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $key = Str::lower($credentials['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            abort(429);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            RateLimiter::clear($key);

            return redirect('/dashboard');
        }

        RateLimiter::hit($key, 60);

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }
}
