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

## Read order

1. `docs/00-PROJECT-BRIEF.md` — goals, scope, constraints
2. `docs/01-THEME-PLUGIN-CONTRACT.md` — what lives in theme vs plugin
3. `docs/02-BRAND-FOUNDATION.md` — tokens, voice, visual rules
4. `docs/03-WOO-TEMPLATE-MAP.md` — template ownership and overrides
5. `docs/04-PRODUCT-MERCHANDISING-SYSTEM.md` — PDP/PLP, badges, cross-sells
6. `docs/05-COMPLIANCE-RULES.md` — accessibility, performance, legal, checkout safety
7. `docs/06-BUILD-ROADMAP.md` — phases, milestones, verification gates
8. `docs/07-INFORMATION-ARCHITECTURE.md` — navigation, routes, content types
9. `docs/08-ROUTINE-COMMERCE-FRAMEWORK.md` — routines, bundles, kits, Learn bridges
10. `docs/09-CANONICAL-PRODUCT-STRATEGY.md` — Single Parent Rule, duplicate avoidance
11. `docs/10-SOURCE-BLUEPRINT-INDEX.md` — source blueprint provenance and open decisions

## Build phases (summary)

Follow the ordered roadmap in `docs/06-BUILD-ROADMAP.md`. Do not skip phases or verification gates.

| Phase | Focus |
|---|---|
| 1 | Foundation blueprint lock |
| 2 | Plugin data contract |
| 3 | Plugin routine-commerce model |
| 4 | Theme global foundation |
| 5 | WooCommerce template shells |
| 6 | Gateway and Learn surfaces |
| 7 | Demo fixture system |
| 8 | Merchandising behavior |
| 9 | Local sandbox validation |
| 10 | Compliance, accessibility, performance QA |
| 11 | Preflight release package |

Planning-level nav concepts only: **People, Pets, CBD, Bundles, Learn** — not hardcoded product data. Routine-commerce concepts include audiences, concerns/problems, routines, canonical parent products, bundles/FBT, Learn-to-shop bridges, and CBD isolation.

No major implementation code should be added until architecture docs align for the affected surface.

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
