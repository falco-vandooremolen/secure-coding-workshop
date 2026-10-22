# A02 Security Misconfiguration

> **Intentionally insecure.** The files under `code/` are deliberately vulnerable training material. Never run or deploy them.

## Scenario

Northwind Staffing (an invented company) is about to launch its intranet at `https://intranet.example.test`. The team copied the example environment file and CORS config from an earlier project and wants a quick pre-launch review. Nobody has looked at the HTTP response headers yet.

## Task

Review the environment file and the CORS config. Then add the missing piece yourself: create `app/Http/Middleware/SecurityHeaders.php` (it does not exist in `code/`) and describe where you would register it. Make every checklist item true.

## Level and time

Quick, about 5 minutes.

## Fix checklist

- [ ] The production environment does not expose debug output or stack traces to visitors.
- [ ] CORS only allows the intranet's real origin, and credentials are not combined with a wildcard origin.
- [ ] A middleware sets at least `X-Content-Type-Options`, `X-Frame-Options` and `Strict-Transport-Security` on responses.
- [ ] The middleware is actually registered so it runs for web requests.

## Stretch

Add a `Content-Security-Policy` and think about what would break in a page that loads scripts from a CDN.

## Files

- `code/.env.example` - example environment file for the production deployment.
- `code/config/cors.php` - cross-origin resource sharing settings for the API routes.
