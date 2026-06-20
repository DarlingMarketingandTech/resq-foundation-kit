# 06 — Build Roadmap

> Ordered phased delivery for the ResQ Foundation Kit. Do not skip verification gates. This repo is a foundation kit, not a full WordPress install.

## Release Marker

| Item | Value |
|---|---|
| Foundation branch | `foundation-blueprint` |
| Foundation tag | `v0.1-foundation-blueprint` |
| Current focus | Phase 1 architecture alignment |

## Phase Overview

| Phase | Name | Primary output |
|---|---|---|
| 1 | Foundation blueprint lock | Docs `00` through `10`, source-blueprint index, boundaries |
| 2 | Plugin data contract | `resq-core` helper stubs, options, meta/taxonomy plan |
| 3 | Plugin routine-commerce model | Audience, concern, routine, canonical, bundle, FBT, CBD data structures |
| 4 | Theme global foundation | Header/nav/mega-menu/mobile drawer/footer, assets, tokens |
| 5 | WooCommerce template shells | Shop, category, card, PDP, cart, checkout, account, search shells |
| 6 | Gateway and Learn surfaces | Human, pet, bundle, CBD, Learn bridge templates |
| 7 | Demo fixture system | Safe fixture categories/products/routines via WP-CLI |
| 8 | Merchandising behavior | Badges, routine ladders, bundles, FBT, cross-sells, filters |
| 9 | Local sandbox validation | Fresh LocalWP/DDEV install and Woo smoke test |
| 10 | Compliance, accessibility, performance QA | CBD isolation, claim review, WCAG, checkout safety |
| 11 | Preflight release package | Preflight review, docs sync, tag candidate |

---

## Phase 1 — Foundation Blueprint Lock

**Goal:** Lock architecture before major implementation.

### Key tasks

- Finalize `00-PROJECT-BRIEF.md`.
- Finalize `01-THEME-PLUGIN-CONTRACT.md`.
- Finalize `03-WOO-TEMPLATE-MAP.md`.
- Finalize `04-PRODUCT-MERCHANDISING-SYSTEM.md`.
- Finalize `05-COMPLIANCE-RULES.md`.
- Finalize this roadmap.
- Add `07-INFORMATION-ARCHITECTURE.md`.
- Add `08-ROUTINE-COMMERCE-FRAMEWORK.md`.
- Add `09-CANONICAL-PRODUCT-STRATEGY.md`.
- Add `10-SOURCE-BLUEPRINT-INDEX.md`.
- Confirm source blueprints remain preserved in `docs/source-blueprints/`.
- Lightly align `AGENTS.md`, skills, and agents.

### Exit criteria

- [ ] Docs `00` through `10` are internally consistent.
- [ ] Theme/plugin/Woo ownership is explicit.
- [ ] Source blueprints are indexed, not rewritten.
- [ ] No major PHP implementation was added.
- [ ] Branch target is `foundation-blueprint`.
- [ ] Tag target is `v0.1-foundation-blueprint`.

---

## Phase 2 — Plugin Data Contract

**Goal:** Make `resq-core` a thin, safe provider of documented helper contracts.

### Key tasks

- Add plugin infrastructure helpers from `01-THEME-PLUGIN-CONTRACT.md`.
- Add storefront helper stubs returning empty arrays/nulls/booleans safely.
- Add options defaults and feature flags.
- Add WooCommerce dependency check and admin notice.
- Document meta/taxonomy registration plan before adding fields.
- Add WP-CLI status scaffold only if needed for validation.

### Exit criteria

- [ ] Plugin activates/deactivates without fatals.
- [ ] Documented helpers exist as stubs or safe no-ops.
- [ ] No front-end markup is echoed from plugin hooks.
- [ ] Theme can run with plugin inactive.

---

## Phase 3 — Plugin Routine-Commerce Model

**Goal:** Define the data structures behind audiences, concerns, routines, canonical products, bundles, FBT, ingredients, and compliance flags.

### Key tasks

- Register audience/concern/routine data structures.
- Register product meta for routine membership, canonical mapping, bundle composition, FBT, CBD/compliance, and ingredients.
- Add admin fields only after names and storage are documented.
- Implement canonical resolver.
- Implement CBD/compliance helper rules.
- Add cache/transient strategy for relationship lookups.

### Exit criteria

- [ ] Helpers return real fixture-ready data.
- [ ] Meta ownership matches `01-THEME-PLUGIN-CONTRACT.md`.
- [ ] CBD isolation can be evaluated from data.
- [ ] Canonical resolver has documented fallbacks.

---

## Phase 4 — Theme Global Foundation

**Goal:** Build the display shell without business logic.

### Key tasks

- Add header, footer, primary nav, mega-menu shell, mobile drawer, cart link shell.
- Add theme helper functions.
- Add asset loading and CSS token shell.
- Add responsive containers, grid primitives, focus states, reduced-motion defaults.
- Add plugin guard pattern for all data slots.

### Exit criteria

- [ ] Theme activates with or without plugin.
- [ ] Global layout renders on basic WP pages.
- [ ] Navigation remains stable and accessible.
- [ ] No product data is registered or mutated by the theme.

---

## Phase 5 — WooCommerce Template Shells

**Goal:** Add minimal Woo template overrides mapped in `03-WOO-TEMPLATE-MAP.md`.

### Key tasks

