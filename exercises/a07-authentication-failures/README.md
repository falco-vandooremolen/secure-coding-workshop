# A07 Authentication Failures

> **Intentionally insecure.** The files under `code/` are deliberately vulnerable training material. Never run or deploy them.

## Scenario

Northwind Staffing (an invented company) has an intranet login form. The security team saw thousands of login attempts overnight against a few staff accounts, using passwords from old public leaks, and one attempt succeeded. A penetration tester also reported that a session cookie set before logging in stays valid after logging in. The error text is already generic, but the login code around it was written in a hurry.

## Task

Read the login route and controller. Work out how an attacker could keep guessing credentials indefinitely, and how a session could be taken over around the moment of login. Change the files until the checklist is true. Keep the login working for normal users.

## Level and time

Medium, about 8 minutes.

## Fix checklist

- [ ] The session identifier is regenerated after a successful login.
- [ ] Failed attempts are counted and limited per account and IP address combination.
- [ ] The limiter is cleared after a successful login.
- [ ] The error message does not reveal whether the email address exists.

## Stretch

Account lockout stops guessing but can also be used to lock out a colleague on purpose. Describe how you would balance lockout, throttling and multi-factor authentication for this intranet.

## Files

- `code/routes/web.php` - declares the login route.
- `code/app/Http/Controllers/LoginController.php` - checks credentials and signs the user in.
