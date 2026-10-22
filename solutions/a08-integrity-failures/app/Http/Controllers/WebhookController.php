<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secret = (string) config('services.payments.webhook_secret');

        // Fail closed: never sign with an empty key, because anyone could then forge a valid signature.
        if ($secret === '') {
            Log::critical('webhook.secret_missing');
            abort(500);
        }

        $expected = hash_hmac('sha256', $request->getContent(), $secret);

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
