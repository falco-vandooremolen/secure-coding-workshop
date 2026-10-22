# Solution notes: A05 Injection

- The controller uses the query builder: `DB::table('products')->where('name', 'like', '%'.$term.'%')->get()`. The wildcard characters are added around the term, but the term itself travels as a bound parameter. The raw alternative is `DB::select('SELECT * FROM products WHERE name LIKE ?', ['%'.$term.'%'])`.
- The input is also validated (`required|string|max:100`). Validation limits the shape of the data but is not the defence against injection; the bindings are.
- The view uses `{{ $comment->body }}`, which escapes HTML. Stored script tags are displayed as text.
- A user can still type `%` or `_` and widen the match. That is a correctness issue, not injection, but it can be escaped if exact matching matters.

Stretch: `{!! !!}` is acceptable only for content the application itself produced and trusts. If users need rich text, sanitise on input or output with an allow-list based purifier library, and add a Content-Security-Policy as a second layer.
