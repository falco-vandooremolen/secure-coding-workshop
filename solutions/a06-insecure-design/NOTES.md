# Solution notes: A06 Insecure Design

- The route has `auth` and `throttle:10,1` middleware, so only signed-in users can check out and each account or IP is limited to ten attempts a minute. Pick the numbers from the business, not from this example.
- The controller validates only what the client is allowed to choose: `product_id` (must exist) and `quantity` (integer, 1 to 10). The price is never read from the request.
- The product is loaded with `Product::findOrFail()` and both `unit_price` and `total` are computed from the database price on the server.
- Validation limits quantity to a sensible maximum, which also closes the negative-quantity trick that turned an order into a credit.
- The root cause was design: the form was built around what the client could send, not around what the business allows. The fix works because the server now owns the facts.

Stretch (threat-model in three bullets):
- A buyer buys the whole stock to resell or to deny others: add a per-user purchase limit and stock reservation.
- A script places many orders to probe prices or discounts: rate limiting, monitoring of unusual order patterns.
- A user manipulates any other order field (for example `user_id`): never accept it from the client; take it from the session.
