# 13 — Phase 2A Implementation Notes

> Summary of Phase 2A decisions, Phase 2B delivery notes, deferred work, and Phase 3 risks.

## Checkpoint Context

| Item | Value |
|---|---|
| Phase 1 | Foundation blueprint lock — complete |
| Phase 1 branch | `foundation-blueprint` |
| Phase 1 tag | `v0.1-foundation-blueprint` |
| Phase 1 commit | `77430b6` |
| Phase 2A | Plugin data schema and helper contracts — complete (documentation only) |
| Phase 2B | Plugin PHP scaffold — complete |
| Phase 2B branch | `phase-2b-plugin-scaffold` |
| Phase 2B tag | `v0.3-phase-2b-plugin-scaffold` |
| Phase 2B merge commit | `52f1cf5` |
| Current phase | Phase 3 pending — plugin routine-commerce model |

---

## What Phase 2A Decided

### Alignment check

All six alignment checks pass:

1. Canonical product model remains primary.
2. Gateway pages are front-end experiences, not inventory owners.
3. Routine, step, bundle/kit, collection, gateway, Learn guide, and product remain distinct concepts.
4. CBD/compliance isolation is structural via `resq_compliance_zone`, flags, and cross-sell rules.
5. Bundles are routine-commerce offers with composition meta, not discount-only labels.
6. Schema is WooCommerce-first with platform-neutral concept mapping in docs.

### Taxonomies recommended (register in Phase 3)

| Taxonomy | Register |
|---|---|
| `resq_audience` | Yes |
| `resq_concern` | Yes — hierarchical |
| `resq_ingredient` | Yes |
| `resq_product_role` | Yes |
| `resq_compliance_zone` | Yes |
| `resq_routine` | No — use CPT |
| `resq_routine_step` | No — use CPT meta |

### Post types

| CPT | Decision |
|---|---|
| `resq_routine` | Create in Phase 3 |
| `resq_ingredient_profile` | Defer |
| `resq_learn_guide` | Defer |
| `resq_bundle_recipe` | Defer |

### Meta key reconciliation

| Key | Decision |
|---|---|
| `_resq_audience_ids` | Superseded by `resq_audience` taxonomy |
| `_resq_concern_ids` | Superseded by `resq_concern` taxonomy |
| `_resq_compliance_flags` | Retained — writable flag array |
| `_resq_cbd_product` | Not stored — derived in helpers |
| `_resq_requires_compliance_notice` | Not stored — derived in helpers |
| `_resq_compliance_zone` | New — mirrors taxonomy slug for queries |
| `_resq_routine_ids` | Retained |
| `_resq_bundle_product_ids` | Retained |
| `_resq_fbt_product_ids` | Retained |
| `_resq_ingredient_profile` | Retained |
| `_resq_canonical_product_id` | Retained |
| `_resq_routine_step_order` | New — optional hint |
| `_resq_primary_routine_id` | New |
| `_resq_short_benefit_tags` | New |
| `_resq_product_card_subtitle` | New |
| `_resq_gateway_featured` | New |
| `_resq_learn_links` | New |
| `_resq_donation_eligible` | New — display gated by proof |

### Helper functions defined

19 storefront helpers documented in `12-PLUGIN-HELPER-CONTRACTS.md`:

- Product intelligence: 5
- Canonical mapping: 2
- Compliance: 4
- Routine commerce: 4
- Presentation: 4

Plus 7 infrastructure helpers from Phase 1 (implemented in Phase 2B).

### Tensions resolved

1. Native taxonomy preferred over `_ids` meta for audience and concern.
2. Compliance flags stay in one writable array; zone is separate for query performance.
3. Routine steps are CPT meta, not a taxonomy.

### Conflicts or open questions (not silently resolved)

| Item | Status |
|---|---|
| Bundle engine choice | Deferred to Phase 8 |
| Final taxonomy terms | Deferred to Phase 7 fixtures |
| Donation/mission claims | Require operational proof; display off by default |
| CBD jurisdiction rules | Open — compliance review before production |
| Isolated checkout mode | Open — theme decision in Phase 5+ |
| Source blueprint risky copy | Already flagged in `10`; not adopted as product truth |

---

## What Phase 2B Delivered

Phase 2B shipped the minimum PHP scaffold — still no admin UI, no fixture data, no live taxonomy/CPT registration.

### Plugin bootstrap

- Expanded `wp-content/plugins/resq-core/resq-core.php` with bootstrap constants and the require chain
- Added `includes/class-plugin.php` singleton
- Added `includes/class-woocommerce-compat.php` with a WooCommerce dependency check + admin notice when inactive and a WooCommerce HPOS compatibility declaration

### Options and feature flags

- Implemented `includes/class-options.php` initializing `resq_core_version`, `resq_core_features`, `resq_core_settings`, `resq_core_compliance`, and `resq_core_merchandising` defaults
- Implemented `resq_core_get_option()` and `resq_core_feature_enabled()`
- Implemented `resq_core_is_active()`

### Helper stubs

