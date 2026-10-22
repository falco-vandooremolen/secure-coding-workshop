# Trainer notes: A01 Broken Access Control

## Why it matters

Broken access control tops the OWASP list because it is easy to introduce and rarely caught by automated tests. One missing ownership check on an id-based route can expose every employee's payslips or let an attacker delete other people's data. It needs no special tooling: the attacker just changes a number in the URL.

## OWASP category

OWASP Top 10:2025 A01 Broken Access Control (here: insecure direct object reference, IDOR).

## Common wrong fixes

- Hiding the link or button in the UI. The route still answers to anyone who types the URL.
- Switching to unguessable ids (UUIDs) and calling it done. Ids leak through logs, emails and shared links; an ownership check is still needed.
- Checking ownership in `show` but forgetting `destroy` (or the reverse). Check every action that takes an id.
- Adding the check in the Blade view after the data has already been loaded and sent to the template.

## Discussion questions

- Should a denied request return 403 or 404? Does 403 leak that the document exists?
- Where else do ids appear in this app (APIs, file downloads, exports, form hidden fields)?
- How would you test this so a future change cannot silently remove the check?
- Who should be able to see a document besides its owner (HR, managers)? How does the rule change?
