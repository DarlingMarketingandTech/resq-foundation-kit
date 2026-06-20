# ResQ Foundation Kit — Agent Operating Guide

This repository is the **foundation layer** for a WordPress + WooCommerce storefront built as a theme/plugin pair. Agents should treat the docs in `docs/` as the source of truth before writing code.

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
- **Local sandbox:** DDEV + WP-CLI (see `docs/06-BUILD-ROADMAP.md`)
- **Write policy:** Plan mode for any file, DB, or config mutation; no production store writes in normal sessions

## Read order

1. `docs/00-PROJECT-BRIEF.md` — goals, scope, constraints
2. `docs/01-THEME-PLUGIN-CONTRACT.md` — what lives in theme vs plugin
3. `docs/02-BRAND-FOUNDATION.md` — tokens, voice, visual rules
4. `docs/03-WOO-TEMPLATE-MAP.md` — template ownership and overrides
5. `docs/04-PRODUCT-MERCHANDISING-SYSTEM.md` — PDP/PLP, badges, cross-sells
6. `docs/05-COMPLIANCE-RULES.md` — accessibility, performance, legal, checkout safety
7. `docs/06-BUILD-ROADMAP.md` — phases, milestones, verification gates

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

## Sensitive data

- Do not commit API keys, application passwords, or customer PII
- Do not print secrets in chat or logs
- Disable CodeGraph telemetry for client work if required (`codegraph telemetry off`)
