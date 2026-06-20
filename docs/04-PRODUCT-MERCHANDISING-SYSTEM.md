# 04 — Product Merchandising System

> How products are discovered, related, badged, bundled, and presented. Source blueprint examples inform structure only; they are not final product, price, claim, taxonomy, or copy truth.

## Merchandising Principles

1. **Audience-led first:** Shoppers start with who they are buying for: human, pet, CBD, bundle/value, or Learn.
2. **Problem-led second:** Within audience paths, shoppers refine by concern such as dry skin, grooming, scalp care, pet hot spots, coat care, or specialty treats.
3. **Routine-led when useful:** PDPs and bundle pages should show ordered steps when it makes the buying decision easier.
4. **Canonical products always:** Multiple front-end paths can market the same item, but inventory, price, variations, and checkout point to one canonical WooCommerce product.
5. **Compliance-aware by default:** CBD, medical-adjacent, pet health, baby/infant, before/after proof, and donation claims require safer copy and data flags.

## Product Data Model

| Feature | Plugin data | Theme consumer | Notes |
|---|---|---|---|
| Audiences | Audience taxonomy/meta | Gateway cards, filters, PDP labels | Examples: human, pet; final terms TBD |
| Concerns/problems | Concern taxonomy/meta | Concern landing pages, filters | Avoid cure/treatment language |
| Routines | Routine definitions | PDP routine ladder, bundle pages | Ordered steps with current-product marker |
| Routine steps | Step title, product target, optional bundle target | Routine checklist UI | Keep optional and empty-safe |
| Canonical mapping | Route/page/term to product ID | Product links, Learn bridges | Prevent duplicate product records |
| Bundles/kits | Composition, quantities, savings | Bundle cards/PDP blocks/cart drawer | Extension choice still open |
| FBT | Product ID relationships | PDP FBT block | Must respect CBD isolation |
| CBD/compliance | Product/term/content flags | Notices and blocked cross-sells | See `05-COMPLIANCE-RULES.md` |
| Ingredient profile | Claim-safe ingredient descriptors | PDP/landing ingredient modules | Theme must not invent claims |
| Badges | Label/type/priority | Product card/PDP badge slots | Examples: New, Bundle, Sensitive Skin |

## Audience-Led Shopping

Audience gateways are content-rich landing pages that help shoppers choose a path before they face a product grid.

- Human paths may include skincare, grooming, hair/scalp, baby/infant, and CBD examples from the source blueprints.
- Pet paths may include topical care, coat/grooming, treats, and pet CBD examples.
- Bundle paths should group routines by outcome and audience.
- Learn paths educate and then point to canonical products.

Do not treat blueprint path names such as `/shop/human/womens-skincare` or `/shop/pet/topical-skin-care` as final routing decisions until information architecture is implemented.

## Problem-Led Discovery

Problem-led pages can target specific shopper intent while avoiding duplicate products. A concern page may talk about a use case, show Learn content, and display canonical product cards pulled by plugin mappings.

Example pattern:

- Front-end concern route: pet hot spot support
- Editorial copy: cautious comfort and routine language
- Product shelf: canonical pet skin treatment product and grooming routine steps
- Checkout: the same WooCommerce product record used everywhere else

## Routine-Led Merchandising

Routine ladders should answer, "What step is this, and what completes the regimen?"

| Routine element | Purpose | Owner |
|---|---|---|
| Routine | Named regimen or use case | plugin |
| Step | Ordered slot in the regimen | plugin |
| Current product marker | Shows what the shopper is viewing | plugin data, theme display |
| Optional upgrade | Bundle/kit target when available | plugin |
| UI state | Checkbox/list/card layout | theme |

Blueprint examples such as a 3-step Manuka routine are examples of the pattern, not final product truth.

## Canonical Parent Products

The storefront must avoid creating separate products just because marketing pages speak to different species, audiences, sizes, or concerns. Use one canonical product when formula, inventory, pricing, and fulfillment are the same.

- Size or format differences belong in WooCommerce variations when appropriate.
- Landing pages and Learn guides point to canonical products through plugin mappings.
- Filters and category pages narrow discovery without creating duplicate records.
- Source-blueprint phrases like "Single Parent Rule" are adopted as architecture, not as a specific SKU list.

See `09-CANONICAL-PRODUCT-STRATEGY.md`.

## Decoupled Front-End Content

Front-end pages may carry tailored copy, imagery, ingredient education, and problem framing while product purchase actions remain canonical.

Allowed:

- Audience gateway pages
- Concern landing pages
- Learn guides
- Ingredient education pages
- Bundle/savings landing pages

Not allowed:

- Duplicate product records for each page angle
- Hardcoded prices or stock in page copy
- Theme-only product relationships
- Claims copied from blueprints without compliance review

## Bundles and Savings

Bundles and kits sell complete routines or replenishment packs. The plugin owns bundle composition, savings source, validation, and cart behavior. The theme renders bundle cards, included products, and cart drawer suggestions.

Open decision: use WooCommerce Product Bundles extension, grouped products, or a plugin-managed bundle layer. Decide before implementing real bundle logic.

## Product Cards

Product cards should be scannable and modest:

- Image, title, price, badge, and primary CTA from Woo/plugin data
- Optional audience/concern/routine hints when plugin data exists
- Compliance-aware badge display
- No long claims, medical promises, or unsupported before/after claims
- CBD card treatment that does not blend into standard skincare/pet care cross-sells

## PDP Blocks

Recommended PDP order:

1. Product gallery and purchase summary
2. Compliance notice slot when required
3. Short benefit/attribute bullets sourced safely
4. Routine ladder when the product belongs to routines
5. Ingredient profile
6. Usage/application guidance
7. FBT or related products, respecting restrictions
8. Reviews/proof only when allowed and reviewed

## Cross-Sells and FBT

Cross-sells should improve clarity, not indiscriminately maximize basket size.

- Standard products should not recommend CBD products by default.
- CBD products should not recommend standard products unless compliance approves the context.
- Pet products should not cross-sell human products without clear intent.
- Baby/infant products need extra caution around claims and ingredient framing.
- Cart drawer suggestions should be optional and never block checkout.

## CBD Isolation

CBD isolation applies to navigation, categories, product cards, related products, FBT, cart suggestions, notices, and checkout. CBD products require `resq_is_cbd_product()`/`resq_requires_compliance_notice()` style data before theme UI can display them responsibly.

## Learn-to-Shop Bridges

Learn content can bridge to products through plugin mappings:

- Ingredient guide -> ingredient profile -> canonical products
- Problem guide -> concern mapping -> canonical products/routines
- Routine guide -> steps -> bundle/kit/product targets

Learn pages must never create hidden product truth in page copy. Product IDs and relationships come from the plugin.

## Blueprint Examples

The source blueprints provide useful examples:

- Stable global nav and filter refinements
- Human/pet gateway layout patterns
- Bundles/savings card structure
- Routine ladder UI concept
- Canonical parent product strategy
- CBD isolation and cross-sell safeguards

These examples are not final legal copy, prices, product names, routes, ingredients, claims, donation commitments, or inventory structure.
