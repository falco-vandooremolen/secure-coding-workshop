# A08 Software or Data Integrity Failures

> **Intentionally insecure.** The files under `code/` are deliberately vulnerable training material. Never run or deploy them.

## Scenario

Northwind Staffing (an invented company) lets employees save their display preferences from a mobile app, and receives payment status updates from its payment provider through a webhook. The mobile app sends preferences as an encoded blob, which the server decodes and stores. The payment webhook is a public URL. A tester posted a hand-written "paid" message to the webhook and an unpaid invoice changed status, and a second tester sent a crafted preferences blob that made the server behave oddly.

## Task

Read the three files under `code/`. Decide which data the server is trusting without any proof that it is intact and genuine, and change the files until the checklist is true. You may assume a config value for the webhook secret exists under `services.payments.webhook_secret`.

## Level and time

Stretch, about 10 minutes.

## Fix checklist

- [ ] The server never rebuilds objects from data that comes from a request.
- [ ] Preferences are validated structured data with known keys and values.
- [ ] The webhook only acts on messages that are proven to come from the payment provider and to be unmodified.
- [ ] That proof is checked in a way that does not leak information through response timing.
- [ ] The webhook content is validated only after its origin has been proven.

## Stretch

Add replay protection so a captured valid message cannot be resent later. Describe what an attacker could still do if only the signature were checked.

## Files

- `code/routes/api.php` - declares the preferences and webhook routes.
- `code/app/Http/Controllers/PreferencesController.php` - decodes and stores user preferences.
- `code/app/Http/Controllers/WebhookController.php` - applies payment status updates from the provider.
