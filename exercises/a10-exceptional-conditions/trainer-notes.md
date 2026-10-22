# Trainer notes: A10 Mishandling of Exceptional Conditions

## Why it matters

Attackers do not need to break a control if they can make it fail. A dependency outage, a timeout or malformed input should lead to a safe outcome, not to access. Verbose error output gives attackers a free map of the system: file paths, class names, framework versions and sometimes query text or secrets. This is a quick exercise: the two fixes are small, so use the remaining time for discussion.

## OWASP category

OWASP Top 10:2025 A10 Mishandling of Exceptional Conditions (failing open, information disclosure through error handling, errors not logged).

## Common wrong fixes

- Rethrowing the exception but leaving the trace in the response body.
- Hiding details only when `APP_DEBUG` is false and leaving the handler otherwise unchanged.
- An empty `catch` that returns `false` without logging, which fails closed but silently, so the outage is never noticed.
- Replacing every exception, including 404, 403 and 422, with a 500 and breaking clients and monitoring.
- Logging the full exception message, which may contain credentials or query values.

## Discussion questions

- Fail open versus fail closed: when is each acceptable, and who decides? What about a feature where blocking users has a high business cost?
- How would you alert on a dependency outage like the permissions service, and who should be told?
- How can a correlation or request id help support find the right log entry without exposing details to the user?
- What other places swallow exceptions in a typical Laravel code base (queued jobs, event listeners, scheduled tasks)?
