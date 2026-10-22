<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            Log::channel('security')->info('login.succeeded', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
            ]);

            return redirect('/dashboard');
        }

        Log::channel('security')->warning('login.failed', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }
}
