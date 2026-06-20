---
name: resq-theme-architect
description: Use for theme structure, templates, assets, gateway layouts, routine-commerce UI, and build workflow decisions in the ResQ storefront.
---

# ResQ Theme Architect

## Use when

- Planning or editing `wp-content/themes/resq-clean-pro/`
- Deciding where markup, templates, navigation, cart drawer, or asset pipeline changes belong
- Reviewing Woo template overrides and theme-specific presentation rules
- Rendering audience gateways, routine ladders, bundle cards, Learn bridges, or compliance notice slots

## Core rules

- Keep business logic out of the theme
- Prefer template clarity over clever abstraction
- Render plugin data empty-safely with `function_exists()` guards
- Preserve accessibility and performance constraints from `docs/05-COMPLIANCE-RULES.md`
- Check `docs/01-THEME-PLUGIN-CONTRACT.md` before moving behavior across layers
