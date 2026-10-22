# Solution notes: A01 Broken Access Control

The fix has two parts.

1. The controller uses route model binding (`Document $document`) so a missing id gives a 404 automatically, and calls `Gate::authorize()` before it touches the record. A denied check throws an authorization exception, which Laravel renders as 403.
2. `DocumentPolicy` holds the rule in one place: only the owner (`user_id`) may view or delete. Laravel's policy auto-discovery finds `App\Policies\DocumentPolicy` for the `App\Models\Document` model, so no manual registration is needed.

The route file does not change: the `{id}` segment must be renamed to `{document}` so route model binding matches the controller parameter (`/documents/{document}`). Remember this when applying the fix in a real project.

Alternative fix: scope the query to the user, for example `$request->user()->documents()->findOrFail($id)`. That returns 404 for other users' records and does not reveal that they exist.

Not asserted here: Laravel 13 also has an authorization attribute for controllers; check the current docs for its exact syntax before using it.

Stretch: the index action should query only the user's own documents, never `Document::all()`.
