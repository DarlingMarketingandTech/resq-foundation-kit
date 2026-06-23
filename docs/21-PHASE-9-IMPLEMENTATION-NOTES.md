# Phase 9 — Local Sandbox Validation

> **Implementation authority** for Phase 9. Prove the full kit works in a fresh local WordPress + WooCommerce install — no state carried over from Phase 8 dev environment.

## Checkpoint context

| Item | Value |
| --- | --- |
| Phase 8 | Merchandising behavior — complete (all streams A–F) |
| Phase 9 | Local sandbox validation — **in progress** |
| Plugin version | `0.4.0` (target `0.5.0` after Phase 9 gate) |
| Theme version | `0.4.0` (target `0.5.0` after Phase 9 gate) |
| Runbook reference | Phase 8 smoke recipe → [`20-PHASE-8-IMPLEMENTATION-NOTES.md`](20-PHASE-8-IMPLEMENTATION-NOTES.md) § Local smoke recipe |

**Architecture references:** [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md) § Phase 9, [`CHECKPOINT.md`](CHECKPOINT.md).

---

## Goal

Confirm the kit installs and functions correctly from scratch — no reliance on a pre-seeded local environment. This is the formal gate before targeting `0.5.0` and proceeding to Phase 10 compliance/accessibility/performance QA.

---

## Fresh-install procedure

Run from LocalWP **Open site shell** at the WordPress site root. All commands are single-line (no inline `#` bash comments on Windows CMD).

### 1. Provision fresh site

Create a new LocalWP site (WordPress + WooCommerce). Do not copy files from the Phase 8 dev environment.

### 2. Activate theme and plugin

```bat
wp theme activate resq-clean-pro
wp plugin activate resq-core
wp plugin list
wp theme list
```

Expected: both active, no fatal errors.

### 3. WooCommerce baseline

```bat
wp wc tool run install_pages --user=1
wp option get woocommerce_db_version
```

Expected: WooCommerce pages installed, DB version returned.

### 4. Import catalog

```bat
wp db export backups/before-phase9-fresh-install.sql
wp resq-catalog import
wp wc product list --format=count
wp post list --post_type=resq_routine --format=count
```

Expected: ~28 product families, routines present.

### 5. Smoke surfaces

| Surface | URL / action | Pass criteria |
| --- | --- | --- |
| Home | `/` | Renders without PHP notices |
| Shop | `/shop/` | Product grid loads; badges render |
| Human gateway | `/shop/human/` | Audience shelf renders; filter controls present |
| Pet gateway | `/shop/pet/` | Pet audience shelf; no human cross-sells |
| Bundles landing | `/shop/bundles/` | Bundle cards render |
| CBD gateway | `/shop/cbd/` | CBD-isolated display; disclaimer slot present |
| Category archive | Any Woo category | Grid + filter controls |
| PDP — routine product | e.g. `RQ-HUM-AIOCREAM` | Routine ladder with bundle savings CTA |
| PDP — bundle | e.g. `RQ-KIT-PET-HOTSPOT` | `bundle-options.php` included items list + savings |
| PDP — ingredient profile | Any product with `_resq_ingredient_profile` | Ingredient block renders or hides cleanly |
| PDP — FBT | Product with `_resq_fbt_product_ids` | FBT block with restricted set |
| CBD isolation | Human PDP → related/FBT | No `RQ-HCBD-*` or `RQ-PCBD-*` appear |
| Add to cart | Any product | Cart count increments; no JS errors |
| Cart drawer | Add routine-step product | Drawer opens; next-step suggestion card rendered |
| Cart page | `/cart/` | Items, totals, cross-sells load without error |
| Checkout start | `/checkout/` | Form renders; no PHP notices |
| My Account | `/my-account/` | Login/register page renders |
| Search | `/?s=test` | Results page without error |
| Filters | `?resq_audience=human` | PLP filtered correctly |

### 6. Plugin resilience checks

```bat
wp plugin deactivate resq-core
```

Browse `/`, shop, PDP — theme must render without PHP notices or broken markup.

```bat
wp plugin activate resq-core
wp cache flush
```

### 7. Theme resilience check

```bat
wp theme activate twentytwentyfour
```

Confirm WooCommerce pages and checkout still work with fallback theme. Reactivate `resq-clean-pro` after.

---

## Exit criteria (from [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md))

- [ ] Fresh install smoke passes (all surfaces above).
- [ ] Plugin deactivate/reactivate passes — no PHP fatals or broken theme.
- [ ] Theme active without plugin passes — empty-safe throughout.
- [ ] No PHP fatals or critical JS console errors on any smoked surface.

---

## Known open items entering Phase 9

| Item | Source | Notes |
| --- | --- | --- |
| `resq_theme_get_gateway_page_url()` nav fix | Phase 8 nav fix | Resolves gateway URLs from `_wp_page_template` meta — verify correct URLs on fresh install |
| Cart drawer focus trap | Phase 8 Stream E | Verify via browser (not shell) — see `assets/js/cart-drawer.js` |
| Filter GET params | Phase 8 Stream F | Verify `?resq_audience=`, `?resq_concern=`, `?resq_compliance_zone=` all filter correctly |
| Bundle add-to-cart validation error message | Phase 8 Stream D | Verify cart notice surfaces correctly on invalid bundle attempt |

---

## Read next

1. [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md) — Phase 9 exit criteria and global verification gates
2. [`20-PHASE-8-IMPLEMENTATION-NOTES.md`](20-PHASE-8-IMPLEMENTATION-NOTES.md) — Phase 8 smoke recipe (surfaces and SKUs)
3. [`19-CATALOG-IMPORT-NOTES.md`](19-CATALOG-IMPORT-NOTES.md) — catalog import options
4. [`CHECKPOINT.md`](CHECKPOINT.md) — update when Phase 9 gate passes
