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

## Target Problem Lane Copy Rules

Target Problem Lanes are problem-led discovery pages (concern landings) that resolve to **one canonical parent product** per lane via `resq_get_canonical_product_id()` — not duplicate SKUs. Strategy source: product-to-problem mapping matrix and lane creative briefs (June 2026). **Draft lane copy is not approved production copy** until owner/legal sign-off per [`Product Data and Strategy/compliance-review-checklist.md`](Product%20Data%20and%20Strategy/compliance-review-checklist.md) §7.

### Scope and naming

- Use **Target Problem Lane** in implementation docs; **concern landing** in architecture docs (`07-INFORMATION-ARCHITECTURE.md`) — same surface.
- URL pattern: `/shop/{audience}/{category-slug}/{problem-slug}/` (examples: `/shop/human/mens-grooming/razor-burn`, `/shop/pet/topical-skin-care/hyperkeratosis`).
- Each lane maps to exactly one canonical WooCommerce parent record. Size/strength variants share the parent; lane CTAs add the canonical product (or a documented default variation), never a presentation-only duplicate.
- Lane headlines may name a **symptom or routine context**; product titles and cart lines must stay canonical catalog names.

### Global lane rules (all audiences)

| Rule | Requirement |
| --- | --- |
| Single Parent Rule | One back-end parent product may power multiple lane presentations; lane copy must not imply separate formulas. |
| Cosmetic framing | Describe appearance, feel, comfort, cleansing, and routine support — not diagnosis, cure, treatment, or prevention of disease. |
| No guaranteed outcomes | Avoid "instantly," "overnight cure," "eliminates," "clears," "eradicate," and similar certainty language unless substantiated and legally cleared. |
| Routine ladder honesty | Interactive checklist steps must reflect real routine membership from plugin data; unchecked steps are suggestions, not implied bundled efficacy. |
| Bundle upgrade callouts | Savings and kit prices must match live WooCommerce bundle calculations at publish time. |
| Proof blocks | Before/after timelines, "verified" quotes, and vet/pediatrician markers require documented permission and substantiation — see Before/After Proof Caution. |
| Mission copy | "Buy 1, Donate 1" and shelter-donation pledges ship only when fulfillment and accounting can prove the mechanism — see Donation and Mission Claim Caution. |
| CBD isolation | Standard human/pet/baby lanes must not cross-sell, ladder-link, or cart-suggest CBD collection items. Men's moisturizer lane is explicitly CBD-excluded in strategy. |
| Audience isolation | Pet lanes do not imply human use; human lanes do not imply pet use. Diabetic pet treats stay out of human checkout pipelines. |
| Plugin-owned CTAs | Primary lane CTAs resolve through `resq_get_canonical_product_id()` (or documented variation resolver) — no hardcoded product IDs in theme templates. |

### Block-level copy patterns

| Block | Allowed focus | Avoid |
| --- | --- | --- |
| **Hero** | Symptom-aware comfort, routine entry, cosmetic benefit support | Disease names as treatable conditions, "cure/heal/treat/prevent [condition]" |
| **Symptom benefit matrix (3-column)** | Sensory outcomes (cooling feel, softness, hydration, cleansing, slip, shine) | Cellular rewriting, antimicrobial/medical-grade, immune or systemic claims |
| **Routine ladder** | Step order, product names, optional kit upgrade with real price | Implying all steps are required for medical results |
| **Proof / trust** | Documented testimonials, substantiated badges, consult-your-vet/pediatrician guidance where warranted | Undocumented "vet-recommended," guaranteed timelines, dramatic medical framing |

### Per-lane compliance guardrails

Use **Allowed claim territory** for lane drafting. **Prohibited without legal clearance** lists must be scrubbed before publish. Canonical SKU prefixes reference the live catalog import (`wp resq-catalog`).

#### Human — men's grooming

