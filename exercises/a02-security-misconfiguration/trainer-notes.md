# Trainer notes: A02 Security Misconfiguration

## Why it matters

With debug on, Laravel error pages show stack traces, file paths and sometimes environment values, which is a gift to an attacker. An open CORS policy with credentials lets any website make authenticated calls on behalf of a signed-in user. Missing headers remove cheap, effective browser protections against clickjacking and content sniffing. None of this needs a code bug: a default left unchanged is enough.

## OWASP category

OWASP Top 10:2025 A02 Security Misconfiguration.

## Common wrong fixes

- Only setting `APP_ENV=local`. That is the wrong direction; the problem is `APP_DEBUG` in a production setting.
- Replacing `*` with a wildcard subdomain pattern that also matches attacker-controlled domains (for example a loose regex like `.*example.test`).
- Setting security headers in Blade templates. Headers belong in the HTTP response, not the HTML body, and templates do not cover errors or JSON responses.
- Writing the middleware but never registering it.

## Discussion questions

- Why is `*` combined with `supports_credentials` so dangerous? (Browsers actually refuse that combination, so what do frameworks do to make it "work"?)
- Which settings are safe by default and which need hardening? Who owns the checklist for a launch?
- How would you detect configuration drift between environments?
- Which header would you add first for an app that renders user content, and why?
