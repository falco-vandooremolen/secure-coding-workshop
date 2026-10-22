# Solution notes: A01 Broken Access Control

The fix has two parts.

1. The controller uses route model binding (`Document $document`) so a missing id gives a 404 automatically, and calls `Gate::authorize()` before it touches the record. A denied check throws an authorization exception, which Laravel renders as 403.
2. `DocumentPolicy` holds the rule in one place: only the owner (`user_id`) may view or delete. Laravel's policy auto-discovery finds `App\Policies\DocumentPolicy` for the `App\Models\Document` model, so no manual registration is needed.

The route file changes too (`routes/web.php` in this folder): the `{id}` segment is renamed to `{document}` so route model binding matches the controller parameter (`/documents/{document}`). Both routes stay inside the `auth` middleware group.

Alternative fix: scope the query to the user, for example `$request->user()->documents()->findOrFail($id)`. That returns 404 for other users' records and does not reveal that they exist.

Alternative: Laravel 13 has an `Authorize` controller attribute (verified in the 13.x controllers and authorization docs). It is a shortcut for the `can` middleware, so the check runs before the method body:

```php
use Illuminate\Routing\Attributes\Controllers\Authorize;

#[Authorize('delete', 'document')]
public function destroy(Document $document)
{
    $document->delete();

    return redirect('/documents');
}
```

The reference solution uses `Gate::authorize()` because it is explicit and works in every Laravel version.

Stretch: any future list/index action should query only the user's own documents, never `Document::all()`.
