# Solution notes: A04 Cryptographic Failures

- `RegisterController` validates input (email format and uniqueness, minimum password length of 12) and stores `Hash::make($data['password'])`. Laravel uses bcrypt or Argon2 with a per-password salt and a tunable cost.
- `config/services.php` reads `env('PAYMENTS_API_KEY')` and `config/app.php` reads `env('APP_KEY')`. The values live in the server's `.env` (or secret store), which is listed in `.gitignore`.
- Rotate any key that was ever committed. Removing it from the code (or even rewriting git history) does not help, because clones, forks and CI caches may still hold it. Issue a new payments key, invalidate the old one, and generate a new `APP_KEY` with `php artisan key:generate` (note that changing it logs everyone out and invalidates data encrypted with the old key, so plan the rollout).
- Existing MD5 hashes in a real database need a migration plan, for example rehash on the next successful login.
