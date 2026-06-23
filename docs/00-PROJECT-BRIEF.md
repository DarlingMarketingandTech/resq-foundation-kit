# 00 — Project Brief

> Status: **Phase 8 next** — Phases 1–7 and real catalog import are complete. See [`CHECKPOINT.md`](CHECKPOINT.md) for versions and runbooks.

## Mission

ResQ Foundation Kit is the **routine-commerce platform foundation** for a premium WordPress + WooCommerce storefront built as a **theme + companion plugin pair**:

- **Theme:** `wp-content/themes/resq-clean-pro/` — presentation, layout, WooCommerce template overrides, storefront UI
- **Plugin:** `wp-content/plugins/resq-core/` — commerce data model, settings, Woo hooks, helpers, compliance rules

The mission is to turn audience-led shopping, problem-led discovery, routine-led merchandising, and canonical product discipline into a safe implementation contract. Agents and developers should be able to build ResQ storefront surfaces without duplicating products, blurring theme/plugin ownership, or hardcoding source-blueprint examples as final catalog truth.

## Strategic North Star

ResQ should feel like a **routine-commerce** store, not a flat SKU grid. Customers enter through human, pet, bundle, and Learn paths; they refine by audience, problem, concern, routine, or ingredient; the storefront routes them back to a small set of canonical WooCommerce products and bundles.

Core strategy:

- **Audience-led shopping:** People, pets, CBD, bundles, and Learn are planning-level navigation concepts.
- **Problem-led discovery:** Landing pages can speak to skin comfort, grooming, scalp care, pet hot spots, coat care, and similar concerns without creating duplicate products.
- **Routine-led merchandising:** PDPs and cart surfaces should show steps, kits, and bundle logic where it reduces choice fatigue.
- **Canonical product discipline:** One real product record owns inventory, price, variations, and checkout behavior; many front-end routes may point to it.
- **Compliance isolation:** CBD and other sensitive claims stay isolated by data flags, navigation, cross-sell rules, copy rules, and checkout notice placement.

## What This Repo Is

| Layer | Contents |
|---|---|
| Architecture docs | Project brief, theme/plugin contract, brand foundation, Woo template map, merchandising system, compliance rules, roadmap, IA, routine-commerce framework, canonical product strategy, source-blueprint index |
| Source blueprints | Preserved reference material in `docs/source-blueprints/`; examples only, not final product truth |
| Agent infrastructure | `AGENTS.md`, `.codex/skills/`, `.codex/agents/` |
| Implementation code | `resq-core` `0.4.0` and `resq-clean-pro` `0.4.0` — routine-commerce model, gateways, fixtures, catalog import |
| Data import | `wp resq-fixtures` (demo) and `wp resq-catalog` (real `RQ-*` SKUs) — see docs `18` and `19` |

Agents and humans treat `docs/` as the source of truth for implementation boundaries. Source blueprints inform these docs but are not edited in place.

## What This Repo Is Not

- A full WordPress core install
- A turnkey production WooCommerce site
- A finished product catalog, taxonomy tree, or copy deck
- A source of final legal, medical, veterinary, CBD, donation, or ad-policy claims
- A place for new major PHP on surfaces whose docs and phase gate are not aligned
- A Medusa, Shopify, or headless storefront implementation
- A production database, staging deployment pipeline, payment integration, or email system

## Current Phase

Phases 1–7 and catalog import are **complete**. Phase 8 (merchandising behavior) is next. Local catalog import and storefront smoke tests passed on LocalWP. See [`CHECKPOINT.md`](CHECKPOINT.md).

## Implementation Target

The immediate implementation target remains a **standard WordPress + WooCommerce theme/plugin pair**:

- Classic PHP templates are the baseline.
- WooCommerce owns product, cart, checkout, order, account, price, stock, tax, and gateway primitives.
- `resq-core` owns ResQ-specific data, mappings, helpers, compliance flags, and Woo logic.
- `resq-clean-pro` owns storefront layout and rendering.
- LocalWP or DDEV is used for sandbox validation.

No architecture doc should imply this repo includes WordPress core or a live store.

## Platform-Aware Posture

The foundation is WooCommerce-first but should remain **portable enough to compare platforms later**. Keep the routine-commerce model expressed in platform-neutral concepts before binding it to Woo-specific implementation:

| Concept | Platform-neutral meaning | WooCommerce target |
|---|---|---|
| Audience | Who the shopper is buying for | Product taxonomy/meta + landing routes |
| Concern/problem | What the shopper is trying to solve | Taxonomy/meta + Learn/category pages |
| Routine | Ordered steps or repeatable regimen | Product meta, bundle relationships, PDP/cart UI |
| Canonical product | Single source for buyable item | Woo product or variation parent |
| Bundle/kit | Curated group with pricing/savings rules | Bundle extension or documented plugin rules |
| Compliance flag | Sensitive product/content constraint | Product/term meta + helper-driven notices |

This posture keeps future comparison against WooCommerce, Medusa, Shopify, or another platform possible without rewriting the business model. Platform-specific implementation lives below the architectural concepts, not above them.

## Target Site Experience

The finished storefront should feel:

- **Fast:** scannable PLP/PDP, optimized media, low layout shift
- **Accessible:** WCAG 2.2 AA target; keyboard-operable cart and checkout
- **Trust-building:** honest product, mission, shipping, returns, and compliance messaging
- **Routine-aware:** shoppers understand what to buy alone, what completes a routine, and what belongs in a kit
- **Category-clear:** global navigation stays stable while filters refine within collections
- **Agent-safe to extend:** every surface maps to a doc and each behavior has an owner

