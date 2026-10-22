# A08 Software or Data Integrity Failures

> **Intentionally insecure.** The files under `code/` are deliberately vulnerable training material. Never run or deploy them.

## Scenario

Northwind Staffing (an invented company) lets employees save their display preferences from a mobile app, and receives payment status updates from its payment provider through a webhook. The mobile app sends preferences as an encoded blob, which the server decodes and stores. The payment webhook is a public URL. A tester posted a hand-written "paid" message to the webhook and an unpaid invoice changed status, and a second tester sent a crafted preferences blob that made the server behave oddly.

## Task

Read the three files under `code/`. Decide which data the server is trusting without any proof that it is intact and genuine, and change the files until the checklist is true. You may assume a config value for the webhook secret exists under `services.payments.webhook_secret`.

## Level and time

Stretch, about 10 minutes.

## Fix checklist

- [ ] No `unserialize()` is called on data that comes from a request.
- [ ] Preferences are validated structured data (an array or JSON with known keys and values).
- [ ] The webhook signature is verified with an HMAC over the raw request body before the payload is used.
- [ ] The signature is compared with a timing-safe function.
- [ ] The webhook payload is validated after the signature has been verified.

## Stretch

Add replay protection: a signed timestamp header, and reject messages older than five minutes. Describe what an attacker could still do if only the signature were checked.

## Files

- `code/routes/api.php` - declares the preferences and webhook routes.
- `code/app/Http/Controllers/PreferencesController.php` - decodes and stores user preferences.
- `code/app/Http/Controllers/WebhookController.php` - applies payment status updates from the provider.
