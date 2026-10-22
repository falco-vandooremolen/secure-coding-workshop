# Contributing

## Add an exercise

1. Copy the template: `cp -r template exercises/aNN-name`
2. Fill in every section of `exercises/aNN-name/README.md` (scenario, task, level and time, 3-5 checklist items, stretch, files).
3. Add the vulnerable files under `code/`, laid out like a Laravel 13 project. Every file starts with the intentionally-insecure notice:
   - PHP: `// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.`
   - Blade: `{{-- INTENTIONALLY INSECURE ... --}}`
   - `.env`: `# INTENTIONALLY INSECURE ...`
   - `composer.json`: put the notice in `"description"` (JSON has no comments).
4. Write three hints: `hint-1.md` where to look, `hint-2.md` what class of problem, `hint-3.md` what kind of fix. No full code in hints.
5. Write `trainer-notes.md` with `## Why it matters`, `## OWASP category`, `## Common wrong fixes`, `## Discussion questions`.
6. Create `solutions/aNN-name/` with the fixed files (same paths as `code/`, without the insecure notice) and a `NOTES.md`. The template's `solution/` folder shows the shape.
7. Add the folder name to `EXERCISES` in `scripts/check.sh`.
8. Run `./scripts/check.sh` and fix every failure.

## Placeholder and secret rules

- Placeholders only. Fake secrets must be obviously fake and must not match real key formats: no `sk_live_`, `AKIA`, `ghp_`, and no base64 `APP_KEY` of 32 real bytes.
- Use values like `EXAMPLE-NOT-A-REAL-KEY-...`.
- Invented data only: no real customers, employees, hosts or credentials. Use `example.test` domains.
- Solution files do not carry the insecure notice.

## The check script

`scripts/check.sh` verifies structure, safety labelling, PHP syntax and JSON validity. It uses `php` from your PATH. If `php` is not installed, it falls back to Docker (`php:8.5-cli`) and lints all files in a single container. It fails with "php not found" only when neither is available.

## CI

`.github/workflows/lint.yml` runs the check on every push and pull request. If `shivammathur/setup-php` does not yet offer PHP `8.5`, use `8.4` and note it here.
