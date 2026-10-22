<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => ['required', Password::min(12)],
        ]);

        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json(['id' => $user->id], 201);
    }
}
