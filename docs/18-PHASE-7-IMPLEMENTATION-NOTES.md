# Phase 7 — Demo Fixture System

> WP-CLI fixture import for local sandbox validation (`resq-core`).

## Checkpoint Context

| Item | Value |
| --- | --- |
| Phase 6 | Gateway and Learn surfaces — complete |
| Phase 7 | Demo fixture system — in progress |
| Plugin version | `0.3.0` |
| WP-CLI command | `wp resq-fixtures` |

## Commands

Run from your WordPress site root (LocalWP site shell, DDEV, etc.):

```bash
# Import or update fixtures (idempotent)
wp resq-fixtures import

# Clean import
wp resq-fixtures import --reset

# Remove all fixture SKUs and routines
wp resq-fixtures reset --yes
```

## Prerequisites

- WordPress + WooCommerce running locally
- `resq-core` active (taxonomies and `resq_routine` CPT registered)
- `resq-clean-pro` optional but recommended for Phase 9 smoke tests

Backup before first import:

```bash
wp db export backups/before-fixtures.sql
```

## What gets created

All fixture SKUs use the `fixture-` prefix. Data is placeholder-only — no production catalog, claims, or images.

| Coverage | Fixture examples |
| --- | --- |
| CBD isolation | `fixture-cbd-wellness-oil` (`zone: cbd`, flag `cbd`) |
| Canonical mapping | `fixture-comfort-cream-listing` → canonical parent `fixture-human-comfort-cream` |
| Routine ladder | `fixture-routine-human-daily` with ordered steps + `_resq_primary_routine_id` |
| Variable parent | `fixture-human-comfort-cream` with 2oz/4oz variations |
| Baby / pet zones | `fixture-baby-gentle-balm`, `fixture-pet-coat-wash` |
| Bundles | `fixture-bundle-daily-comfort` via `_resq_bundle_product_ids` |
| FBT | Manual `_resq_fbt_product_ids` on comfort cream |

## File map

| Path | Role |
| --- | --- |
| `includes/fixtures/data/catalog.php` | Catalog definitions (`resq_fixtures_get_catalog()`) |
| `includes/fixtures/class-fixture-importer.php` | WooCommerce + meta import logic |
| `includes/cli/class-fixtures-cli.php` | `wp resq-fixtures` command registration |

## Phase 7 exit criteria mapping

- **Idempotent import:** Re-run `wp resq-fixtures import` safely; uses SKU lookup + updates.
- **No production data:** All names/prices/SKUs are clearly demo placeholders.
- **CBD isolation:** Dedicated CBD-zone product with `cbd` compliance flag.
- **Canonical + routine ladder:** Alias listing + multi-step routine with primary routine meta.

## Next step

Phase 9: fresh LocalWP/DDEV install → activate theme/plugin → `wp resq-fixtures import` → smoke test gateways, PDP, cart, checkout.