| Lane slug | Canonical product | SKU prefix | Allowed claim territory | Prohibited without legal clearance |
| --- | --- | --- | --- | --- |
| `razor-burn` | Men's Face & Body Moisturizer | `RQ-HUM-MOISTURIZER` | Post-shave comfort, cooling feel, lightweight hydration, zero-grease finish, routine support after shaving | Treating pseudofolliculitis, curing razor burn, anti-inflammatory drug claims, "eliminates" bumps |
| `beard-and-odor-control` | Men's Face & Body Wash | `RQ-HUM-MENSWASH` | Cleansing, beard hair softening, sweat/oil removal, dual-use shave prep, fresh feel after activity | Antimicrobial, anti-dandruff therapeutic, odor as medical symptom, drug-grade anti-bacterial claims |

**Men's moisturizer lane — commerce:** exclude from all CBD collection cross-sells, FBT, and routine upgrades that include CBD SKUs.

#### Human — women's skincare

| Lane slug | Canonical product | SKU prefix | Allowed claim territory | Prohibited without legal clearance |
| --- | --- | --- | --- | --- |
| `makeup-removal-detox` | Women's Restoring Face & Body Wash | `RQ-HUM-WASH` | Gentle makeup removal, surface impurity cleansing, pollutant/dust removal, moisture-balanced clean feel | Anti-acne treatment, medical detoxification, deep cellular purification, disease claims |
| `exfoliation-texture` | Microdermabrasion Scrub | `RQ-HUM-SCRUB` | Physical polishing, rough-patch softening, smoother-looking texture, elbow/knee smoothing | Curing keratosis pilaris, eradicating "strawberry skin," treating underlying dermatological conditions |
| `overnight-repair` | Age-Defying Night Serum | `RQ-HUM-NIGHTSERUM` | Evening cosmetic support, appearance of tone uniformity, fine-line look refinement, overnight hydration feel | Medicinal anti-wrinkle cures, hyperpigmentation disease treatment, deep dermal cellular rewriting |

#### Human — hair & scalp

| Lane slug | Canonical product | SKU prefix | Allowed claim territory | Prohibited without legal clearance |
| --- | --- | --- | --- | --- |
| `post-color-irritation` | Manuka Honey Shampoo | `RQ-HUM-SHAMPOO` | Buildup removal, dry-scalp comfort feel, sebum/oil balance appearance, color-safe cleansing | Treating psoriasis, seborrheic dermatitis, chemical burn treatment, scalp disease cures |
| `split-end-defense` | Manuka Honey Conditioner | `RQ-HUM-CONDITIONER` | Cuticle smoothing, detangling slip, heat-styling stress reduction, moisture seal, shine | Follicle regrowth, structural hair restoration, clinical repair of split ends |

#### Human — baby / infant

| Lane slug | Canonical product | SKU prefix | Allowed claim territory | Prohibited without legal clearance |
| --- | --- | --- | --- | --- |
| `diaper-rash` | All-in-One Intensive Skin Treatment Cream (baby presentation) | `RQ-BABY-CREAM`, `RQ-HUM-AIOCREAM` | Gentle diaper-area comfort, chafing friction support, 5.5 pH-balanced routine language (if substantiated), breathable barrier feel | Newborn safety guarantees, "cleared overnight," drug/diaper-rash treatment claims without applicable regulatory classification, undocumented hypoallergenic guarantees |

Baby lanes inherit **Baby and Infant Caution** and require legal review of active-ingredient positioning (zinc oxide, drug vs. cosmetic rules) before publish.

#### Pet — topical skin care

| Lane slug | Canonical product | SKU prefix | Allowed claim territory | Prohibited without legal clearance |
| --- | --- | --- | --- | --- |
| `hyperkeratosis` | Pet Skin Treatment Cream | `RQ-PET-SKINCREAM` | Softening rough nose/paw calluses, crack comfort, lick-safe topical care, moisture barrier support | Curing hyperkeratosis, veterinary treatment substitute, infection prevention/treatment |
| `feline-dermatitis` | Pet Skin Treatment Cream (2oz default) | `RQ-PET-SKINCREAM` | Chin/muzzle fold comfort, gentle non-toxic botanical care, lick-safe application | Clearing acne, treating dermatitis as a diagnosed condition, undocumented "vet-recommended" |

