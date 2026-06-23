# Phase 8 — Merchandising Behavior

> **Implementation authority** for Phase 8. Turn documented routine-commerce data into useful storefront behavior (`resq-core` + `resq-clean-pro`).

## Checkpoint context

| Item | Value |
| --- | --- |
| Phases 1–7 + catalog import | Complete — see [`CHECKPOINT.md`](CHECKPOINT.md) |
| Phase 8 | Merchandising behavior — **in progress** |
| Plugin version | `0.4.0` (target `0.5.0` after Phase 8 gate) |
| Theme version | `0.4.0` (target `0.5.0` after Phase 8 gate) |
| Catalog data | `wp resq-catalog import` — real `RQ-*` SKUs (preferred for smoke tests) |
| Demo data | `wp resq-fixtures import` — `fixture-*` SKUs (sandbox only) |

**Architecture references (do not duplicate here):** [`04-PRODUCT-MERCHANDISING-SYSTEM.md`](04-PRODUCT-MERCHANDISING-SYSTEM.md), [`03-WOO-TEMPLATE-MAP.md`](03-WOO-TEMPLATE-MAP.md), [`11-PLUGIN-DATA-SCHEMA.md`](11-PLUGIN-DATA-SCHEMA.md), [`12-PLUGIN-HELPER-CONTRACTS.md`](12-PLUGIN-HELPER-CONTRACTS.md), [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md).

---

## Architecture decisions (locked for Phase 8)

### Bundle engine — plugin-managed composition

**Decision:** Use **plugin-managed bundle composition** on standard WooCommerce **simple products**. Do **not** adopt the WooCommerce Product Bundles extension for Phase 8.

| Approach | Status |
| --- | --- |
| WooCommerce Product Bundles extension | Rejected for foundation kit — external dependency, theme coupling |
| Woo grouped products | Rejected — weak savings/validation story for routine kits |
| **Simple product + `_resq_bundle_product_ids` meta** | **Adopted** — already used by `wp resq-catalog` and `wp resq-fixtures` |

**Implications:**

- Bundle SKUs (`RQ-KIT-*`, `RQ-DUO-*`, `RQ-PK-*`) are `WC_Product_Simple` records with composition meta.
- Phase 8 owns **display** (bundle PDP block, gateway bundle cards), **savings math** (`bundle_savings` in ladder payload), and **cart validation** (included IDs canonical, in stock, compliance-compatible before add).
- Single “add bundle to cart” may add the bundle simple product only in v1; expanding to multi-line composition is optional if validation-only is insufficient — document any change in this file.

### Checkout isolation — deferred

**Decision:** Phase 8 does **not** implement isolated checkout (stripped header/nav at checkout). Keep the Phase 5 checkout shell. Revisit in Phase 10 compliance QA per [`03-WOO-TEMPLATE-MAP.md`](03-WOO-TEMPLATE-MAP.md).

### Cart drawer — commerce drawer, not mobile nav

The theme **mobile navigation drawer** (`template-parts/global/mobile-drawer.php`) is complete. Phase 8 **cart drawer** is a separate surface: post–add-to-cart suggestions via `resq_get_recommended_routine_addons()` — see [`03-WOO-TEMPLATE-MAP.md`](03-WOO-TEMPLATE-MAP.md) planned path `template-parts/cart/drawer.php`.

---

## Shell vs behavior matrix

Audit baseline at Phase 8 start (`0.4.0`). **Shell** = markup/CSS/hooks exist; **Behavior** = live data, restrictions, or cart/query logic wired.

| Surface | Plugin data | Theme shell | Phase 8 behavior work |
| --- | --- | --- | --- |
| Product badges | `resq_get_product_badges()` — live | `content-product.php`, gateway shelf | Default badge rules; card subtitle/tags polish |
| Routine ladder | `resq_get_product_routine_ladder()` — live | `routine-ladder.php` | `bundle_savings` display; optional step add-to-cart |
| Ingredient profile | `resq_get_product_ingredient_profile()` — live | **Missing** `ingredient-profile.php` | Create template part; PDP slot already hooked |
| FBT block | `resq_get_frequently_bought_together()` — live | **Missing** `frequently-bought-together.php` | Create template part; combined add UI; respect `resq_can_cross_sell_products()` |
| Bundle PDP | `resq_get_bundle_products()` — live | **Missing** `bundle-options.php` | Included items list, savings vs sum of parts, validation messaging |
| Bundle cards (gateway/PLP) | `resq_get_product_card_data()` — live | Gateway shelf only | Optional `bundle-card.php` for bundles landing |
| Cart drawer suggestions | `resq_get_recommended_routine_addons()` — live | **Missing** `cart/drawer.php` | Drawer UI + fragment/AJAX hook; must not block checkout |
| Cross-sell / related | `resq_can_cross_sell_products()` — live | Woo native related | Filter `woocommerce_related_products`, cart cross-sells |
| PLP / gateway filters | `resq_resolve_product_context()` partial | `filter-shell.php` placeholder | Query hooks (`pre_get_posts` / `woocommerce_product_query`); interactive filter UI |
| CBD isolation in merchandising | `resq_is_cbd_product()`, zone meta | Card/PDP classes | Enforce on FBT, related, cart suggestions, filters |

