<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function update(Request $request)
    {
        $preferences = unserialize(base64_decode($request->input('prefs')));

        $request->user()->update(['preferences' => $preferences]);

        return response()->noContent();
    }
}
