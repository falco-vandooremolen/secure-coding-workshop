# Solution notes: A02 Security Misconfiguration

- `.env.example`: `APP_DEBUG=false`. The real `APP_KEY` is generated per environment (`php artisan key:generate`) and never committed.
- `config/cors.php`: one explicit origin, explicit methods and an explicit header list. With `supports_credentials` set to true a wildcard origin must never be used.
- `SecurityHeaders` middleware sets `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Strict-Transport-Security` and a baseline `Content-Security-Policy`.
- Registration: in `bootstrap/app.php`, inside the `->withMiddleware(function (Middleware $middleware) { ... })` callback, append it to the web group with `$middleware->web(append: [\App\Http\Middleware\SecurityHeaders::class]);`. Verify the exact API against the Laravel 13 docs when applying it.

The CSP `default-src 'self'` is deliberately strict and will block inline scripts and CDN assets; tune it to the real app. HSTS should only be enabled once the site is reliably served over HTTPS.
