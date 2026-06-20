---
name: ecommerce-taste-review
description: Use for frontend polish reviews against brand guidelines and conversion heuristics.
---

# Ecommerce Taste Review

## Use when

- Reviewing visual merchandising PRs (PLP, PDP, homepage zones)
- Evaluating UI polish on storefront-facing templates
- Checking brand alignment on campaign or landing pages

## Review criteria

1. Brand alignment — does it match tokens and voice from `docs/02-BRAND-FOUNDATION.md`?
2. Conversion clarity — are CTAs visible, trust signals present, friction minimized?
3. Accessibility — does it comply with `docs/05-COMPLIANCE-RULES.md`?
4. Performance — no unnecessary motion, images lazy-loaded, critical CSS intact?
5. Mobile-first — does the layout work on small screens before large ones?

## Constraints

- Accessibility and performance override visual novelty
- No motion that delays checkout or obscures primary CTAs
- `prefers-reduced-motion` must be respected
