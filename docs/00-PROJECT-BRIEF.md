# 00 — Project Brief

> Status: **draft skeleton** — fill in before theme/plugin implementation begins.

## Purpose

ResQ Foundation Kit is the shared foundation for a premium WooCommerce storefront:

- **Theme:** `wp-content/themes/resq-clean-pro/` — presentation, layout, Woo template overrides
- **Plugin:** `wp-content/plugins/resq-core/` — business logic, settings, integrations, data hooks

Agents and humans use this repo as the **single planning and contract surface** before code lands in theme or plugin directories.

## Goals

- [ ] Fast, accessible storefront with clear merchandising patterns
- [ ] Clean separation: theme = display, plugin = behavior and configuration
- [ ] Agent-safe workflow: staging-first, WP-CLI mutations, read-only MCP by default
- [ ] Documented brand, compliance, and template contracts so changes stay consistent

## Non-goals (phase 1)

- Full WordPress core in repo (use DDEV/sandbox install)
- Production write access from AI tooling
- Importing entire upstream skill libraries (754-skill security pack, etc.)

## Stack north star

**Adopt now:** CodeGraph · Addy-style lifecycle · Stop Slop · Taste Skill (constrained)

**Later:** Understand Anything · Headroom · cherry-picked security skills · Agent-Reach (research only)

See `AGENTS.md` for agent operating rules.

## Open decisions

| Decision | Options | Chosen |
|---|---|---|
| Theme base | Classic PHP · Block · Sage + Vite | _TBD_ |
| Hosting / PHP | _TBD_ | _TBD_ |
| Bedrock vs standard WP | _TBD_ | _TBD_ |
| Staging URL | _TBD_ | _TBD_ |
| Payment / email plugins to deactivate locally | _TBD_ | _TBD_ |

## Success criteria

- [ ] Local DDEV sandbox boots with theme + plugin active
- [ ] CodeGraph indexes PHP theme/plugin code
- [ ] Docs 01–06 reviewed and signed off
- [ ] Checkout, cart, and PDP smoke-tested after first template pass