## Core Audiences

| Audience | Primary need | Storefront implication |
|---|---|---|
| Human care shoppers | Personal care, skin, grooming, hair/scalp, baby-safe discovery | Human gateway pages, routine cards, cautious benefit language |
| Pet owners / adopters | Pet topical care, coat/grooming, treats, mission trust | Pet gateway pages, concern cards, pet health caution |
| CBD shoppers | Isolated and compliant CBD discovery | CBD-specific navigation, flags, notices, cross-sell restrictions |
| Bundle/value shoppers | Complete routines and transparent savings | Bundles/savings landing, kit cards, routine ladders |
| Learners / supporters | Ingredient, application, and mission context | Learn-to-shop bridges that point to canonical products |
| Developers and agents | Safe boundaries and phased implementation | Contract docs, roadmap gates, no production writes |

## Goals

- [x] Align docs around routine-commerce, canonical products, CBD isolation, and source-blueprint traceability
- [x] Preserve theme/plugin separation: theme renders, plugin decides and supplies data
- [ ] Map all WooCommerce storefront surfaces before Phase 5 override work expands
- [ ] Keep source-blueprint examples as references, not hardcoded catalog data
- [ ] Support future platform comparison by documenting concepts separately from Woo details
- [ ] Deliver phased implementation with verification gates

## Non-goals

- Rewriting source blueprint files
- Adding major PHP on unscoped surfaces before docs and phase gates align
- Creating real product SKUs, prices, claims, or legal copy from blueprint examples
- Importing production catalog, customer, order, or analytics data
- Implementing production CBD, donation, payment, or advertising compliance workflows
- Re-platforming away from WooCommerce in this foundation phase

## Stack North Star

**Adopt now:** CodeGraph · Addy-style lifecycle · Stop Slop · Taste Skill (constrained)

**Later:** Understand Anything · Headroom · cherry-picked security skills · Agent-Reach (research only)

See `AGENTS.md` for agent operating rules.

## Architecture Decisions

| Decision | Options considered | Chosen | Rationale |
|---|---|---|---|
| Commerce foundation | WooCommerce · Medusa · Shopify · custom | **WooCommerce-first foundation** | Current repo is a WP/Woo theme/plugin kit; concepts remain portable |
| Theme base | Classic PHP · Block/FSE · Sage + Vite | **Classic PHP** | Direct Woo template control and low bootstrap complexity |
| Theme/plugin split | Monolith · Paired layers | **Paired layers** | Theme owns display; plugin owns data/logic |
| Product strategy | Duplicate per route/species · Canonical parent | **Canonical parent rule** | Protects inventory, SEO, margins, and operations |
| Navigation model | Dynamic hidden menu · Stable global nav | **Stable global nav with filters** | Preserves browsing habits; filters refine inside surfaces |
| CBD handling | Mixed catalog · Isolated sections | **Isolated by flags/routes/rules** | Reduces payment, advertising, and compliance risk |
| Source blueprints | Rewrite into final docs · Preserve as references | **Preserve unchanged, index them** | Keeps provenance while preventing example copy from becoming product truth |

## Open Decisions

| Decision | Notes |
|---|---|
| Final catalog taxonomy | Phase 7+; blueprint categories are examples only |
| Bundle engine | WooCommerce Product Bundles extension vs plugin-managed rules; decide by Phase 8 |
| Final CBD/legal copy | Requires compliance review before production use |
| Donation/mission mechanism | Requires operational proof and policy review before storefront claims |
| Local sandbox tool | LocalWP or DDEV; choose per developer machine |
| Hosting/PHP version | PHP 8.1+ required; exact host TBD |
| Payment/email sandbox policy | Deactivate live keys locally; test mode only |

## Repository Layout

```text
resq-foundation-kit/
  AGENTS.md                    # Agent operating guide
  docs/                        # Architecture docs, runbooks, archive
    CHECKPOINT.md              # Current status (start here)
    source-blueprints/         # Preserved source strategy references
    archive/phase-notes/       # Historical phase delivery records
  wp-content/
    themes/resq-clean-pro/     # Presentation layer
    plugins/resq-core/         # Business logic, fixtures, catalog import
  scripts/                     # CSV generator, taxonomy seed snippet
  fixtures/                    # Placeholder (.gitkeep); data lives in plugin
```

## Success Criteria

- [ ] Docs `00` through `10` are internally consistent
- [ ] Source blueprint index maps each source file to the architecture docs it informs
- [x] Theme/plugin contract names all routine-commerce helper surfaces (implemented in Phase 3)
- [ ] Woo template map covers global nav, gateway pages, PLP/PDP, cart drawer, cart, checkout, account, Learn, bundles, FBT, routine ladders, and compliance notice slots
- [ ] Roadmap includes branch `foundation-blueprint` and tag `v0.1-foundation-blueprint`
- [ ] No source blueprint file was rewritten in place

## Read Next

1. [`CHECKPOINT.md`](CHECKPOINT.md) — current versions and phase status
2. [`01-THEME-PLUGIN-CONTRACT.md`](01-THEME-PLUGIN-CONTRACT.md) — layer ownership
3. [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md) — Phase 8+ gates
4. [`18-PHASE-7-IMPLEMENTATION-NOTES.md`](18-PHASE-7-IMPLEMENTATION-NOTES.md) — demo fixtures runbook
5. [`19-CATALOG-IMPORT-NOTES.md`](19-CATALOG-IMPORT-NOTES.md) — real catalog runbook