- Add shop/category archive shell.
- Add product card shell.
- Add PDP shell with empty routine/ingredient/FBT/compliance slots.
- Add cart and cart-empty shells.
- Add checkout shell with notice slot.
- Add My Account shell.
- Add search shell.

### Exit criteria

- [ ] Every Phase 5 surface loads without PHP notices.
- [ ] Woo hooks still fire.
- [ ] Empty plugin data creates empty-safe UI.
- [ ] Template map remains current.

---

## Phase 6 — Gateway and Learn Surfaces

**Goal:** Build audience, bundle, CBD, concern, and Learn-to-shop presentation shells.

### Key tasks

- Add Human gateway page pattern.
- Add Pet gateway page pattern.
- Add Bundles & Savings landing pattern.
- Add CBD-isolated gateway/category pattern.
- Add concern landing pattern.
- Add Learn index and Learn-to-shop bridge modules.
- Add filter UI shell without final taxonomy lock.

### Exit criteria

- [ ] Gateway pages render without hardcoded final product truth.
- [ ] Learn bridges use plugin helper fallbacks.
- [ ] CBD visual isolation is present.
- [ ] Source-blueprint examples remain examples only.

---

## Phase 7 — Demo Fixture System

**Goal:** Provide repeatable local data without production catalog or PII.

### Key tasks

- Create WP-CLI fixture import script.
- Add demo products, variations, categories, routines, bundles, FBT, and compliance flags.
- Add safe placeholder copy and images.
- Document reset/import flow.

### Exit criteria

- [ ] Fixtures import idempotently on fresh local install.
- [ ] No production SKUs, prices, claims, images, customer data, or order data.
- [ ] At least one fixture covers CBD isolation.
- [ ] At least one fixture covers canonical mapping and routine ladder.

---

## Phase 8 — Merchandising Behavior

**Goal:** Turn the documented data into useful storefront behavior.

### Key tasks

- Implement badges.
- Implement routine ladders.
- Implement FBT.
- Implement bundle cards and bundle PDP display.
- Implement cart drawer suggestions.
- Implement related/cross-sell restrictions.
- Implement product filters from plugin-owned data.

### Exit criteria

- [ ] Routine and bundle UI improves clarity without blocking checkout.
- [ ] Cross-sells respect CBD, audience, and baby/pet restrictions.
- [ ] Product cards remain claim-safe.
- [ ] Bundle engine decision is documented.

---

## Phase 9 — Local Sandbox Validation

**Goal:** Prove the kit works in a fresh local WordPress + WooCommerce sandbox.

### Key tasks

- Install fresh WP + WooCommerce in LocalWP or DDEV.
- Activate `resq-core` and `resq-clean-pro`.
- Import fixtures.
- Configure test payment only.
- Smoke test home, shop, category, gateway, Learn, PDP, cart drawer, cart, checkout start, account, search.

### Exit criteria

- [ ] Fresh install smoke passes.
- [ ] Plugin deactivate/reactivate passes.
- [ ] Theme active without plugin passes.
- [ ] No PHP fatals or critical JS console errors.

---

## Phase 10 — Compliance, Accessibility, Performance QA

**Goal:** Confirm quality gates before a release package.

### Key tasks

- Review CBD isolation across nav, cards, PDP, cart, checkout, and search.
- Review medical, pet, baby, proof, and donation language.
- Run accessibility checks against WCAG 2.2 AA target.
- Review checkout/payment gateway safety.
- Measure key page performance and document gaps.
- Run frontend polish review constrained by compliance.

### Exit criteria

- [ ] Compliance review findings resolved or documented.
- [ ] Accessibility blockers resolved or documented.
- [ ] Checkout remains gateway-safe.
- [ ] Performance risks are listed with next actions.

---

## Phase 11 — Preflight Release Package

**Goal:** Prepare a reviewable foundation package.

### Key tasks

- Run `preflight-package-check`.
- Confirm docs and implementation agree.
- Confirm no secrets, PII, or production data.
- Confirm branch `foundation-blueprint`.
- Prepare tag `v0.1-foundation-blueprint` after approval.
- Draft PR/release notes with rollback notes.

### Exit criteria

- [ ] Preflight checklist passes or has documented exceptions.
- [ ] Git diff is intentional.
- [ ] Suggested tag is ready after review.
- [ ] No commit/tag is created without explicit approval.

---

## Global Verification Gates

1. Docs remain source of truth before code.
2. No production writes in normal sessions.
3. WP-CLI mutations require backup and explicit approval.
4. Theme/plugin boundaries stay aligned with `01-THEME-PLUGIN-CONTRACT.md`.
5. WooCommerce primitives are not replaced without ADR.
6. CBD/compliance-sensitive work references `05-COMPLIANCE-RULES.md`.
7. Source blueprints stay preserved in `docs/source-blueprints/`.

## Phase Status Tracker

| Phase | Status | Notes |
|---|---|---|
| 1 Foundation blueprint lock | in progress | Current branch target: `foundation-blueprint` |
| 2 Plugin data contract | pending | |
| 3 Plugin routine-commerce model | pending | |
| 4 Theme global foundation | pending | |
| 5 Woo template shells | pending | |
| 6 Gateway and Learn surfaces | pending | |
| 7 Demo fixture system | pending | |
| 8 Merchandising behavior | pending | |
| 9 Local sandbox validation | pending | |
| 10 Compliance/accessibility/performance QA | pending | |
| 11 Preflight release package | pending | Tag target: `v0.1-foundation-blueprint` |
