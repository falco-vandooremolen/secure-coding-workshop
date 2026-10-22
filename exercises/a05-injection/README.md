# A05 Injection

> **Intentionally insecure.** The files under `code/` are deliberately vulnerable training material. Never run or deploy them.

## Scenario

Northwind Staffing (an invented company) has a product catalogue with a search box, and a page where employees leave comments on products. Last week a tester typed a single apostrophe in the search box and got a database error page. Another tester left a comment that made a pop-up appear for everyone who opened the page.

## Task

Read the search controller and the comments view. Find where untrusted input ends up somewhere it is interpreted as code, and change the files until the checklist is true. The search must still support partial matches, so searching "chai" finds "Office chair".

## Level and time

Medium, about 8 minutes.

## Fix checklist

- [ ] No request input is joined into an SQL string.
- [ ] Input reaches the database only through bindings or the query builder.
- [ ] User-provided content is escaped when it is rendered in the Blade view.
- [ ] Partial-match search (including terms with `%` or `'` in them) still works without errors.

## Stretch

Rich-text comments are a requested feature. Explain when raw Blade output is ever acceptable, and what you would need before allowing any HTML from users.

## Files

- `code/app/Http/Controllers/SearchController.php` - searches the products table by name.
- `code/resources/views/comments/index.blade.php` - lists comments on a product page.
