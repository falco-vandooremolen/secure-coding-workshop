# Trainer notes: A08 Software or Data Integrity Failures

## Why it matters

Integrity failures happen when software trusts code or data without verifying that it is genuine and unmodified. `unserialize()` on user input can instantiate classes of the attacker's choosing, and chains of existing classes ("gadget chains") have led to remote code execution in PHP applications. An unauthenticated webhook lets anyone mark an invoice as paid. This is the Stretch exercise because it has two separate problems and the signature part needs some thought.

## OWASP category

OWASP Top 10:2025 A08 Software or Data Integrity Failures (insecure deserialization, unverified inbound data).

## Common wrong fixes

- Using `unserialize($data, ['allowed_classes' => false])` as the only fix. It is a better partial step, but it still gives a complex format to attackers and does not validate the values. Accept it as partial credit and push toward validated JSON.
- Checking the sender's IP address only. Addresses change, can be spoofed in some setups and ranges are shared.
- Comparing signatures with `==` or `===` instead of `hash_equals()`, which allows timing attacks.
- Computing the HMAC over the decoded payload instead of the raw body, so re-encoding changes the bytes and the check breaks or can be bypassed.
- Validating the payload but never verifying who sent it.
- Forgetting the misconfiguration case: if the webhook secret is unset (empty string), `hash_hmac` still works and the signature is trivially forgeable. The reference solution fails closed (logs and returns 500) when the secret is empty; ask what happens in a fresh environment where the variable was never set.

## Discussion questions

- What is a PHP gadget chain and why does a vulnerable class not need to be in your own code?
- Why must the HMAC be computed over the raw body and compared with `hash_equals()`?
- How would replay protection work, and what would you log when a signature fails?
- Beyond this code: how do you verify the integrity of dependencies, build artefacts and updates you pull in?
