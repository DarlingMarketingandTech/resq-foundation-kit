# 00 — Project Brief

> Status: **contract draft** — architecture and boundaries locked for Step 2. Brand tokens and catalog details remain in later docs/phases.

## Mission

ResQ Foundation Kit is the **shared foundation** for a premium WooCommerce storefront built as a **theme + companion plugin pair**:

- **Theme:** `wp-content/themes/resq-clean-pro/` — presentation, layout, WooCommerce template overrides
- **Plugin:** `wp-content/plugins/resq-core/` — business logic, settings, integrations, data hooks

The mission is to give agents and developers a **single, safe contract surface** before implementation spreads across theme and plugin code. Every future change should be traceable to a doc in `docs/` and respect the theme/plugin boundary in `01-THEME-PLUGIN-CONTRACT.md`.

The target storefront serves a multi-category commerce experience with planning-level navigation concepts: **Pets**, **People**, **CBD**, **Bundles**, and **Learn**. These are top-level planning categories only — not hardcoded product data, SKUs, or final taxonomy trees.

## What this repo is

| Layer | Contents |
|---|---|
| Planning docs | Project brief, theme/plugin contract, brand foundation, Woo template map, merchandising system, compliance rules, build roadmap |
| Agent infrastructure | `AGENTS.md`, `.codex/skills/`, `.codex/agents/` |
| Scaffold code | Minimal theme and plugin bootstrap (constants, headers, bare templates) |
| Fixtures/scripts (later) | WP-CLI import helpers and demo data — Phase 5+ |

Agents and humans treat this repo as the **source of truth for architecture and boundaries**. Code changes that violate a doc must update the doc in the same PR.

## What this repo is not

- A full WordPress core install (use LocalWP, DDEV, or equivalent sandbox)
- A turnkey, deployable production site
- A child theme of a third-party commercial theme
- A multisite or Bedrock configuration
- A production database, staging deployment pipeline, or live payment integration
- A finished storefront with real product catalog data
- A dumping ground for upstream skill libraries (754-skill security packs, etc.)

## Target site experience

The finished storefront (built from this foundation) should feel:

- **Fast** — scannable PLP/PDP, optimized images, minimal layout shift
- **Accessible** — WCAG 2.2 AA target; keyboard-operable cart and checkout
- **Trust-building** — clear shipping/returns language, compliance notices where required (especially CBD), honest merchandising
- **Category-clear** — top-level navigation aligned to Pets, People, CBD, Bundles, Learn
- **Conversion-aware** — strong product cards, PDP hierarchy, cross-sell/upsell zones without checkout friction
- **Agent-safe to extend** — every surface mapped in `03-WOO-TEMPLATE-MAP.md`; every behavior assigned in `01-THEME-PLUGIN-CONTRACT.md`

Visual polish and brand tokens live in `02-BRAND-FOUNDATION.md`. Merchandising rules live in `04-PRODUCT-MERCHANDISING-SYSTEM.md`. Non-negotiable constraints live in `05-COMPLIANCE-RULES.md`.

## Core audiences

### External (storefront visitors)

| Audience | Primary need | Storefront implication |
|---|---|---|
| Pet owners / adopters | Products and content for pets and rescue support | Pets category, trust signals, clear product info |
| CBD consumers | Compliant product discovery and purchase | CBD category, age/disclaimer notices, careful checkout copy |
| General e-commerce shoppers | Browse, compare, buy with confidence | Strong PLP/PDP, cart/checkout clarity |
| Rescue organization supporters | Understand mission, find relevant products | Learn zone, mission-aligned merchandising |

### Internal (builders and agents)

| Audience | Primary need | Repo implication |
|---|---|---|
| Developers | Clear boundaries, predictable file layout | Contract doc, template map, roadmap phases |
| AI agents (Codex, Claude, etc.) | Safe defaults, no accidental production writes | `AGENTS.md`, skills, staging-first policy |
| Reviewers | Verification gates per phase | Roadmap exit criteria, compliance triggers |

## Goals

- [ ] Fast, accessible storefront with clear merchandising patterns
- [ ] Clean separation: theme = display, plugin = behavior and configuration
- [ ] Agent-safe workflow: staging-first, WP-CLI mutations, read-only MCP by default
- [ ] Documented brand, compliance, and template contracts so changes stay consistent
- [ ] Every WooCommerce surface mapped before override work begins
- [ ] Phased delivery with verification gates (see `06-BUILD-ROADMAP.md`)

## Non-goals (foundation phase)

- Building a complete visual design system (Phase 3+ / brand doc fill-in)
- Importing production catalog or customer data
- Production write access from AI tooling
- Payment gateway integration beyond test/sandbox mode
- Custom block editor theme (FSE) — classic PHP templates are the baseline
- Sage/Vite asset pipeline until explicitly decided in Phase 1 exit criteria

## Stack north star

**Adopt now:** CodeGraph · Addy-style lifecycle · Stop Slop · Taste Skill (constrained)

**Later:** Understand Anything · Headroom · cherry-picked security skills · Agent-Reach (research only)

See `AGENTS.md` for agent operating rules.

## Architecture decisions (locked)

| Decision | Options considered | Chosen | Rationale |
|---|---|---|---|
| Theme base | Classic PHP · Block (FSE) · Sage + Vite | **Classic PHP** | Direct Woo template overrides, lower bootstrap complexity, aligns with current scaffold |
| WordPress layout | Standard WP · Bedrock | **Standard WP** | Sandbox installs via LocalWP/DDEV; no Composer-managed core in repo |
| Theme/plugin split | Monolith · Paired layers | **Paired layers** | Theme owns display; plugin owns logic — see contract doc |
| Asset build (initial) | Plain CSS/JS · Vite/Sage | **Plain CSS/JS first** | Revisit at Phase 1 exit; Vite optional in Phase 3 |
| Top-level nav concepts | Open · Fixed set | **Pets, People, CBD, Bundles, Learn** | Planning labels only; final taxonomy defined in Phase 7 |

## Open decisions (environment-specific)

| Decision | Notes |
|---|---|
| Local sandbox tool | LocalWP (Phase 6 target) or DDEV — either works; pick one per developer machine |
| Hosting / PHP version | PHP 8.1+ required (see theme/plugin headers); exact host TBD |
| Staging URL | Set when staging environment exists; MCP read-only against staging |
| Payment / email plugins to deactivate locally | Document per sandbox setup; never use live keys locally |

## Repository layout

```text
resq-foundation-kit/
  AGENTS.md                 # Agent operating guide (points to docs/)
  docs/                     # Planning and contract docs (00–06)
  wp-content/
    themes/resq-clean-pro/  # Presentation layer
    plugins/resq-core/      # Business logic layer
  .codex/skills/            # Project-specific agent skills
  .codex/agents/            # Review/planning subagents
  fixtures/                 # Demo data (Phase 5+)
  scripts/                  # WP-CLI helpers (Phase 5+)
```

## Success criteria (foundation kit)

- [ ] Docs 00, 01, 03, 06 reviewed and internally consistent
- [ ] Theme + plugin activate without PHP fatals on a fresh WP + Woo install
- [ ] CodeGraph indexes PHP in theme and plugin directories
- [ ] Woo template map covers all planned storefront surfaces
- [ ] Phase 6 smoke test passes: home, shop, category, PDP, cart, checkout start, account
- [ ] No contract violations in first template override PR

## Read next

1. `01-THEME-PLUGIN-CONTRACT.md` — what lives where
2. `03-WOO-TEMPLATE-MAP.md` — WooCommerce surface inventory
3. `06-BUILD-ROADMAP.md` — phased delivery order
