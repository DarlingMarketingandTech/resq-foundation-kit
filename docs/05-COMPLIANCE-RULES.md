# 05 — Compliance Rules

> Non-negotiable constraints. Taste Skill and visual novelty must not override these.

## Accessibility (WCAG 2.2 AA target)

- [ ] Color contrast ≥ 4.5:1 for body text; 3:1 for large text
- [ ] Focus visible on all interactive elements
- [ ] Form labels and error associations on checkout/account
- [ ] Images have meaningful `alt` (decorative: empty alt)
- [ ] No essential information conveyed by color alone
- [ ] Keyboard operable cart and checkout flows

## Performance

- [ ] LCP target: _TBD_ (document per template)
- [ ] Defer non-critical JS; avoid layout shift on PLP images
- [ ] Woo scripts/styles: dequeue only with measured impact
- [ ] Critical CSS strategy: _TBD_

## Checkout and commerce safety

- [ ] No plugin/theme changes that bypass Woo nonce validation
- [ ] Payment gateway test mode only in local/staging
- [ ] Deactivate live payment + outbound email plugins in sandbox
- [ ] Price/tax display filters reviewed for legal accuracy — _jurisdiction TBD_

## Privacy and data

- [ ] No PII in logs or committed fixtures
- [ ] Cookie/consent banner requirements: _TBD_
- [ ] Analytics tags load only after consent if required

## Security review triggers

Engage **compliance-reviewer** agent when touching:

- Checkout, payment, or account authentication
- File upload or custom product fields
- Admin capabilities or REST endpoints
- Secrets, API keys, webhooks

## Content compliance

- Apply **Stop Slop** to customer-facing copy
- Health/safety claims (if applicable): _regulatory notes TBD_
- Return/refund language must match actual policy
