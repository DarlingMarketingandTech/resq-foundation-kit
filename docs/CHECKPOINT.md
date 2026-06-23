# Docs Checkpoint — Current Status

> Single source for where the ResQ Foundation Kit stands today. Updated at the post–catalog-import checkpoint (PR #9 merged).

## Release marker

| Item | Value |
| --- | --- |
| Branch | `main` |
| `resq-core` | `0.4.0` |
| `resq-clean-pro` | `0.4.0` |
| Catalog import | `wp resq-catalog` — ~28 families, 6 routines, 23 bundles (`RQ-*` SKUs) |
| Demo fixtures | `wp resq-fixtures` — `fixture-*` SKUs (sandbox only) |
| Local validation | Catalog import + storefront smoke verified on LocalWP |

**Next roadmap focus:** Phase 8 — Merchandising behavior (runbook [`20`](20-PHASE-8-IMPLEMENTATION-NOTES.md)). Phase 9 partially satisfied by Local work; full fresh-install gate still applies before release.

Comprehensive documentation (single merged handbook) is **deferred** to a follow-up initiative. This checkpoint only prunes and syncs status.

---

## Phase status

| Phase | Name | Status |
| --- | --- | --- |
| 1 | Foundation blueprint lock | complete |
| 2A | Plugin data schema (docs) | complete |
| 2B | Plugin data contract (PHP) | complete |
| 3 | Plugin routine-commerce model | complete |
| 4 | Theme global foundation | complete |
| 5 | WooCommerce template shells | complete |
| 6 | Gateway and Learn surfaces | complete |
| 7 | Demo fixture system | complete |
| — | Real catalog import (`wp resq-catalog`) | complete (post–Phase 7) |
| 8 | Merchandising behavior | **in progress** — runbook [`20`](20-PHASE-8-IMPLEMENTATION-NOTES.md) |
| 9 | Local sandbox validation | partial (Local smoke done; fresh-install gate open) |
| 10 | Compliance, accessibility, performance QA | pending |
| 11 | Preflight release package | pending |

Delivery records for phases 2A–6 live in [`docs/archive/phase-notes/`](archive/phase-notes/). Do not use them as current runbooks.

---

## What to read

| Goal | Start here |
| --- | --- |
| Architecture and boundaries | [`00-PROJECT-BRIEF.md`](00-PROJECT-BRIEF.md) → [`01`](01-THEME-PLUGIN-CONTRACT.md)–[`12`](12-PLUGIN-HELPER-CONTRACTS.md) |
| Phase order and gates | [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md) |
| Build Phase 8 merchandising | [`20-PHASE-8-IMPLEMENTATION-NOTES.md`](20-PHASE-8-IMPLEMENTATION-NOTES.md) |
| Import demo data (sandbox) | [`18-PHASE-7-IMPLEMENTATION-NOTES.md`](18-PHASE-7-IMPLEMENTATION-NOTES.md) |
| Import real catalog | [`19-CATALOG-IMPORT-NOTES.md`](19-CATALOG-IMPORT-NOTES.md) |
| Owner compliance sign-off | [`Product Data and Strategy/compliance-review-checklist.md`](Product%20Data%20and%20Strategy/compliance-review-checklist.md) |
| Historical delivery detail | [`docs/archive/phase-notes/`](archive/phase-notes/) |
| Source strategy (read-only) | [`source-blueprints/`](source-blueprints/) via [`10-SOURCE-BLUEPRINT-INDEX.md`](10-SOURCE-BLUEPRINT-INDEX.md) |

---

## WP-CLI quick reference

Run from WordPress site root (LocalWP **Open site shell**). On **Windows CMD**, do not paste bash `#` comments — they are passed as extra arguments.

```bat
wp resq-catalog import
wp resq-catalog import --reset
wp resq-fixtures import
wp resq-fixtures reset --yes
```

See runbooks [`18`](18-PHASE-7-IMPLEMENTATION-NOTES.md) and [`19`](19-CATALOG-IMPORT-NOTES.md) for full options.

---

## Protected artifacts

- **`docs/source-blueprints/`** — preserved strategy references; do not edit in place.
- **`docs/research/`** — background only; not implementation authority.
- **`docs/Product Data and Strategy/`** — strategy CSVs and compliance checklist; not live catalog truth (catalog truth is `wp resq-catalog` data in `resq-core`).
