# A02 Security Misconfiguration

> **Intentionally insecure.** The files under `code/` are deliberately vulnerable training material. Never run or deploy them.

## Scenario

Northwind Staffing (an invented company) is about to launch its intranet at `https://intranet.example.test`. The team copied the example environment file and CORS config from an earlier project and wants a quick pre-launch review. Nobody has looked at the HTTP response headers yet.

## Task

Review the environment file and the CORS config. One protective layer is missing from `code/` entirely: add it yourself (as a new file) and describe where you would register it. Make every checklist item true.

## Level and time

Quick, about 5 minutes.

## Fix checklist

- [ ] The production environment does not expose debug output or stack traces to visitors.
- [ ] CORS only allows the intranet's real origin, and credentials are not combined with a wildcard origin.
- [ ] Responses carry the standard security headers that protect against content sniffing, clickjacking and downgrade to HTTP.
- [ ] That protection is actually active for web requests, not just defined.

## Stretch

Go one step further with a policy that restricts where content may load from, and think about what would break in a page that loads scripts from a CDN.

## Files

- `code/.env.example` - example environment file for the production deployment.
- `code/config/cors.php` - cross-origin resource sharing settings for the API routes.
