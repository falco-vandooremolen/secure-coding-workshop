# A09 Security Logging and Alerting Failures

> **Intentionally insecure.** The files under `code/` are deliberately vulnerable training material. Never run or deploy them.

## Scenario

Northwind Staffing (an invented company) found out from a customer, three weeks late, that an attacker had been guessing staff passwords and had reached an admin page. When the team went to the logs to reconstruct what happened they found plenty of noise but little that helped, and one thing that made them very uncomfortable: the log files contained things that should never be written to disk.

## Task

Read the login controller and the admin middleware. Imagine you are the incident responder who has to answer "who tried what, from where, and when?" using only these logs, and imagine you are the person who has to keep the logs safe. Change the files until the checklist is true. A new file is allowed if you think the alerting part needs one.

## Level and time

Medium, about 8 minutes.

## Fix checklist

- [ ] Failed logins are logged with the account identifier and the client IP address (the time comes from the log entry itself).
- [ ] Denied access (HTTP 403) to the admin area is logged with the user (if any), IP address and path.
- [ ] No password or other secret appears in any log line.
- [ ] Repeated failures from the same source raise an alert-level log entry.

## Stretch

Decide who gets notified when the alert fires, how quickly someone has to react, and how long these logs are kept. Write it down in three lines.

## Files

- `code/app/Http/Controllers/LoginController.php` - signs staff in and writes to the log.
- `code/app/Http/Middleware/EnsureIsAdmin.php` - protects the admin area.
