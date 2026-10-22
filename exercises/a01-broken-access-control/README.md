# A01 Broken Access Control

> **Intentionally insecure.** The files under `code/` are deliberately vulnerable training material. Never run or deploy them.

## Scenario

Northwind Staffing (an invented company) runs an intranet where employees keep their own payslips and contracts as "documents". Every employee has an account, and the document pages are only reachable when signed in. A colleague reviewing the code says: "We have the `auth` middleware, so we are safe, right?"

## Task

Read the routes and the controller. Decide who can do what today, and who should be able to. Change the code until every item on the checklist is true.

## Level and time

Quick, about 5 minutes.

## Fix checklist

- [ ] A signed-in user cannot view a document that belongs to someone else.
- [ ] A signed-in user cannot delete a document that belongs to someone else.
- [ ] When access is denied, the response is an error status (403 or 404) and contains none of the document's data.
- [ ] Every route that takes a document id is covered by the same rule.

## Stretch

Imagine the app also gets a page that lists documents. Describe, in one sentence, how you would make sure it shows only the signed-in user's own documents.

## Files

- `code/routes/web.php` - routes for viewing and deleting a document, behind the `auth` middleware.
- `code/app/Http/Controllers/DocumentController.php` - controller that loads and deletes documents by id.
