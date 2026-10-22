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
  # count checklist items only inside the '## Fix checklist' section
  n=$(awk '/^## /{in_s = ($0 == "## Fix checklist")} in_s && /^- \[ \]/{c++} END{print c+0}' "$d/README.md" 2>/dev/null); n=${n:-0}
  { [ "$n" -ge 3 ] && [ "$n" -le 5 ]; } || err "$d/README.md needs 3-5 checklist items (found $n)"
  grep -Eq '^## Level and time' "$d/README.md" 2>/dev/null || err "$d/README.md lacks 'Level and time'"
  for h in "Why it matters" "OWASP category" "Common wrong fixes" "Discussion questions"; do
    grep -qx "## $h" "$d/trainer-notes.md" 2>/dev/null || err "$d/trainer-notes.md lacks '## $h' heading"
  done
  # every vulnerable file must carry the notice (ignore macOS metadata files)
  while IFS= read -r f; do
    head -n 6 "$f" | grep -qi "intentionally insecure" || err "$f lacks insecure notice"
  done < <(find "$d/code" -type f ! -name .DS_Store)
done

# Solutions must never carry the insecure notice.
if [ -d solutions ]; then
  while IFS= read -r f; do
    err "$f contains 'INTENTIONALLY INSECURE' (solutions must be clean)"
  done < <(grep -rli "INTENTIONALLY INSECURE" solutions 2>/dev/null)
fi

# Secret-pattern sanity check (plan Task 14, step 2).
secret_hits=$(grep -rEn "sk_live|AKIA[0-9A-Z]{12}|ghp_[A-Za-z0-9]{20}|BEGIN (RSA|PRIVATE)" exercises solutions template 2>/dev/null)
[ -z "$secret_hits" ] || err "possible secret in repo: $secret_hits"

# PHP syntax (Blade templates are not plain PHP, so skip them).
# Uses local php if available, otherwise one Docker container for all files.
# Prints the path of every file that fails to parse, then the sentinel LINT_DONE.
# A missing sentinel or a non-zero runner exit status counts as a failure, so a
# crashed container can never look like a clean run.
LINT_ROOTS="${LINT_ROOTS:-exercises solutions template}"
LINT_SCRIPT='find '"$LINT_ROOTS"' -name "*.php" ! -name "*.blade.php" -print0 2>/dev/null | xargs -0 -r -n1 sh -c '"'"'php -l "$0" >/dev/null 2>&1 || echo "$0"'"'"'; echo LINT_DONE'
lint_status=0
if command -v php >/dev/null; then
  lint_out=$(sh -c "$LINT_SCRIPT" 2>/dev/null); lint_status=$?
elif command -v docker >/dev/null; then
  lint_out=$(docker run --rm -v "$PWD":/app -w /app php:8.5-cli sh -c "$LINT_SCRIPT" 2>/dev/null); lint_status=$?
else
  lint_out="__NO_PHP__"; lint_status=1
fi
if [ "$lint_out" = "__NO_PHP__" ]; then
  err "php not found (no php on PATH and no docker)"
else
  [ "$lint_status" -eq 0 ] || err "php lint runner exited with status $lint_status"
  printf '%s\n' "$lint_out" | grep -qx 'LINT_DONE' || err "php lint did not finish (LINT_DONE sentinel missing)"
  while IFS= read -r f; do
    [ -n "$f" ] && [ "$f" != "LINT_DONE" ] && err "php -l failed: $f"
  done <<< "$lint_out"
fi

# JSON files must parse
while IFS= read -r f; do
  python3 -c 'import json,sys; json.load(open(sys.argv[1]))' "$f" 2>/dev/null || err "invalid JSON: $f"
done < <(find exercises solutions template -name '*.json' 2>/dev/null)

[ $fail -eq 0 ] && echo "OK: all checks passed"
exit $fail
