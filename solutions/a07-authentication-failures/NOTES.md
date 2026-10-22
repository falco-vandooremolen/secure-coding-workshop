# Solution notes: A07 Authentication Failures

- The limiter key is `Str::lower($email) . '|' . $request->ip()`, so one account is limited per client and one client cannot guess across many accounts without hitting limits on each. Lowercasing stops bypassing the limit by changing letter case.
- `RateLimiter::tooManyAttempts($key, 5)` returns HTTP 429 after five failures. `RateLimiter::hit($key, 60)` records a failure that expires after 60 seconds. On success `RateLimiter::clear($key)` resets the counter.
- `Auth::attempt($credentials)` replaces the manual lookup and `Hash::check()`. On success `$request->session()->regenerate()` issues a new session identifier, which closes session fixation. Regenerating the CSRF token is not a substitute.
- The route also has `throttle:login` as a second, coarser layer (for example against one address spraying many different accounts). The named limiter is defined in `app/Providers/AppServiceProvider.php` (see this folder), as in the 13.x routing docs:

  ```php
  RateLimiter::for('login', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));
  ```

- The error message stays generic (`Invalid credentials.`) and is identical for unknown email and wrong password.
- Checked against the Laravel 13.x docs: `Auth::attempt()` plus `$request->session()->regenerate()` (authentication), `RateLimiter::tooManyAttempts()` and `RateLimiter::clear()` (rate limiting) and named limiters via `RateLimiter::for()` (routing). `RateLimiter::hit($key, $decaySeconds)` is not shown on the 13.x docs page, which documents `increment()` instead; it is verified in the 13.x framework source, where `hit()` simply calls `increment($key, $decaySeconds)`. The numeric `throttle:10,1` form (used in A06 and A08) is not in the 13.x docs but is still accepted by `ThrottleRequests` in the 13.x source; this solution uses a named limiter because that is the documented style.

Stretch: combine throttling (cheap, self-healing) with multi-factor authentication for strong protection, and avoid hard lockouts that an attacker can trigger on purpose.
