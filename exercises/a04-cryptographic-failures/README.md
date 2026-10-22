# A04 Cryptographic Failures

> **Intentionally insecure.** The files under `code/` are deliberately vulnerable training material. Never run or deploy them.

## Scenario

Northwind Staffing (an invented company) lets new employees register on the intranet and connects to an external payments service for expense reimbursements. A penetration tester received a copy of the repository for a code review and sent back a short message: "I did not need to hack anything."

## Task

Read the registration controller and the two config files. Find every place where sensitive data is protected badly or not at all, and change the code until the checklist is true. All keys in this repository are invented placeholders.

## Level and time

Quick, about 5 minutes.

## Fix checklist

- [ ] Passwords are stored with Laravel's `Hash` facade, not with a general-purpose digest function.
- [ ] The payments API key is read from the environment, not written in the code.
- [ ] The application key is read from the environment, not written in the code.
- [ ] No secret value remains in any committed file after your change.

## Stretch

Besides changing the code, what must happen to a key that was ever committed? Write down the steps.

## Files

- `code/app/Http/Controllers/RegisterController.php` - creates a user account from a registration request.
- `code/config/services.php` - settings for third-party services, including the payments provider.
- `code/config/app.php` - core application settings, including the encryption key and cipher.
