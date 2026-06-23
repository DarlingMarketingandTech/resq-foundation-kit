# ResQ Foundation Kit — Agent Operating Guide

This repository is the **routine-commerce foundation layer** for a WordPress + WooCommerce storefront built as a theme/plugin pair:

- **Theme:** `resq-clean-pro` — presentation and Woo template overrides
- **Plugin:** `resq-core` — business logic, settings, Woo hooks

Agents must treat the docs in `docs/` as the source of truth before writing code. Do not blur the theme/plugin boundary defined in `docs/01-THEME-PLUGIN-CONTRACT.md`. Preserve source blueprints in `docs/source-blueprints/`; absorb their strategy into architecture docs rather than rewriting them in place.

## North star stack

Adopt now (thin, conservative core):

| Layer | Tool / pattern | Role |
|---|---|---|
| Code navigation | **CodeGraph** | PHP, Liquid, JS/TS structural discovery and impact analysis |
| Process discipline | **Addy-style workflow** | spec → plan → build → test → review → ship |
| Prose quality | **Stop Slop** | Clean ADRs, PRs, merchant-facing copy |
| Storefront design | **Taste Skill** (selective) | Premium ecommerce UI within brand and accessibility rules |

Add later (only after sandbox is stable):

- **Understand Anything** — onboarding and large architecture reviews
- **Headroom** — context compression for huge logs/MCP output
- **Anthropic Cybersecurity Skills** — cherry-picked hardening checklists only
- **Agent-Reach** — public-web research, never as a default coding dependency

## Environment assumptions

- **Mutation layer:** WP-CLI first (backups, search-replace dry-run, plugin/config changes)
- **Remote access:** read-only WordPress/Woo MCP against staging until explicitly approved
- **Local sandbox:** LocalWP or DDEV + WP-CLI (Phase 9 validates LocalWP; see `docs/06-BUILD-ROADMAP.md`)
- **Write policy:** Plan mode for any file, DB, or config mutation; no production store writes in normal sessions

## Site shell first (top priority)

**Default verification and ops path:** LocalWP **Open site shell** at the WordPress site root. This is more efficient than browser automation or re-discovering state in chat — prefer it whenever a `wp` command can answer the question.

### Agent workflow

1. **Implement** in repo (`resq-core`, `resq-clean-pro`, docs).
2. **End each build slice** with a short, copy-paste **site shell block** (import, counts, option reads, targeted `wp eval`) — see runbooks `18`–`20`.
3. **User runs** commands in site shell; paste output back **only on failure**.
4. **Do not** use browser/MCP to verify catalog counts, meta, options, or plugin state if shell can do it.
5. **Do not** repeat long import/setup instructions when runbooks `18`/`19` already cover them — link + minimal smoke block.

### Prefer site shell for

| Task | Examples |
|---|---|
| Catalog / fixtures | `wp resq-catalog import`, `wp resq-fixtures import` |
| Backups before writes | `wp db export backups/before-<task>.sql` |
| Product / routine counts | `wp wc product list --format=count`, `wp post list --post_type=resq_routine --format=count` |
| Options and flags | `wp option get resq_core_merchandising --format=json` |
| Quick data checks | `wp post meta get <id> _resq_bundle_product_ids`, `wp eval '...'` |
| Plugin/theme state | `wp plugin list`, `wp theme list`, `wp cache flush` |

### Use browser / MCP only when shell cannot

- Visual layout, CSS, drawer focus trap, responsive behavior
- Checkout/payment iframe internals
- JS console errors on interaction
- Staging read-only review when explicitly approved

### Windows CMD (Local site shell)

Do not paste bash `#` inline comments on the same line — CMD passes them as extra arguments. One command per line.

### Skills

- `wp-wpcli-and-ops` — safe WP-CLI patterns, backups, targeting
- Active phase runbooks — `docs/20-PHASE-8-IMPLEMENTATION-NOTES.md` (smoke recipe), `docs/19-CATALOG-IMPORT-NOTES.md`, `docs/18-PHASE-7-IMPLEMENTATION-NOTES.md`

