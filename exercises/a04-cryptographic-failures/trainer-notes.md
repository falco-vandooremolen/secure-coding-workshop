# Trainer notes: A04 Cryptographic Failures

## Why it matters

Unsalted fast hashes such as MD5 are cracked in seconds with public tables and a consumer GPU, so a database leak turns directly into reusable passwords. Secrets committed to a repository are copied to every clone, backup and CI cache, and stay in history after deletion. The application key protects sessions and encrypted data, so leaking it breaks both.

## OWASP category

OWASP Top 10:2025 A04 Cryptographic Failures.

## Common wrong fixes

- Switching to `sha256` or `sha1`. Still a fast digest, still wrong for passwords.
- Base64-encoding the key. That is encoding, not protection.
- Moving the secret to another committed file (a different config file or a `.env` that is not ignored).
- Removing the secret from the code but never rotating it.

## Discussion questions

- Why does rotation matter even after the secret is removed from the repository history?
- What makes a password hash algorithm good (slow, salted, adjustable cost) and why does Laravel hide those details behind `Hash::make`?
- Where else do secrets leak: logs, error pages, CI output, chat messages?
- Who can read the production `.env` today, and should they be able to?
