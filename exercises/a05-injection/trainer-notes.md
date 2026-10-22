# Trainer notes: A05 Injection

## Why it matters

Injection lets an attacker change what a query or page does. SQL injection can dump or modify an entire database, and stored cross-site scripting runs attacker code in the browsers of every colleague who views the page, including administrators. Both have been well understood for decades and still appear because string concatenation is the easy path.

## OWASP category

OWASP Top 10:2025 A05 Injection (SQL injection and cross-site scripting).

## Common wrong fixes

- Using `addslashes()` or manual escaping. Easy to get wrong and encoding-dependent.
- Blocklisting characters such as `'` or `--`. Attackers have many other ways in, and legitimate input (an apostrophe in a name) breaks.
- Using `strip_tags()` as the only defence for HTML output.
- Switching to `DB::raw()` or `whereRaw()` with the same concatenation and assuming the ORM protects it.

## Discussion questions

- What is second-order injection (data stored safely, then used unsafely later)? Where could it occur here?
- Why does the query builder not help if you pass concatenated strings into `DB::raw()` or `whereRaw()`?
- A `LIKE` search treats `%` and `_` as wildcards. Is that a security problem, a correctness problem, or both?
- When is `{!! !!}` legitimate, and how do you sanitise rich text safely (allow-list, purifier library)?
