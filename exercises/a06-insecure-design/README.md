# A06 Insecure Design

> **Intentionally insecure.** The files under `code/` are deliberately vulnerable training material. Never run or deploy them.

## Scenario

Northwind Staffing (an invented company) runs a small web shop where employees can order office supplies and branded gifts. A colleague noticed that the checkout form sends the price along with the product and quantity. After a quick look in the browser developer tools they bought a laptop bag for one cent, and then ordered a quantity of minus five to "earn" credit. Nothing in the code throws an error, so this is not a typo: the flow itself was never thought through.

## Task

Read the checkout route and controller as if you were reviewing a design, not hunting for a typo. Ask what a user could send that the designers did not intend, and change the files until the checklist is true. Then spend the last minutes writing down, in three bullets, how you would threat-model this flow.

## Level and time

Stretch, about 10 minutes.

## Fix checklist

- [ ] The price used for an order comes from the database, never from the request.
- [ ] The quantity is validated: it must be an integer, at least 1 and capped at a sensible maximum.
- [ ] The checkout route is rate limited.
- [ ] The order total is computed on the server.

## Stretch

Think of abuse cases beyond the code: what stops one account from buying the entire stock, or from placing hundreds of orders to probe prices? Write three bullets describing threats and the control you would add for each (for example a per-user purchase limit).

## Files

- `code/routes/web.php` - declares the checkout route.
- `code/app/Http/Controllers/CheckoutController.php` - creates an order from the submitted form.
