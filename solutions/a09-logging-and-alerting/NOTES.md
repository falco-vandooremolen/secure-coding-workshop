# Solution notes: A09 Security Logging and Alerting Failures

- The login controller no longer logs the credentials array. It writes `login.failed` (warning) with the email, IP and user agent, and `login.succeeded` (info) with the user id and IP, both to a dedicated `security` log channel. The timestamp comes from the log entry. Never add `password` to a context array.
- The `security` channel is defined in `config/logging.php` (this folder): `daily` driver, `storage/logs/security.log`, 90 days of retention via `max_files` (the 13.x skeleton key; older versions use `days`). It is the file your log shipper or alerting tool reads. In production it feeds the alerting tool, so entries with level `critical` page someone.
- `EnsureIsAdmin` logs `access.denied` with the user id (or null), IP and path before it aborts with 403.
- `app/Listeners/AlertOnRepeatedFailures.php` listens for `Illuminate\Auth\Events\Failed`, counts failures per IP in the cache with a 10 minute window and writes a `critical` entry `login.bruteforce_suspected` from the fifth failure. Register it (Laravel discovers listeners in `app/Listeners` by their type-hinted event) or add it explicitly in a service provider.
- Using the email as a context field (not inside the message string) keeps structured logs parseable. Consider stripping newlines from user-supplied values to prevent log injection.
- Checked against the Laravel 13.x docs and skeleton: `Log::channel('name')` and the `daily` driver (logging docs; the 13.x skeleton uses `max_files` for retention), listener auto-discovery by the type-hinted `handle()` method in `app/Listeners` (events docs), and `Illuminate\Auth\Events\Failed` in the authentication events list. In production run `php artisan event:cache` (or `optimize`) so discovery is cached.

Stretch: for example notify the on-call engineer within 15 minutes by chat and email, keep security logs for 12 months in a read-restricted store.