---

## Scope

### In scope

1. **Badges** — populate `resq_core_merchandising.default_badge_config`; ensure PLP, gateway shelf, and PDP show claim-safe badges.
2. **Routine ladders** — savings copy on bundle upgrade CTA; keep empty-safe when no routine data.
3. **FBT** — PDP block with restricted product set; optional cart integration.
4. **Bundle PDP** — composition list, savings, stock state; pre-add validation in plugin.
5. **Cart drawer suggestions** — next routine step / kit upgrade after add-to-cart.
6. **Cross-sell restrictions** — CBD zone, audience mismatch, baby flag rules on all merchandising surfaces.
7. **Product filters** — audience/concern/format (and plugin taxonomies) on shop, category, and gateway contexts.

### Out of scope (later phases)

- Product images (merchandising pass / media workflow)
- Subscribe-and-save, bulk breaks (pricing strategy only — see [`19-CATALOG-IMPORT-NOTES.md`](19-CATALOG-IMPORT-NOTES.md))
- Admin merchandising settings UI (schema exists; WP-CLI/options only for now)
- Isolated checkout mode (Phase 10)
- Full Phase 9 fresh-install gate (run smoke here; formal gate remains in [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md))
- WooCommerce Product Bundles extension

---

## File map

### Plugin (`resq-core`)

| Path | Phase 8 role |
| --- | --- |
| `includes/helpers/storefront.php` | Badge rules, ladder savings, FBT, bundle read, cross-sell gate, card data |
| `includes/helpers/infrastructure.php` | `resq_core_get_cross_sells()` wrapper |
| `includes/class-options.php` | `resq_core_merchandising` defaults |
| `includes/registrations/class-post-meta.php` | `_resq_bundle_product_ids`, `_resq_fbt_product_ids`, badge meta |
| `includes/catalog/class-catalog-importer.php` | Reference for bundle simple-product pattern |
| **New (expected)** `includes/woocommerce/class-merchandising-hooks.php` | Query filters, related/cross-sell filters, bundle cart validation |
| **New (expected)** `includes/woocommerce/class-bundle-cart.php` | Validate composition on add-to-cart (if not inline in hooks class) |

Register new classes from `resq-core.php` only after hooks are documented here.

### Theme (`resq-clean-pro`)

| Path | Phase 8 role |
| --- | --- |
| `inc/woocommerce.php` | PDP slots (routine, ingredient, FBT) — already hooked |
| `woocommerce/content-product.php` | PLP badge + compliance slots |
| `template-parts/product/routine-ladder.php` | Extend for savings display |
| `template-parts/gateway/product-shelf.php` | Gateway cards — badges live |
| `template-parts/gateway/filter-shell.php` | Wire real filter controls |
| `assets/css/components.css` | Badge, ladder, FBT, bundle, drawer styles |
| **Create** `template-parts/product/ingredient-profile.php` | PDP ingredient module |
| **Create** `template-parts/product/frequently-bought-together.php` | PDP FBT module |
| **Create** `template-parts/product/bundle-options.php` | Bundle PDP composition block |
| **Create** `template-parts/cart/drawer.php` | Commerce cart drawer |
| **Optional** `template-parts/product/bundle-card.php` | Bundles gateway / savings landing |
| **New (expected)** `assets/js/cart-drawer.js` | Drawer open/close, focus trap, fragment refresh |

---

## Implementation workstreams

Work in roadmap order. Each stream should stay theme/plugin clean per [`01-THEME-PLUGIN-CONTRACT.md`](01-THEME-PLUGIN-CONTRACT.md).

### Stream A — Missing PDP template parts

