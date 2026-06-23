# Catalog Import — Real Product System

> WP-CLI catalog import for production-ready SKU data (`resq-core` 0.4.0).

## Checkpoint context

| Item | Value |
| --- | --- |
| Phase 7 | Demo fixture system — complete |
| Catalog import | Real ~28 families + 21 bundles/packs |
| Plugin version | `0.4.0` |
| WP-CLI command | `wp resq-catalog` |

## Commands

Run from WordPress site root (LocalWP site shell, DDEV, etc.):

```bash
# Import or update catalog (idempotent)
wp resq-catalog import

# Clean reimport
wp resq-catalog import --reset

# Remove all RQ- SKUs and rq- routines
wp resq-catalog reset --yes

# Export reference WooCommerce CSV
wp resq-catalog export-csv
wp resq-catalog export-csv --file=/path/to/resq-catalog-import.csv
```

## Prerequisites

- WordPress + WooCommerce running locally or staging
- `resq-core` **0.4.0+** active (taxonomies, `resq_product_role`, `resq_routine` CPT)
- `resq-clean-pro` recommended for gateway/PDP smoke tests

Optional: remove demo fixtures after catalog validation:

```bash
wp resq-fixtures reset --yes
```

## SKU strategy

Format: `RQ-{SEG}-{PRODUCT}[-{VARIANT}]`

| Segment | Meaning | Examples |
| --- | --- | --- |
| `HUM` | Human skincare/grooming/hair | `RQ-HUM-AIOCREAM`, `RQ-HUM-NIGHTSERUM` |
| `BABY` | Baby care | `RQ-BABY-WASH`, `RQ-BABY-CREAM` |
| `PET` | Pet non-CBD | `RQ-PET-SKINCREAM`, `RQ-PET-SHAMPOO` |
| `HCBD` | Human CBD | `RQ-HCBD-OIL`, `RQ-HCBD-GUMMIES` |
| `PCBD` | Pet CBD | `RQ-PCBD-OIL`, `RQ-PCBD-TREATS` |

Bundles/packs:

- `RQ-KIT-*` — kits (Hot Spot, Calm Starter, routine bundles)
- `RQ-DUO-*` — two-product duos
- `RQ-PK-*` — multipacks / variety packs

**Reset scope:** all products with `_sku` starting `RQ-` plus routines with slug prefix `rq-`.

## What gets created

| Coverage | Catalog examples |
| --- | --- |
| Variable parents | `RQ-HUM-AIOCREAM` (2oz/4oz/8oz), `RQ-HCBD-OIL` (300–1500mg) |
| CBD isolation | `RQ-HCBD-OIL`, `RQ-PCBD-OIL` → `zone: cbd`, flag `cbd` |
| Baby zone | `RQ-BABY-WASH`, `RQ-BABY-CREAM` → `zone: baby`, flag `baby` |
| Pet health | `RQ-PET-SKINCREAM`, `RQ-PET-DIABETIC-TREATS` |
| Medical-adjacent | `RQ-PET-DIABETIC-TREATS`, `RQ-HCBD-SOFTGELS` |
| Routines | 6 routines (`rq-routine-*`) with steps + `_resq_primary_routine_id` |
| Bundles | 21 bundles via `_resq_bundle_product_ids` (variation SKUs resolved) |
| FBT | `_resq_fbt_product_ids` where defined in catalog data |
| Cross-audience | `RQ-HUM-MANUKAHONEY` → audiences `human` + `pet` |

## Taxonomy + meta mapping

Importer seeds terms idempotently then assigns per product:

| Taxonomy / meta | Source in catalog data |
| --- | --- |
| `resq_audience` | `audiences[]` |
| `resq_concern` | `concerns[]` (hierarchical terms seeded) |
| `resq_ingredient` | `ingredients[]` |
| `resq_product_role` | `roles[]` |
| `resq_compliance_zone` + `_resq_compliance_zone` | `zone` |
| `_resq_compliance_flags` | `compliance_flags[]` |
| Woo `product_cat` | `categories[]` (slug + name) |
| Card meta | `card_sub`, `benefits`, `ingredient_profile`, `gateway_featured` |
| Badges | `badge_label`, `badge_type` (when set) |

All meta passes through sanitizers in `includes/registrations/class-post-meta.php`. Compliance zone meta synced via `ResQ_Core_Product_Sync::sync_compliance_zone_meta()`.

## File map

| Path | Role |
| --- | --- |
| `includes/catalog/data/catalog.php` | `resq_catalog_get_data()` entry point |
| `includes/catalog/data/products.php` | 28 product families |
| `includes/catalog/data/routines.php` | 6 routines |
| `includes/catalog/data/bundles.php` | 21 bundles/packs |
| `includes/catalog/data/helpers.php` | Size/strength variation builders |
| `includes/catalog/class-catalog-importer.php` | Extends `ResQ_Core_Fixture_Importer` |
| `includes/cli/class-catalog-cli.php` | `wp resq-catalog` commands |

## Reference artifacts

| Path | Role |
| --- | --- |
| `docs/Product Data and Strategy/resq-catalog-import.csv` | WooCommerce CSV backup (from `export-csv`) |
| `docs/Product Data and Strategy/compliance-review-checklist.md` | Owner sign-off for CBD/baby/medical-adjacent SKUs |

## Verification gate

After `wp resq-catalog import`:

1. Shop page loads with real catalog products
2. Human / Pet / CBD gateways show correct isolation
3. Variable PDP: size/strength selector + routine ladder
4. Bundle PDP: component list from `_resq_bundle_product_ids`
5. Compliance notices on CBD, baby, and medical-adjacent SKUs

## Relationship to Phase 7 fixtures

| System | SKU prefix | Purpose |
| --- | --- | --- |
| `wp resq-fixtures` | `fixture-` | Demo sandbox (Phase 7) |
| `wp resq-catalog` | `RQ-` | Real strategic catalog |

Both can coexist during migration; reset each independently.

## Open follow-ups (non-blocking)

- Subscribe-and-save / bulk breaks: pricing strategy only (Phase 8)
- Product images: not in import — add via media or merchandising pass
- Ingredient term assignments beyond manuka-honey flagged "confirm" in compliance checklist
- Final prices imported at recommended values — owner confirms COGS/margin before promotions
