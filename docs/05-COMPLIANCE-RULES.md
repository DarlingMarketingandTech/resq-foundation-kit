# 05 — Compliance Rules

> Non-negotiable constraints. Taste, conversion, and source-blueprint copy must not override these rules.

## Accessibility

- [ ] Target WCAG 2.2 AA.
- [ ] Color contrast: 4.5:1 for body text, 3:1 for large text and UI boundaries.
- [ ] Focus is visible on every interactive element.
- [ ] Mega-menu, mobile drawer, cart drawer, filters, checkout, and account flows are keyboard operable.
- [ ] Form labels and errors are programmatically associated.
- [ ] Images have meaningful `alt`; decorative images use empty alt.
- [ ] No essential information is conveyed by color alone.

## Performance

- [ ] Avoid layout shift on PLP/PDP images.
- [ ] Defer non-critical JS.
- [ ] Respect `prefers-reduced-motion`.
- [ ] Do not add motion that delays checkout or hides CTAs.
- [ ] Dequeue Woo assets only after measuring impact.

## Checkout and Commerce Safety

- [ ] Never bypass Woo nonce validation.
- [ ] Payment gateways use test mode in local/staging.
- [ ] Do not override gateway iframe internals or payment provider markup.
- [ ] Price, tax, coupon, bundle, and savings display must match real WooCommerce calculations.
- [ ] Deactivate live payment and outbound email plugins in sandbox environments.
- [ ] Cart drawer suggestions are optional and never block checkout.

## CBD Isolation

CBD products must be isolated by data and UI:

- Separate CBD navigation/category paths.
- Product-level CBD flag via plugin data.
- CBD notice slots on category, PDP, cart, and checkout when required.
- No default FBT, related product, cart drawer, or bundle recommendations between CBD and standard product lanes.
- No CBD copy in standard skincare, pet care, baby/infant, or general bundle cards unless the entire context is approved for CBD.
- No production CBD claims or notices without compliance review.

## Medical-Claim Avoidance

Do not use copy that promises diagnosis, cure, treatment, prevention, or guaranteed outcomes for disease or medical conditions.

| Risky | Safer |
|---|---|
| Cures eczema | Supports dry, sensitive-feeling skin |
| Treats infections | Helps keep skin comfortable and cared for |
| Heals wounds | Supports a gentle care routine for minor skin discomfort |
| Anti-inflammatory treatment | Comfort-focused formula |
| Clinically proven to restore skin | Designed to support hydrated, resilient-looking skin |

## Pet Health Caution

Pet copy needs extra caution because shoppers may interpret product pages as veterinary advice.

| Risky | Safer |
|---|---|
| Heals hot spots | Helps soothe the look and feel of irritated areas |
| Treats anxiety | Supports a calming routine |
| Vet recommended | Vet-reviewed or vet-informed only if documented |
| For diabetic dogs | For dogs with special dietary routines, if substantiated |
| Prevents infection | Helps keep skin clean as part of regular care |

Add "consult your veterinarian" style guidance when product category, severity, or ingestion risk warrants it.

## Baby and Infant Caution

Baby/infant pages must avoid overstating safety.

| Risky | Safer |
|---|---|
| Safe enough for newborns | Made for delicate skin; patch test and follow label directions |
| Hypoallergenic guarantee | Formulated with gentle-use intent, if substantiated |
| Pediatrician approved | Pediatrician-reviewed only if documented |
| Prevents rash | Supports everyday skin comfort |

No infant claims should ship without substantiation and legal review.

## Before/After Proof Caution

Before/after imagery and testimonials are high-risk when tied to health, pet health, CBD, or baby/infant claims.

- Use only documented, permissioned, representative proof.
- Do not imply guaranteed results.
- Pair proof with context and disclaimers where required.
- Avoid dramatic medical framing in captions.
- Do not use source-blueprint proof examples as production content.

## Donation and Mission Claim Caution

Mission and donation copy must match operational reality.

| Risky | Safer |
|---|---|
| Every purchase donates an identical item | A portion of purchases supports rescue partners, if documented |
| Buy 1, donate 1 | Use only if fulfillment and accounting can prove it |
| Directly donated immediately | Avoid timing/mechanism claims unless audited |
| Feeds our mission | Mission-aligned language is acceptable if specific claims are verified |

Donation claims require policy, fulfillment, accounting, and partner proof before use.

## Cross-Sell Restrictions

- CBD products do not appear in standard product FBT, related, cart drawer, or bundle suggestions by default.
- Standard products do not appear in CBD suggestions by default.
- Human and pet products should not cross-sell across audiences unless the page context makes intent explicit.
- Baby/infant products should not be used as generic cart upsells.
- Products with medical-adjacent or before/after claims should not be algorithmically injected into unrelated pages.

## Payment Gateway and Ad Crawler Risk Reduction

- Keep CBD paths visibly separate.
- Avoid regulated terms in standard nav labels, meta titles, product cards, and checkout surfaces.
- Do not mix CBD and non-CBD products in generic "complete your routine" blocks.
- Keep checkout copy plain and policy-aligned.
- Do not hide CBD content with CSS while leaving it in crawlable markup.
- Use plugin flags to make restrictions data-driven and reviewable.

## Privacy and Data

- [ ] No PII in logs or committed fixtures.
- [ ] No customer/order exports in repo.
- [ ] Cookie/consent requirements are TBD and must be decided before analytics tags.
- [ ] Analytics tags load only after consent if required.

## Security Review Triggers

Engage compliance/security review when touching:

- Checkout, payment, account authentication, or session handling
- File upload or custom product fields
- Admin capabilities, REST endpoints, webhooks, or secrets
- CBD flags, checkout notices, age gates, or restricted cross-sell logic
- Donation/mission claim mechanisms

## Content Rules

- Apply Stop Slop to customer-facing copy.
- Treat source-blueprint copy as draft strategy, not approved production copy.
- Use benefit/support language over cure/treatment language.
- Return/refund/shipping copy must match actual policy.
- If a claim requires proof, the doc or PR must identify the proof source before implementation.
