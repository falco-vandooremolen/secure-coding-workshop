# Trainer notes: A03 Software Supply Chain Failures

## Why it matters

Most of the code in a Laravel app is not ours. A typosquatted package or a hijacked dependency runs with the same rights as our own code, and Composer scripts run on developer laptops and build servers where secrets live. Incidents such as malicious lookalike packages and compromised maintainer accounts show this is not theoretical.

## OWASP category

OWASP Top 10:2025 A03 Software Supply Chain Failures.

## Common wrong fixes

- Pinning to `dev-main`. That is even looser than `*`: it follows an unreviewed branch.
- Moving the `curl | sh` into a different hook (for example `post-update-cmd`) instead of removing it.
- Deleting the script but keeping `*` constraints.
- Fixing the manifest but never committing `composer.lock`, so every install resolves different versions.

## Discussion questions

- How could you spot a typosquatted package name in review? Which tools help?
- What is the difference between the constraints in `composer.json` and the lock file? Which one does CI install from?
- Who reviews dependency updates in your team, and how often do they happen?
- What can a Composer plugin or script do on a developer laptop that a normal library cannot?
