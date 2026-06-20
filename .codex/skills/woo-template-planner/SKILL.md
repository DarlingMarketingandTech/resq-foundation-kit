---
name: woo-template-planner
description: Use before adding, moving, or overriding any WooCommerce template file.
---

# Woo Template Planner

## Use when

- Overriding a WooCommerce template in the theme
- Moving template responsibility between theme and plugin
- Updating `docs/03-WOO-TEMPLATE-MAP.md`

## Required steps

1. Query CodeGraph for the template's current callers and hook attachments
2. Check `docs/03-WOO-TEMPLATE-MAP.md` for existing ownership
3. Confirm the override path follows Woo hierarchy (`woocommerce/` subfolder in theme)
4. Verify no duplicate override exists in a parent or child theme
5. Update the template map doc in the same PR
6. Run verification: no PHP notices, mobile layout check, plugin hooks still fire
