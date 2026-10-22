# Solution notes: A10 Mishandling of Exceptional Conditions

- `AccessChecker::canExport()` now fails closed: on any `\Throwable` it logs `permission.check_failed` with the user id and the exception class (not the message, which can hold secrets) and returns `false`. A silent `return false` without the log is only half a fix, because the outage would go unnoticed.
- The exception renderer in `bootstrap/app.php` returns `{"error": "Something went wrong."}` with status 500 for unexpected exceptions. It returns `null` for HTTP exceptions, validation, authentication, authorisation and model-not-found errors so Laravel's normal handling keeps 404, 403, 422 and 401 intact.
- Laravel already reports (logs) unhandled exceptions, so the closure does not need to log them again; it only has to stop leaking details. Call `report($e)` only for exceptions that you handle yourself.
- Keep `APP_DEBUG=false` in production (see A02) as a second layer; it is not a replacement for a safe handler.
- Verify the `withExceptions` API and the exception classes in the current Laravel 13 documentation before reusing this.

Stretch: monitor the `permission.check_failed` log entry and alert when it appears repeatedly, which signals a dependency outage. Add a request id to responses and log context so support can match a user's report with the entry.
