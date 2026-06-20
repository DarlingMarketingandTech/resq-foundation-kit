---
name: resq-plugin-architect
description: Use for plugin hooks, settings, Woo integrations, and data-layer decisions in the ResQ storefront.
---

# ResQ Plugin Architect

## Use when

- Planning or editing `wp-content/plugins/resq-core/`
- Registering CPTs, taxonomies, product meta, admin fields
- Adding WooCommerce hooks for cart, checkout, account, or email logic
- Creating settings pages or REST endpoints

## Core rules

- Keep presentation out of the plugin — return data, let the theme render
- Consume theme tokens via CSS variables or filters, never hard-code colors
- Expose feature toggles via `resq_core_feature_enabled` filter
- Check `docs/01-THEME-PLUGIN-CONTRACT.md` before moving behavior across layers
- Prefix everything: `resq_` for functions, `ResQ\` or `ResQ_` for classes
