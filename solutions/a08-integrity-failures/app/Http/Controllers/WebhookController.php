<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $expected = hash_hmac('sha256', $request->getContent(), config('services.payments.webhook_secret'));

        if (! hash_equals($expected, (string) $request->header('X-Signature'))) {
            abort(401);
        }

        $event = $request->validate([
            'reference' => 'required|string|max:64',
            'status' => 'required|in:paid,failed',
        ]);

        Payment::where('reference', $event['reference'])->update(['status' => $event['status']]);

        return response()->noContent();
    }
}