Pet topical lanes share one parent per **Single Parent Rule** (dog, cat, equine presentations). Add consult-your-veterinarian guidance for severity, ingestion, or persistent symptoms.

#### Pet — coat grooming

| Lane slug | Canonical product | SKU prefix | Allowed claim territory | Prohibited without legal clearance |
| --- | --- | --- | --- | --- |
| `seasonal-grass-itch` | All-Natural Manuka Honey Pet Shampoo | `RQ-PET-SHAMPOO` | Washing away pollen/grass/debris, cooling feel on irritated coat, gentle cleanse | Curing allergies, treating systemic immune response, replacing medicated veterinary washes |
| `undercoat-friction-static` | Manuka Honey Pet Conditioner | `RQ-PET-CONDITIONER` | Mat/tangle glide, static reduction, cuticle moisture, deshedding slip | Parasite treatment, anti-fungal therapeutic claims, medical dander eradication |

#### Pet — horse care

| Lane slug | Canonical product | SKU prefix | Allowed claim territory | Prohibited without legal clearance |
| --- | --- | --- | --- | --- |
| `mud-fever-support` | All-Natural Manuka Honey Pet Shampoo | `RQ-PET-SHAMPOO` | Mud/crust softening for grooming, pasture debris removal, gentle lower-leg cleanse | Curing pastern dermatitis (mud fever), antifungal/antibacterial drug claims |

Horse lane may ladder to horse-specific treatment cream (`RQ-PET-HORSECREAM`) only when formula/label substantiation matches copy.

### Expansion lanes (matrix only — briefs pending)

The product-to-problem matrix defines additional expansion opportunities not yet fully briefed. Until a lane brief and owner sign-off exist, treat these as **draft routing only**:

| Canonical product | Expansion problems (draft) | Constraint reminder |
| --- | --- | --- |
| All-in-One Intensive Skin Treatment Cream | Baby diaper rash, skin chafing, cradle cap comfort | Single Parent Rule; baby/drug classification review |
| Pet Skin Treatment Cream | Senior dog elbow calluses, skin fold dermatitis | Lick-safe, donation claim proof, pet-health zone |
| Men's Face & Body Moisturizer | Pseudofolliculitis, shaving bumps | No CBD cross-sell |
| Manuka Honey Shampoo (human) | Excess sebum control | No scalp disease treatment claims |
| Manuka Honey Pet Shampoo | Equine mud fever (see brief above) | Large-animal copy must match label |
| Low-Glycemic Diabetic Dog Treats | Diabetic training rewards, weight-management rewards | Strict human-checkout isolation; medical-adjacent flag; no diagnostic diabetic claims |

### Pre-publish review checklist (per lane)

Before any Target Problem Lane ships:

- [ ] Lane slug, canonical SKU, and compliance zone (`baby`, `pet-health`, `medical-adjacent`, standard) documented.
- [ ] Hero, matrix, ladder, and proof copy reviewed against this section and Medical/Pet/Baby caution tables above.
- [ ] Risky draft phrases replaced (e.g. "eliminates," "cellular micro-repair," "clears acne," "cures," undocumented "vet-recommended").
- [ ] Routine ladder products and bundle upgrade SKUs verified in catalog; CBD and audience isolation confirmed.
- [ ] Before/after assets and testimonials permissioned; donation language verified against operational policy.
- [ ] Owner sign-off recorded in compliance checklist §7.

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
- Target Problem Lane pages follow [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md) § Target Problem Lane Copy Rules; draft strategy briefs are not production copy.
- Return/refund/shipping copy must match actual policy.
- If a claim requires proof, the doc or PR must identify the proof source before implementation.
