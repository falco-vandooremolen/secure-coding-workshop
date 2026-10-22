<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|in:light,dark',
            'locale' => 'required|in:en,nl',
        ]);

        // The model casts `preferences` to JSON/array; no unserialize() anywhere.
        $request->user()->update(['preferences' => $data]);

        return response()->noContent();
    }
}
