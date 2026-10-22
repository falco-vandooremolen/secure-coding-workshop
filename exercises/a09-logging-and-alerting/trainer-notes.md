# Trainer notes: A09 Security Logging and Alerting Failures

## Why it matters

Most breaches are detected late, and often by someone outside the organisation. Without useful security logs there is no way to scope an incident, and without alerting nobody looks at the logs in time. The opposite failure matters too: logging passwords turns the log store (often widely readable and kept for months) into a credentials database.

## OWASP category

OWASP Top 10:2025 A09 Security Logging and Alerting Failures (missing security events, sensitive data in logs, no alerting).

## Common wrong fixes

- Logging the whole request (headers, cookies, body) "to be safe". This logs secrets and drowns the signal.
- Logging a hashed or masked password. Even a hash is sensitive, and it is still useless for incident response.
- Logging every event at every level until nobody can find the interesting ones.
- Adding the log call but not the denial path, so only the happy path is visible.
- Writing alert logic that fires on every single failure, which trains people to ignore it.

## Discussion questions

- Log injection: what happens if the email contains a newline or fake log syntax? How would you structure the log to avoid it?
- How long should security logs be kept, who may read them and how do you protect them?
- Who gets paged when `login.bruteforce_suspected` fires, and what do they do first?
- Which other events deserve a security log entry (password change, role change, export of data)?
