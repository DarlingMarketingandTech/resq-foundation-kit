# 08 — Routine-Commerce Framework

> Defines routines, steps, kits, bundles, collections, products, Learn guides, and the data/UI needed to support them.

## Core Terms

| Term | Meaning | Example use |
|---|---|---|
| Audience | Who the shopper is buying for | Human, pet |
| Concern/problem | What the shopper is trying to solve | Dry skin, hot spots, scalp care |
| Routine | A repeatable regimen with ordered steps | Daily skincare, pet coat care |
| Step | A slot inside a routine | Cleanse, treat, restore |
| Product | A canonical WooCommerce buyable item | Single cream, shampoo, conditioner |
| Canonical product | Single source of truth for a buyable item across all routes | One Manuka cream shared by human and pet landing pages |
| Collection | A browsable grouping | Human skincare, pet grooming |
| Kit | Curated set of products for a routine | 3-step starter kit |
| Bundle | Buyable grouped offer with pricing/cart behavior | Routine bundle, replenishment multipack |
| Learn guide | Editorial explanation that can point to products/routines | Ingredient or application guide |
| Learn-to-shop bridge | Plugin mapping from Learn content to canonical products | Ingredient guide → product shelf |
| Gateway page | Audience or category entry page with editorial context | Human gateway, pet gateway, CBD gateway |
| PDP | Product detail page (single canonical product view) | `single-product.php` |
| PLP | Product listing page (archive, category, or search results) | `archive-product.php` |
| Product card | Reusable product summary UI in grids and shelves | Image, title, price, badge, CTA |
| Front-end route | URL path that provides shopping context | `/shop/human/`, `/shop/pet/topical-care/` |
| Compliance notice | Plugin-driven notice for regulated or sensitive content | CBD disclaimer, baby caution, proof requirement |

## Routine Rules

- Routines are plugin-owned data.
- Steps are ordered and may point to canonical products.
- A product can belong to multiple routines.
- A routine can have an optional bundle/kit target.
- The theme renders routine ladders only when helper data exists.
- Routine UI must not imply guaranteed outcomes.

## Human Routine Examples

Examples from the blueprints can inform fixture ideas:

- Daily skincare: cleanse, treat, restore
- Men's grooming: wash/shave prep, moisturize, night repair
- Hair/scalp care: shampoo, conditioner
- Baby/infant care: gentle wash, comfort balm

These examples require compliance-safe copy before production use.

## Pet Routine Examples

Examples from the blueprints can inform fixture ideas:

- Pet topical care: cleanse, apply comfort product, maintain coat
- Coat/grooming: shampoo, conditioner
- Specialty treats: replenishment multipack
- Pet CBD/calming: isolated CBD routine only if compliance-approved

Pet routines must include veterinary caution where needed.

## PDP Routine Ladders

PDP routine ladders show how the current product fits into a regimen.

Theme UI should support:

- Routine title and short explanation.
- Ordered steps.
- Current product marker.
- Optional add-step CTA.
- Optional upgrade-to-kit CTA.
- Empty state that hides the whole block.

Plugin data needed:

- Routine ID/title.
- Step order/title.
- Product IDs for each step.
- Current-product status.
- Optional bundle/kit target.
- Compliance restrictions.

## Bundle Composition

Bundles and kits need a clear composition model:

| Field | Owner | Notes |
|---|---|---|
| Bundle product ID | Woo/plugin | Buyable target |
| Included products | plugin | Product IDs and quantities |
| Savings display | plugin | Must match real pricing |
| Cart validation | plugin/Woo | Depends on bundle engine |
| Bundle card display | theme | No pricing math in theme |

Open decision: WooCommerce Product Bundles extension vs grouped products vs custom plugin-managed behavior.

## Cart Drawer Suggestions

Cart drawer suggestions should be light and optional:

- Matching routine step.
- Complete-the-kit suggestion.
- FBT suggestion.
- Replenishment multipack.

Restrictions:

- Never block checkout.
- Never mix CBD into standard suggestions by default.
- Respect audience boundaries.
- Hide suggestions when plugin data is unavailable.

## Learn Guide Distinctions

Learn guides educate; they do not sell directly unless a plugin mapping supplies a product bridge.

| Learn guide type | Bridge |
|---|---|
| Ingredient guide | Ingredient profile -> canonical products |
| Concern guide | Concern mapping -> product/routine |
| Routine guide | Routine steps -> products/bundle |
| Mission guide | Policy/mission links -> optional products only if claim-safe |

## Plugin Data Needed

- Audience records.
- Concern/problem records.
- Routine records.
- Routine step records.
- Product-to-routine mappings.
- Product-to-concern mappings.
- Canonical product resolver.
- Bundle/kit composition.
- FBT relationships.
- CBD/compliance flags.
- Ingredient profiles.

## Theme Components Needed

- Audience gateway layout.
- Concern card/grid.
- Product card routine/bundle hints.
- PDP routine ladder.
- Bundle card.
- Bundle PDP block.
- Cart drawer suggestion card.
- Ingredient profile block.
- Compliance notice slot.
- Learn product bridge.

## Implementation Guardrail

Do not add major PHP implementation until the helper contracts and data ownership in `01-THEME-PLUGIN-CONTRACT.md` are accepted. Initial code should be stubs, empty-safe template slots, and fixture-safe display scaffolds.
