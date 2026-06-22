# 14 — Phase 3 Implementation Notes

> Summary of Phase 3 delivery: active data registration, real helper reads, compliance engine, and cache strategy.

## Checkpoint Context

| Item | Value |
|---|---|
| Phase 2B | Plugin PHP scaffold — complete |
| Phase 2B tag | `v0.3-phase-2b-plugin-scaffold` |
| Phase 3 | Plugin routine-commerce model — complete |
| Phase 3 branch | `phase-3-routine-commerce-model` |
| Phase 3 tag target | `v0.4-phase-3-routine-commerce-model` |
| Plugin version | `0.2.0` |
| Current focus | Phase 4 — theme global foundation |

---

## What Phase 3 Delivered

### Active registration

| Structure | Status |
|---|---|
| `resq_audience` taxonomy | Registered on `product` |
| `resq_concern` taxonomy (hierarchical) | Registered on `product` |
| `resq_ingredient` taxonomy | Registered on `product` |
| `resq_product_role` taxonomy | Registered on `product` |
| `resq_compliance_zone` taxonomy | Registered on `product` (`public => false`) |
| `resq_routine` CPT | Registered (`public => false`, `show_ui => true`) |
| Product meta (16 keys) | Registered via `register_post_meta()` |
| Routine CPT meta (4 keys) | Registered via `register_post_meta()` |
| Term meta (5 keys) | Registered on product categories + ResQ taxonomies |

**Not registered (by design):** `_resq_audience_ids`, `_resq_concern_ids`, `_resq_cbd_product`, `_resq_requires_compliance_notice`

### Plugin files added/updated

| File | Purpose |
|---|---|
| `includes/registrations/class-taxonomies.php` | Five product taxonomies |
| `includes/registrations/class-cpt.php` | `resq_routine` CPT |
| `includes/registrations/class-post-meta.php` | Product + routine meta with sanitize callbacks |
| `includes/registrations/class-term-meta.php` | Term/route meta |
| `includes/registrations/class-registrations.php` | Registration loader |
| `includes/class-cache.php` | Transient wrapper (`resq_core_` prefix, 1h TTL) |
| `includes/class-product-sync.php` | Compliance zone sync + cache busting |
| `includes/helpers/internal.php` | Shared utilities (product ID resolution, term mappers) |
| `includes/helpers/storefront.php` | All 19 helpers — real data reads |
| `includes/helpers/infrastructure.php` | Three infrastructure helpers implemented |
| `includes/class-plugin.php` | `init` registration wiring + sync hooks |
| `resq-core.php` | Version `0.2.0`, updated require chain |

### Deferred (unchanged)

| Item | Until |
|---|---|
| Custom admin metaboxes / settings UI | Phase 3+ or Phase 7 |
| Fixture import | Phase 7 |
| REST endpoints | After helper stability |
| Bundle engine | Phase 8 |
| Theme templates | Phase 4+ |

---

## Canonical Resolver Fallbacks

`resq_get_canonical_product_id( $source_id, $source_type )`:

| `$source_type` | Resolution |
|---|---|
| `product` | Self, or `_resq_canonical_product_id` override when set |
| `variation` | Parent product ID |
| `bundle` | Bundle product ID (self) |
| `routine` | `_resq_routine_bundle_target`, else first ordered step product |
| `term` | First ID in term `_resq_canonical_targets` |
| `page` / `learn` | First ID in post `_resq_canonical_targets` |
| `route` | Filterable slug map (`resq_route_canonical_map`) — null when unmapped |

Returns `null` when no mapping exists. Never creates products.

---

## Cross-Sell Rule Matrix

Default behavior when `resq_core_feature_enabled( 'cbd_isolation' )` and `cbd_isolation_enabled` option are true:

| Condition | Result |
|---|---|
| Invalid or same product ID | Deny |
| CBD product ↔ standard product | Deny |
| Human audience ↔ pet audience (no shared term) | Deny |
| Baby-flagged target without baby source flag | Deny |
| Same zone + compatible audience | Allow |
| Filter override | `apply_filters( 'resq_can_cross_sell_products', true, $source, $target )` |

---

## Compliance Zone Sync

On `woocommerce_process_product_meta` and `set_object_terms` for `resq_compliance_zone`:

1. Read assigned compliance zone term slug
2. Write mirrored `_resq_compliance_zone` meta
3. Default to `standard` when no term assigned on publish
4. Bust product-scoped transients

`resq_is_cbd_product()` returns true when zone is `cbd` OR `_resq_compliance_flags` contains `cbd`.

---

## Cache Strategy

| Key pattern | Content | Invalidated on |
|---|---|---|
| `product_audiences_{id}` | Audience term objects | Product save, term assign |
| `product_concerns_{id}` | Concern term objects | Product save, term assign |
| `product_routines_{id}` | Routine summaries | Product save, routine save |
| `routine_steps_{id}` | Raw step meta | Routine save |
| `compliance_zone_{id}` | Zone slug | Product save, zone term assign |
| `fbt_{id}` | FBT canonical IDs | Product save |
| `product_badges_{id}` | Badge objects | Product save |
| `gateway_featured_{slug}` | Gateway product IDs | Product save (gateway meta) |

All transients use prefix `resq_core_` and are cleared on plugin deactivation.

---

## Manual Smoke Checklist

Run on a local WP + WooCommerce sandbox:

- [x] Plugin activates/deactivates without fatals (with and without WooCommerce)
- [x] Five taxonomies visible on product edit screen (native panels)
- [x] `resq_routine` CPT visible in admin
- [x] Assign `resq_audience` + `resq_compliance_zone` on a test product → `resq_get_product_audiences()` and `resq_get_compliance_zone()` return live data
- [x] Set `_resq_compliance_flags` to include `cbd` → `resq_is_cbd_product()` true; cross-sell to standard product denied
- [x] Create routine with `_resq_routine_steps` meta → `resq_get_routine_steps()` returns ordered steps
- [x] Link product via `_resq_routine_ids` → `resq_get_product_routine_ladder()` returns ladder payload
- [x] CI `php -l` passes on all `wp-content/**/*.php`

**Verification:** Manual smoke checklist passed (no errors).

Data entry without custom admin UI: use native taxonomy panels, Custom Fields (block editor), or WP-CLI `post meta update`.

---

## Risks Enforced

1. No `_resq_audience_ids` / `_resq_concern_ids` usage
2. Step order from explicit `order` key in `_resq_routine_steps`
3. Zone/meta sync on save
4. Variation IDs resolved via `resq_resolve_product_id()` in all product helpers
5. Cross-sell default deny with filter hook for bundle overrides
6. `donation_display_enabled` remains default `false`

---

## Read Next

1. `06-BUILD-ROADMAP.md` — Phase 4 theme global foundation
2. `12-PLUGIN-HELPER-CONTRACTS.md` — helper contracts (now implemented)
3. Phase 4: `resq-clean-pro` theme shell
