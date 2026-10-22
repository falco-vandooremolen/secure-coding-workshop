<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $event = $request->json()->all();

        Payment::where('reference', $event['reference'])->update(['status' => $event['status']]);

        return response()->noContent();
    }
}
