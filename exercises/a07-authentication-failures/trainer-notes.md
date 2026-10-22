# Trainer notes: A07 Authentication Failures

## Why it matters

Login is the front door. Without throttling, attackers can try leaked passwords from other breaches against every account (credential stuffing) at machine speed. Without a session regeneration step, an attacker who plants a known session identifier before login (session fixation) inherits the victim's authenticated session. Both need very little skill.

## OWASP category

OWASP Top 10:2025 A07 Authentication Failures (weak brute-force protection, session management flaws).

## Common wrong fixes

- Rate limiting by IP address only. Attackers rotate addresses, and shared office networks get locked out together.
- Adding a CAPTCHA and nothing else.
- Regenerating the CSRF token (`regenerateToken()`) instead of the session identifier.
- Counting attempts but never clearing the counter, so a legitimate user is eventually blocked for good.
- Rate limiting only on the route middleware and ignoring the per-account dimension.

## Discussion questions

- What is session fixation, and why does `Auth::login()` alone not prevent it here?
- Credential stuffing versus brute force: which controls help against each, and what does multi-factor authentication add?
- Account lockout can become a denial-of-service tool against colleagues. How would you design around that?
- The message is already generic. What other signals (response time, status code, registration or reset forms) could still reveal that an account exists?