## Read order

Start at **`docs/CHECKPOINT.md`** for current versions and phase status. Full map: **`docs/README.md`**.

### Tier 1 — Architecture (always)

1. `docs/CHECKPOINT.md` — current status
2. `docs/00-PROJECT-BRIEF.md` through `docs/12-PLUGIN-HELPER-CONTRACTS.md` — goals, contracts, schema, helpers

### Tier 2 — Runbooks (when building or operating data)

- `docs/20-PHASE-8-IMPLEMENTATION-NOTES.md` — **active** Phase 8 merchandising implementation authority
- `docs/18-PHASE-7-IMPLEMENTATION-NOTES.md` — `wp resq-fixtures` (demo `fixture-*` SKUs)
- `docs/19-CATALOG-IMPORT-NOTES.md` — `wp resq-catalog` (real `RQ-*` SKUs)

### Tier 3 — Compliance and strategy artifacts

- `docs/Product Data and Strategy/compliance-review-checklist.md` — owner sign-off before launch
- `docs/Product Data and Strategy/README.md` — strategy CSV index

### Tier 4 — Historical / reference only

- `docs/archive/phase-notes/` — completed phase delivery records (13–17); stubs at old `docs/13–17` paths
- `docs/source-blueprints/` — frozen strategy references (read via `10-SOURCE-BLUEPRINT-INDEX.md`; **do not edit**)
- `docs/research/` — background only; not implementation authority

## Build phases (summary)

Follow the ordered roadmap in `docs/06-BUILD-ROADMAP.md`. Do not skip phases or verification gates.

| Phase | Focus | Status |
|---|---|---|
| 1 | Foundation blueprint lock | complete |
| 2 | Plugin data contract | complete |
| 3 | Plugin routine-commerce model | complete |
| 4 | Theme global foundation | complete |
| 5 | WooCommerce template shells | complete |
| 6 | Gateway and Learn surfaces | complete |
| 7 | Demo fixture system + catalog import | complete |
| 8 | Merchandising behavior | **in progress** — runbook `20` |
| 9 | Local sandbox validation | partial |
| 10 | Compliance, accessibility, performance QA | pending |
| 11 | Preflight release package | pending |

Planning-level nav concepts only: **People, Pets, CBD, Bundles, Learn** — not hardcoded product data. Routine-commerce concepts include audiences, concerns/problems, routines, canonical parent products, bundles/FBT, Learn-to-shop bridges, and CBD isolation.

Phases 1–7 and catalog import ship in `resq-core` `0.4.0` and `resq-clean-pro` `0.4.0`. Before expanding a surface, confirm [`docs/CHECKPOINT.md`](docs/CHECKPOINT.md) and the affected architecture docs still agree. Do not add major code for a phase whose docs and verification gate are not aligned.

## Project skills (`.codex/skills/`)

| Skill | Use when |
|---|---|
| `resq-theme-architect` | Theme structure, templates, assets, Vite/build |
| `resq-plugin-architect` | Plugin hooks, settings, Woo integrations |
| `woo-template-planner` | Mapping Woo templates before override work |
| `ecommerce-taste-review` | Frontend polish against brand + conversion heuristics |
| `preflight-package-check` | Pre-merge/release verification checklist |

## Subagents (`.codex/agents/`)

Delegate focused review or planning to the matching agent file before large changes.

## Research and background artifacts

Background research and exploratory documents are in `docs/research/`. These are not architecture docs and should not be treated as authoritative for implementation decisions. They document the research process, tool evaluation, and platform comparisons that informed the architecture docs.

- `docs/research/deep-research-report.md` — Background workflow and tool research for Claude Code Desktop; informs `AGENTS.md` and skill/tool selection but is not a direct implementation reference.

## Sensitive data

- Do not commit API keys, application passwords, or customer PII
- Do not print secrets in chat or logs
- Disable CodeGraph telemetry for client work if required (`codegraph telemetry off`)
