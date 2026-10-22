# Solution notes: A03 Software Supply Chain Failures

- Constraints are bounded: `laravel/framework` `^13.0`, `guzzlehttp/guzzle` `^7.9`. Composer will accept compatible updates but never a new major version silently.
- The lookalike package `laravel-framwork/helpers` is removed. In a real project, find out why it was added and whether the real functionality is needed from a trusted source.
- The remote `curl | sh` script is removed. The only script left runs a local artisan command (`@php artisan package:discover --ansi`).
- `"config": {"allow-plugins": {}}` means no Composer plugin may run unless it is explicitly allowed.

The lock file is not included, as this is a reading exercise. In a real project `composer.lock` must be committed, CI should install from it with `composer install --no-scripts` (add scripts back deliberately), and a `composer audit` step should fail the build on known vulnerabilities.
