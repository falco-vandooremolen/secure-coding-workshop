<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $user = User::create([
            'email' => $request->input('email'),
            'password' => md5($request->input('password')),
        ]);

        return response()->json(['id' => $user->id], 201);
    }
}
