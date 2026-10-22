# A10 Mishandling of Exceptional Conditions

> **Intentionally insecure.** The files under `code/` are deliberately vulnerable training material. Never run or deploy them.

## Scenario

Northwind Staffing (an invented company) has a report export that only some staff may use. Permissions are looked up in a separate internal permissions service. Last month that service was down for an hour, and during that hour an intern downloaded a report with salary data. Support also forwarded a screenshot from an angry customer: their browser showed a block of text with file paths and class names after a failed request.

## Task

Read the access checker and the application bootstrap file. Work out what the system does when things go wrong, not when they go right. Change the files until the checklist is true. Make sure ordinary errors such as "not found" still return the right status.

## Level and time

Quick, about 5 minutes.

## Fix checklist

- [ ] The access check denies access when it cannot reach a decision.
- [ ] The failure is logged, without secrets, so someone can see it happened.
- [ ] Users get a generic error message without the exception message or a stack trace.
- [ ] The generic response keeps correct HTTP status codes (404, 403 and 422 are not turned into 500).

## Stretch

The permissions service is down: how would you notice, who is told, and what should users see? Add a correlation id idea that lets support find the matching log entry from a user's report.

## Files

- `code/app/Services/AccessChecker.php` - decides whether a user may export reports.
- `code/bootstrap/app.php` - application setup, including how exceptions are turned into responses.
