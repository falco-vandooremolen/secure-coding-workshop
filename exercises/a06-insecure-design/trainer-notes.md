# Trainer notes: A06 Insecure Design

## Why it matters

Insecure design is about flaws that no amount of clean code can fix, because the requirements and the flow never considered abuse. Here the shop trusts the client for the price and the quantity, so an attacker needs nothing more than a browser. Threat modelling and abuse-case thinking are cheap early and expensive after launch. This is the Stretch exercise, so pairs who finish early should spend the time on the threat-modelling bullets.

## OWASP category

OWASP Top 10:2025 A06 Insecure Design (missing business-logic controls, trust placed in the client, no abuse-case analysis).

## Common wrong fixes

- Validating that `price` is numeric or positive and still reading it from the request.
- Hiding the price field or making it read-only in the HTML form. The request can be replayed with any tool.
- Adding client-side (JavaScript) limits on the quantity.
- Fixing only the price and leaving the quantity unbounded (negative or huge values).
- Adding rate limiting but keeping the client-supplied price.

## Discussion questions

- Why is this a design problem and not an implementation bug? At which point in a project should someone have caught it?
- What would a three-bullet threat model of this checkout flow contain? Who is the attacker and what do they want?
- Which business rules are missing besides price and quantity (stock levels, per-user limits, order approval above an amount)?
- How do you make abuse-case review a normal part of refinement, not a special event?
