> **WARNING: INTENTIONALLY INSECURE CODE.** Everything under exercises/ is deliberately vulnerable training material. Never run, install, copy into real projects or deploy it.

# Secure Coding Workshop Exercises

## What this is

Ten Laravel 13 / PHP 8.5 code excerpts, one for each category of the OWASP Top 10:2025 (A01 to A10). Each excerpt hides a realistic weakness. You read the code, find the problem and fix it.

The repository is read-only material. It is not a Laravel application and there is nothing to install or run. Open the files in your own editor.

## How to work

1. Clone the repository or download the zip, and open it in your editor.
2. Pick an exercise (start at A01 or anywhere you like) and read its `README.md`.
3. Find the weakness in the files under `code/`.
4. Fix it by editing the code.
5. Tick the fix checklist in the exercise README to judge your own work.
6. Stuck? Open the hints one at a time: `hint-1.md` (where to look), `hint-2.md` (what class of problem), `hint-3.md` (what kind of fix).
7. Do not open `solutions/` until the debrief.

## Exercises

| Exercise | OWASP category | Level | Minutes |
| --- | --- | --- | --- |
| `a01-broken-access-control` | A01 Broken Access Control | Quick | 5 |
| `a02-security-misconfiguration` | A02 Security Misconfiguration | Quick | 5 |
| `a03-supply-chain-failures` | A03 Software Supply Chain Failures | Medium | 8 |
| `a04-cryptographic-failures` | A04 Cryptographic Failures | Quick | 5 |
| `a05-injection` | A05 Injection | Medium | 8 |
| `a06-insecure-design` | A06 Insecure Design | Stretch | 10 |
| `a07-authentication-failures` | A07 Authentication Failures | Medium | 8 |
| `a08-integrity-failures` | A08 Software or Data Integrity Failures | Stretch | 10 |
| `a09-logging-and-alerting` | A09 Security Logging and Alerting Failures | Medium | 8 |
| `a10-exceptional-conditions` | A10 Mishandling of Exceptional Conditions | Quick | 5 |

That is 72 minutes of material for a 30-minute block, so even the fastest pair stays busy.

## Rules and safety

- Invented data only. Names, keys and passwords in the code are obvious placeholders.
- No deployment configuration. The code is read and edited, never installed or served.
- **Why scanners must not be connected: this repo contains deliberate flaws (SAST, secret and dependency scanners will flag them and lower our company security score). Do not connect it to Aikido, Dependabot alerts-as-gates, or any company scanning.**

## Trainers

Each exercise has a `trainer-notes.md` with why it matters, the OWASP category, common wrong fixes and discussion questions. Reference fixes live in `solutions/`, one folder per exercise.

## Maintainer

Owner: Falco van Dooremolen (author of the PRD). Yearly refresh:

1. Run `scripts/check.sh` and fix anything it reports.
2. Re-verify the code and solutions against the current Laravel documentation.
3. Log the hours spent.

See `CONTRIBUTING.md` for how to add an exercise.
