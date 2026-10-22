# Solution notes: A07 Authentication Failures

- The limiter key is `Str::lower($email) . '|' . $request->ip()`, so one account is limited per client and one client cannot guess across many accounts without hitting limits on each. Lowercasing stops bypassing the limit by changing letter case.
- `RateLimiter::tooManyAttempts($key, 5)` returns HTTP 429 after five failures. `RateLimiter::hit($key, 60)` records a failure that expires after 60 seconds. On success `RateLimiter::clear($key)` resets the counter.
- `Auth::attempt($credentials)` replaces the manual lookup and `Hash::check()`. On success `$request->session()->regenerate()` issues a new session identifier, which closes session fixation. Regenerating the CSRF token is not a substitute.
- The route also has `throttle:10,1` as a second, coarser layer (for example against one address spraying many different accounts). A named limiter such as `throttle:login` defined in a service provider is an equally good choice.
- The error message stays generic (`Invalid credentials.`) and is identical for unknown email and wrong password.
- Verify the Laravel 13 rate limiter and session APIs in the current documentation before using this in a real project.

Stretch: combine throttling (cheap, self-healing) with multi-factor authentication for strong protection, and avoid hard lockouts that an attacker can trigger on purpose.
