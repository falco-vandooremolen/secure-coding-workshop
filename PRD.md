# PRD: Secure Coding Workshop Exercise Repository

## Problem and goal

The workshop has no exercise repository yet, so trainers would write code samples from scratch every year. Slides show vulnerable code, but developers learn more by finding and fixing it themselves.

The goal is one maintained repository of realistic Laravel 13 code where pairs find and fix a vulnerability, and a checklist shows what a correct fix looks like. It covers all ten OWASP Top 10:2025 categories, A01 to A10, so even the fastest pair is still working when the 30-minute exercise block ends. The code only has to be readable and realistic, not a running application.

## Goals and non-goals

Version 1 delivers ten Laravel 13 exercises on PHP 8.5, one per OWASP Top 10:2025 category, that a pair can read, fix and check against a checklist without help from the trainer.

**Goals**

- Ten exercises, one for each category A01 to A10 of OWASP Top 10:2025, ready for the workshop on 12 November 2026.
- More material than fits in the 30-minute exercise block, so even the fastest pairs are still working at the end.
- No setup: participants open Laravel 13 code excerpts in their own editor, with nothing to install or run.
- Every exercise has a short checklist of what a correct fix must do, and a reference solution for the debrief.
- A trainer can run the session from the repository, with no new material to write.
- Adding another exercise means copying one template folder.

**Non-goals for v1**

- A running application, containers or automated exploit tests in v1.
- Accounts, scoring or a leaderboard.
- Languages other than PHP, and frameworks other than Laravel.
- Anything that touches real data, real services or production.

## Users and workshop flow

Three roles use the repository, and each needs something different from it.

| Role | Who | Needs |
| --- | --- | --- |
| Participant | Developer in the workshop; developers also do the testing, so there is no separate tester role | A clear task, hints when stuck, a checklist to judge their own fix |
| Trainer | Runs the session | Solutions, discussion questions and notes on common wrong fixes |
| Maintainer | Refreshes the repository each year; the author of this PRD | A template, a lint check that keeps the PHP valid, a clear owner |

In the 30-minute block, pairs work through the exercises one after another and move on when they finish or get stuck.

1. **Prepare.** Before the workshop, each pair clones the repository or downloads the zip and opens it in their editor.
2. **Pick.** Start at A01 or any exercise; the README shows its level and expected minutes.
3. **Find.** Read the PHP code and name the weakness.
4. **Fix.** Rewrite the code until it meets the checklist in the README.
5. **Move on.** Take the next exercise. The trainer debriefs the most-solved ones after the block.

## Exercises in v1

Each of the ten OWASP Top 10:2025 categories gets one exercise in Laravel 13 on PHP 8.5, which adds up to more material than any pair can finish in 30 minutes. Levels are a first proposal: quick exercises first, stretch exercises for pairs who are ahead.

| Category | Weakness in the Laravel code | Level | A correct fix looks like |
| --- | --- | --- | --- |
| A01 Broken Access Control | A controller loads a record with `Document::find($id)` from the request id and never checks the signed-in user's rights | Quick | A policy check, for example the `#[Authorize]` attribute, or a query scoped to the user, so other users' records return 404 |
| A02 Security Misconfiguration | An example `.env` sets `APP_DEBUG=true` for production, `config/cors.php` allows every origin and no security headers are sent | Quick | Debug is off in production, CORS lists the real origins, and a middleware sets the standard security headers |
| A03 Software Supply Chain Failures | A `composer.json` uses `*` version constraints and a lookalike package name, and a Composer script downloads and runs a remote shell script | Medium | Versions are pinned with a committed `composer.lock`, the lookalike is removed and no script fetches remote code |
| A04 Cryptographic Failures | Passwords are stored with `md5()`, and an API key and the `APP_KEY` are committed in the code | Quick | Passwords use the `Hash` facade, and keys come from the environment and are never committed |
| A05 Injection | A raw `DB::select()` query joins request input into the SQL, and a Blade view prints a comment with `{!! !!}` | Medium | The query uses bindings or the query builder, and the view uses escaped `{{ }}` output |
| A06 Insecure Design | A checkout controller takes the price and quantity from the request and has no rate limit | Stretch | The server looks up the price, validates the quantity, and the `throttle` middleware limits repeats |
| A07 Authentication Failures | A custom login controller signs the user in without regenerating the session and allows unlimited attempts | Medium | The session is regenerated at login and failed attempts are rate limited |
| A08 Software or Data Integrity Failures | A controller passes serialised PHP from the request to `unserialize()`, and a webhook handler does not verify the sender's signature | Stretch | The data is JSON with a signature check, and the webhook signature is verified with `hash_equals` |
| A09 Security Logging and Alerting Failures | Failed logins and 403 responses are not logged, and a password is written to the log | Medium | Security events are logged with user, IP and time but no secrets, and repeated failures raise an alert |
| A10 Mishandling of Exceptional Conditions | An authorisation helper catches `Throwable` and returns true, and the exception handler returns the stack trace | Quick | The check fails closed, the failure is logged and users see a generic error |