1. Add `ingredient-profile.php` — consume `resq_get_product_ingredient_profile()`; hide when empty.
2. Add `frequently-bought-together.php` — consume `resq_get_frequently_bought_together()`; hide when empty; no CBD leakage.
3. Add `bundle-options.php` on bundle PDPs — detect `_resq_bundle_product_ids` via `resq_get_bundle_products()`; show included lines and savings.

### Stream B — Routine ladder and badge polish

1. Compute `bundle_savings` in `resq_get_product_routine_ladder()` (compare bundle price vs sum of step product prices).
2. Render savings in `routine-ladder.php` bundle CTA (claim-safe; no false “% off” without data).
3. Seed sensible `default_badge_config` in `ResQ_Core_Options::default_merchandising()` (e.g. `on_sale`, `is_bundle`).

### Stream C — Cross-sell and related restrictions

1. Filter Woo related products: `woocommerce_related_products` or query hook — apply `resq_can_cross_sell_products()`.
2. Filter cart cross-sells similarly.
3. Ensure FBT manual IDs and Woo cross-sell fallback both respect the same gate (already in `resq_get_frequently_bought_together()`).

### Stream D — Bundle cart validation

1. On add-to-cart for bundle simple products, validate included products: exist, in stock, compliance-compatible with bundle zone.
2. Surface admin-notice or cart error message when validation fails — do not silent-fail into broken orders.
3. Document behavior in [`11-PLUGIN-DATA-SCHEMA.md`](11-PLUGIN-DATA-SCHEMA.md) § Bundle to products validation (change “Phase 8” to implemented when done).

### Stream E — Cart drawer suggestions

1. Create `template-parts/cart/drawer.php` — suggestion card from `resq_get_recommended_routine_addons()`.
2. Hook Woo add-to-cart (AJAX + non-AJAX) to open drawer when `cart_drawer_suggestions_enabled`.
3. Focus trap, escape close, no blocking overlay on checkout route.

### Stream F — Product filters

1. Plugin: register `pre_get_posts` / `woocommerce_product_query` handlers for `resq_audience`, `resq_concern`, `resq_product_role`, compliance zone.
2. Theme: upgrade `filter-shell.php` from placeholder list to real form controls (GET params).
3. Gateway pages: pass resolved context from `resq_resolve_product_context()` into filter shell.

---

## Local smoke recipe

Run from WordPress site root (LocalWP **Open site shell**). **Windows CMD:** no inline `#` comments.

```bat
wp resq-catalog import
```

Suggested URLs / SKUs after import (adjust host):

| Check | Target |
| --- | --- |
| Routine ladder + bundle CTA | Variable PDP e.g. `RQ-HUM-AIOCREAM` (routine-linked SKU) |
| Bundle composition block | `RQ-KIT-PET-HOTSPOT` or any `RQ-KIT-*` |
| CBD isolation | Human standard PDP — confirm FBT/related do not show `RQ-HCBD-*` or `RQ-PCBD-*` |
| CBD gateway | `/shop/cbd/` — isolated shelf |
| FBT | Product with `_resq_fbt_product_ids` in catalog data |
| Gateway shelf + badges | `/shop/human/`, `/shop/bundles/` |
| Cart drawer | Add routine step product — expect next-step suggestion |
| Filters | Shop or category archive with `?` filter params once wired |

Backup before destructive tests:

```bat
wp db export backups/before-phase8-smoke.sql
```

---

## Phase 8 exit criteria (from [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md))

- [ ] Routine and bundle UI improves clarity without blocking checkout
- [ ] Cross-sells respect CBD, audience, and baby/pet restrictions on PDP, cart, and drawer
- [ ] Product cards remain claim-safe (badges/subtitles only from plugin data)
- [x] Bundle engine decision documented (this file § Architecture decisions)
- [ ] Missing template parts from [`03-WOO-TEMPLATE-MAP.md`](03-WOO-TEMPLATE-MAP.md) Phase 8 rows implemented or explicitly deferred with reason
- [ ] Catalog smoke recipe above passes on LocalWP

When complete: update [`CHECKPOINT.md`](CHECKPOINT.md), archive this file to `docs/archive/phase-notes/` at a later cleanup, and add Phase 8 row to release markers in `06`.

---

## Read next

1. [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md) — Phase 8 gate and global verification
2. [`19-CATALOG-IMPORT-NOTES.md`](19-CATALOG-IMPORT-NOTES.md) — catalog import before smoke tests
3. [`04-PRODUCT-MERCHANDISING-SYSTEM.md`](04-PRODUCT-MERCHANDISING-SYSTEM.md) — merchandising principles
4. [`CHECKPOINT.md`](CHECKPOINT.md) — update when Phase 8 ships
