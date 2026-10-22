# Solution notes: A08 Software or Data Integrity Failures

- `PreferencesController` no longer calls `unserialize()`. It validates known keys (`theme`, `locale`) with `in:` allow-lists and stores the validated array, which the model should cast to JSON. Unknown keys are dropped because only `$data` is stored.
- `WebhookController` computes `hash_hmac('sha256', $request->getContent(), ...)` over the raw body, compares it with the `X-Signature` header using `hash_equals()`, and aborts with 401 before reading the payload. Only after that are `reference` and `status` validated (`status` must be `paid` or `failed`).
- `services.payments.webhook_secret` must come from the environment (for example `'webhook_secret' => env('PAYMENTS_WEBHOOK_SECRET')` in `config/services.php`), never from a literal committed in the repository.
- The webhook route has no auth middleware on purpose, because the provider cannot log in. The HMAC is the authentication. A `throttle` middleware is an extra layer.
- Partial credit: `unserialize(..., ['allowed_classes' => false])` removes the object-injection risk but the design stays wrong.

Stretch: have the provider include a timestamp header covered by the signature (sign `timestamp . '.' . body`), and reject messages whose timestamp is more than five minutes old. Optionally store seen message ids to reject exact replays. Without it, an attacker who captures one valid message can resend it forever.