## What every exercise must contain

Each exercise is one folder with the same six parts, so participants and trainers always know where to look.

1. **README.** Scenario, task, level (quick, medium or stretch), expected minutes, and the fix checklist, with no spoilers.
2. **Vulnerable Laravel code.** A few short files such as a controller, route, Blade view or config file, with one clear weakness. They must parse on PHP 8.5 but do not need to run. Some exercises add a stretch part for pairs who finish early.
3. **Fix checklist.** Three to five checkable statements of what a correct fix must do, so a pair can judge its own work.
4. **Hints.** Three levels, from where to look to what kind of fix, each in its own file so people open only what they need.
5. **Reference solution.** Kept apart from the exercise folder, and not opened until the debrief.
6. **Trainer notes.** Why it matters, common wrong fixes such as blocklisting input, discussion questions, and the matching OWASP category.

## Repository structure and technical approach

One repository holds ten exercise folders of Laravel 13 code for PHP 8.5 to read and fix. It is not a full Laravel application, and nothing in it needs to run.

```text
secure-coding-exercises/
  README.md                    start here: rules and safety notice
  CONTRIBUTING.md              how to add an exercise
  exercises/
    a01-broken-access-control/
      README.md                scenario, task, level, minutes, fix checklist
      code/                    the Laravel files to review and fix
      hints/                   hint-1.md, hint-2.md, hint-3.md
      trainer-notes.md
    a02-security-misconfiguration/
    a03-supply-chain-failures/
    a04-cryptographic-failures/
    a05-injection/
    a06-insecure-design/
    a07-authentication-failures/
    a08-integrity-failures/
    a09-logging-and-alerting/
    a10-exceptional-conditions/
  solutions/                   reference fixes, one folder per exercise
  template/                    copy this to start a new exercise
```

- **Excerpts, not an application.** Each exercise holds only the files that matter, laid out like a Laravel 13 project. The framework is not installed, so there is nothing to run.
- **PHP 8.5 and Laravel 13 only.** Code and solutions use only what exists there. Laravel 13 supports PHP 8.3 to 8.5 ([release notes](https://laravel.com/docs/13.x/releases.md)).
- **No environment.** Participants open the files in their own editor, so there is nothing to set up and no container to start.
- **Invented data only.** Sample records, keys and passwords in the code are obvious placeholders.

## Safety and security requirements

The repository contains deliberately vulnerable code, so it must never be mistaken for real software or deployed as such.

- **Clear labelling.** The README opens with a notice, and every vulnerable file carries a header comment saying it is intentionally insecure.
- **Invented data only.** No real customer, employee or personal data, and no real credentials, tokens or keys. Keys and passwords in the code are obvious placeholders, so GitHub's own secret scanning has nothing real to flag.
- **Never run or deployed.** The code is read and edited, never installed or served, and the repository holds no deployment or hosting configuration. The composer manifest in the supply chain exercise is for reading only, and its lookalike package name is invented.
- **Not connected to our security scanners.** The repository stays out of company scanning, so its deliberate flaws do not lower our security score. The README says why, so nobody connects it by accident.
- **Own GitHub.** The repository lives on our own GitHub and holds no real company code, names, data or credentials. Attendee access is arranged there.

## Success metrics

The repository succeeds if every pair is busy for the whole 30-minute block and the trainer has little to prepare. Targets in brackets are still to be agreed.

| Metric | Target | How we measure it |
| --- | --- | --- |
| Volume of material | The fastest pair is still working when the 30 minutes end | Trainer observation |
| Exercise completion | Most pairs fix at least [3] exercises | Trainer tally during the block |
| Exercise quality | All ten have a fix checklist and a reference solution, and every PHP file parses | Maintainer review and the lint check |
| Usefulness | Average rating of [score] or higher | Short feedback form after the workshop |
| Yearly refresh effort | No more than [hours] hours to review and update | Maintainer's own log after the retro |