- Added `includes/helpers/storefront.php` with all 19 storefront helpers returning empty-safe defaults (`[]`, `null`, `false`, `'standard'`)
- Added `includes/helpers/infrastructure.php` with the 7 `resq_core_*` infrastructure helpers
- No real taxonomy/CPT reads — deferred to Phase 3

### Registration scaffolds (commented only)

- Added `includes/registration-scaffold.php` with commented taxonomy registration referencing `11-PLUGIN-DATA-SCHEMA.md`
- Commented CPT registration for `resq_routine`
- Commented product meta registration list
- Registration hooks are not yet called

### Verification

- Plugin activates/deactivates without fatals
- Theme can call helpers with `function_exists()` guards
- No front-end echoed markup from plugin

---

## What Must Remain Deferred

| Item | Until |
|---|---|
| Taxonomy/CPT registration (active) | Phase 3 |
| Admin product fields | Phase 3 |
| Real helper data reads | Phase 3 |
| Fixture import | Phase 7 |
| Bundle engine implementation | Phase 8 |
| REST endpoints | Phase 3+ after helper stability |
| Composer/autoload | Optional — defer unless file count grows in Phase 3+ |
| Final product catalog data | Never in foundation kit without fixtures phase |

---

## Risks to Watch During Phase 3 Implementation

1. **Meta key drift:** Do not reintroduce `_resq_audience_ids` or `_resq_concern_ids` in code. Use taxonomy APIs.
2. **Step order:** Never infer routine step order from taxonomy term order. Always read `_resq_routine_steps`.
3. **Compliance zone sync:** When assigning `resq_compliance_zone` term, sync `_resq_compliance_zone` meta in the same save handler to avoid query mismatches.
4. **Bundle meta shape:** `_resq_bundle_product_ids` shape may change when bundle engine is chosen. Keep helper abstraction thin.
5. **Variation resolution:** All product-scoped helpers must resolve variation IDs to parent/canonical ID consistently.
6. **Cross-sell safety:** Default deny between compliance zones. Opt-in allow only with documented compliance approval.
7. **Duplicate products:** Canonical resolver must never create products. Mapping only.
8. **Donation display:** Keep `resq_core_compliance['donation_display_enabled']` default `false` until proof exists.

---

## Files Created in Phase 2A

| File | Purpose |
|---|---|
| `docs/11-PLUGIN-DATA-SCHEMA.md` | Taxonomies, CPTs, meta keys, options, relationships |
| `docs/12-PLUGIN-HELPER-CONTRACTS.md` | Public helper signatures and return shapes |
| `docs/13-PHASE-2A-IMPLEMENTATION-NOTES.md` | This summary |

## Files Updated in Phase 2A

| File | Change |
|---|---|
| `docs/01-THEME-PLUGIN-CONTRACT.md` | Superseded meta keys, new Phase 2A keys |
| `docs/06-BUILD-ROADMAP.md` | Phase 1 complete, Phase 2A/2B split |
| `.codex/skills/resq-plugin-architect/SKILL.md` | Taxonomy preference note |

---

## Suggested Commit Message (Phase 2A checkpoint — historical)

```
docs: Phase 2A — plugin data schema and helper contracts

Add docs/11-PLUGIN-DATA-SCHEMA.md: taxonomies (resq_audience, resq_concern,
resq_ingredient, resq_product_role, resq_compliance_zone), CPT decision
(resq_routine now / others deferred), meta key reconciliation, options,
relationship model, and deferred decisions.

Add docs/12-PLUGIN-HELPER-CONTRACTS.md: 19 signed helper function contracts
with return shapes, fallback rules, and theme/REST safety flags.

Add docs/13-PHASE-2A-IMPLEMENTATION-NOTES.md: decisions, Phase 2B scope,
risks before PHP, deferred items, and commit guidance.

Update docs/01-THEME-PLUGIN-CONTRACT.md: mark superseded meta keys, add
new Phase 2A keys to ownership table.
Update docs/06-BUILD-ROADMAP.md: Phase 1 complete checkpoint, Phase 2 status
updated, 2A/2B split noted.
Update .codex/skills/resq-plugin-architect/SKILL.md: taxonomy preference
over _ids meta.
```

---

## Recommended Next Prompt (Phase 3)

> Start Phase 3: Plugin Routine-Commerce Model.
>
> Reference `docs/11-PLUGIN-DATA-SCHEMA.md` and `docs/12-PLUGIN-HELPER-CONTRACTS.md` for scope.
> Build on the Phase 2B scaffold in `wp-content/plugins/resq-core/`:
> - Activate the commented taxonomy/CPT/meta registration in `includes/registration-scaffold.php` per `docs/11`
> - Implement the canonical resolver and CBD/compliance helper rules
> - Replace the empty-safe storefront stubs with real data reads per `docs/12`
>
> No admin UI and no fixture data yet — those are later phases per `docs/06`.

---

## Read Next

1. `11-PLUGIN-DATA-SCHEMA.md`
2. `12-PLUGIN-HELPER-CONTRACTS.md`
3. `06-BUILD-ROADMAP.md` — Phase 3 tasks
