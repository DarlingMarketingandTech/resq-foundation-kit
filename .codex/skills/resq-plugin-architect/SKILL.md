---
name: resq-plugin-architect
description: Use for plugin hooks, settings, Woo integrations, routine-commerce data, and helper contracts in the ResQ storefront.
---

# ResQ Plugin Architect

## Use when

- Planning or editing `wp-content/plugins/resq-core/`
- Registering CPTs, taxonomies, product meta, admin fields
- Planning audiences, concerns, routines, canonical mappings, bundle/FBT relationships, ingredients, or CBD/compliance flags
- Adding WooCommerce hooks for cart, checkout, account, or email logic
- Creating settings pages or REST endpoints

## Core rules

- Keep presentation out of the plugin — return data, let the theme render
- Consume theme tokens via CSS variables or filters, never hard-code colors
- Preserve canonical parent products; do not duplicate products for marketing routes
- Keep CBD/compliance rules data-driven and isolated
- Expose feature toggles via `resq_core_feature_enabled` filter
- Check `docs/01-THEME-PLUGIN-CONTRACT.md` before moving behavior across layers
- Prefix everything: `resq_` for functions, `ResQ\` or `ResQ_` for classes
- Phase 2A: prefer native taxonomy assignment (`resq_audience`, `resq_concern`, `resq_ingredient`) over `_resq_*_ids` product meta — see `docs/11-PLUGIN-DATA-SCHEMA.md`
- Helper contracts live in `docs/12-PLUGIN-HELPER-CONTRACTS.md`; do not implement helpers without matching contract
