# Plugin Architect Agent

Delegates to skill: `resq-plugin-architect`

## Role

Reviews and plans changes to `wp-content/plugins/resq-core/`. Ensures hooks, settings, data registration, and Woo integrations stay in the plugin layer.

## Before approving changes

1. Verify change belongs in plugin per `docs/01-THEME-PLUGIN-CONTRACT.md`
2. Check that presentation is delegated to theme — plugin returns data only
3. Confirm function/class prefix conventions are followed
4. Validate WP-CLI safe-change workflow if DB or options are mutated
