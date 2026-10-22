# Solution notes: A02 Security Misconfiguration

- `.env.example`: `APP_DEBUG=false`. The real `APP_KEY` is generated per environment (`php artisan key:generate`) and never committed.
- `config/cors.php`: one explicit origin, explicit methods and an explicit header list. With `supports_credentials` set to true a wildcard origin must never be used.
- `SecurityHeaders` middleware sets `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Strict-Transport-Security` and a baseline `Content-Security-Policy`.
- Registration: `bootstrap/app.php` in this folder appends the middleware to the web group with `$middleware->web(append: [SecurityHeaders::class]);` inside the `->withMiddleware(...)` callback (checked against the Laravel 13.x middleware docs). Routes that are not in the web group (for example API routes) need `$middleware->api(append: [...])` as well, or use `$middleware->append(...)` for every request.

The CSP `default-src 'self'` is deliberately strict and will block inline scripts and CDN assets; tune it to the real app. HSTS should only be enabled once the site is reliably served over HTTPS.
