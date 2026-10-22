# A03 Software Supply Chain Failures

> **Intentionally insecure.** The files under `code/` are deliberately vulnerable training material. Never run or deploy them.

## Scenario

Northwind Staffing (an invented company) is starting a new Laravel intranet. A teammate pasted a `composer.json` from a blog post and from an old project, then went on holiday. You are asked to review it before the first `composer install` runs on the build server.

## Task

Read `composer.json` as if it were a pull request from a stranger. Find everything that lets code you did not choose end up on your servers, and change the file until the checklist is true. The manifest is for reading only: do not run Composer on it.

## Level and time

Medium, about 8 minutes.

## Fix checklist

- [ ] No dependency uses a version constraint that accepts any release.
- [ ] Every package in `require` is one the project really means to use; nothing is there by accident or by lookalike name.
- [ ] No script downloads and executes code from the network.
- [ ] You can state how the exact installed versions are recorded and reviewed in your own project (the lock file is not part of this exercise).

## Stretch

Describe, or write, the CI step that checks dependencies for known vulnerabilities on every build.

## Files

- `code/composer.json` - the dependency manifest and install scripts of the new intranet project.
