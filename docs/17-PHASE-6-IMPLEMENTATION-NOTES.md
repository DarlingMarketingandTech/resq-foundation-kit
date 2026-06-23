# 17 — Phase 6 Implementation Notes

> Gateway and Learn presentation shells for `resq-clean-pro`.

## Checkpoint Context

| Item | Value |
| --- | --- |
| Phase 5 | WooCommerce template shells — complete |
| Phase 6 | Gateway and Learn surfaces — in progress |
| Theme version | `0.4.0` |
| Branch target | `phase-6-gateway-learn-surfaces` |
| Tag target | `v0.7-phase-6-gateway-learn-surfaces` |

---

## What Phase 6 Implements

### New page templates

| Path | Template Name | Surface |
| --- | --- | --- |
| `page-gateway-human.php` | ResQ Gateway: Human | Human audience gateway |
| `page-gateway-pet.php` | ResQ Gateway: Pet | Pet audience gateway |
| `page-gateway-bundles.php` | ResQ Gateway: Bundles & Savings | Bundle/value landing |
| `page-gateway-cbd.php` | ResQ Gateway: CBD | CBD-isolated gateway |
| `page-concern-landing.php` | ResQ Concern Landing | Concern/problem landing |
| `page-learn-index.php` | ResQ Learn Index | Learn index and bridge |

### New gateway template parts

| Path | Purpose | Empty-safe behavior |
| --- | --- | --- |
| `template-parts/gateway/hero.php` | Page title and editorial intro | Renders title only when content is empty |
| `template-parts/gateway/product-shelf.php` | Plugin-driven product cards | Returns when product IDs or card payloads are empty |
| `template-parts/gateway/concern-cards.php` | Concern card grid | Returns when context has no concerns |
| `template-parts/gateway/filter-shell.php` | Structural filter UI shell | Renders placeholders when filters are not configured |
| `template-parts/gateway/cbd-notice.php` | CBD compliance strip | Notice content is delegated to plugin helper fallback |
| `template-parts/gateway/page-shell.php` | Shared gateway template orchestration | Returns when no gateway slug is supplied |

### New Learn template parts

| Path | Purpose | Empty-safe behavior |
| --- | --- | --- |
| `template-parts/learn/guide-card.php` | Guide card for Learn index | Returns when no `WP_Post` is supplied |
| `template-parts/learn/product-bridge.php` | Learn-to-shop product shelf | Returns when product IDs are empty |

### New CSS

| Path | Scope |
| --- | --- |
| `assets/css/gateway.css` | Gateway, concern landing, CBD isolation, filter shell, Learn index, and Learn product bridge styles |

`gateway.css` is enqueued after `components.css` and before the theme stylesheet.

---

## Plugin Helpers Consumed

| Helper | Surface |
| --- | --- |
| `resq_resolve_product_context()` | Gateway pages, concern landing, Learn index |
| `resq_get_gateway_featured_products()` | Gateway product shelves and Learn fallback shelf |
| `resq_get_product_card_data()` | Gateway and Learn product cards |
| `resq_get_learn_links_for_product()` | Learn product bridge verification |
| `resq_theme_render_compliance_notices()` | CBD gateway notice strip and product card notices |

All helper use is guarded by `function_exists()` or the existing `resq_core_is_active()` pattern where product helper data is required.

---

## Compliance and Data Boundaries

- Gateway templates do not hardcode product IDs, product slugs, prices, badges, or claims.
- Concern cards only link when plugin context supplies a URL; the theme does not invent final concern routes.
- CBD receives a distinct wrapper class (`resq-gateway--cbd`) and notice strip (`resq-cbd-notice`).
- Product card copy is limited to data returned by `resq_get_product_card_data()`.
- Filter UI is a structural shell only; final taxonomy wiring is deferred to Phase 8.
- Source-blueprint examples remain examples only and are not copied into templates.

---

## Exit Criteria

- [x] Gateway pages render without hardcoded final product truth.
- [x] Learn bridges use plugin helper fallbacks.
- [x] CBD visual isolation is present.
- [x] Source-blueprint examples remain examples only.

---

## Deferred

| Item | Phase |
| --- | --- |
| Final product filtering behavior | 8 |
| Bundle card behavior and savings math | 8 |
| Cart drawer suggestions | 8 |
| Final Learn guide content workflow | 7 or later |
| Production route slugs and seeded pages | 7 or 9 |

---

## Read Next

1. `06-BUILD-ROADMAP.md` — Phase 7 fixture system
2. `11-PLUGIN-DATA-SCHEMA.md` — gateway and Learn mapping data
3. `12-PLUGIN-HELPER-CONTRACTS.md` — helper signatures consumed by templates
