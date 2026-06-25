# 01 — Theme / Plugin Contract

> Defines **what belongs where**. Agents must not blur this boundary without updating this doc and the Woo template map (`03-WOO-TEMPLATE-MAP.md`).

## Summary

| Layer | Slug | Role |
|---|---|---|
| Theme | `resq-clean-pro` | HTML structure, CSS/JS, Woo template overrides, layout, storefront presentation |
| Plugin | `resq-core` | Data model, settings, Woo hooks, mappings, compliance rules, admin fields |
| WooCommerce | `woocommerce` | Products, variations, cart, checkout, orders, accounts, taxes, payment gateway interfaces |

**Rule of thumb:** If it renders a storefront surface, it lives in the theme. If it decides *what* to show, how products relate, or how commerce behaves, it lives in the plugin. WooCommerce remains the system of record for buyable product, price, stock, variation, cart, checkout, and order primitives.

---

## Theme: `resq-clean-pro`

### Owns

- Global header, navigation, mega-menu, mobile drawer, footer, and search presentation
- Audience gateway layouts, collection landing layouts, bundles landing layouts, Learn-to-shop bridge layouts
- WooCommerce template overrides and template parts
- Product cards, PDP presentation, routine ladder UI, bundle card UI, FBT UI, cart drawer UI, cart/checkout/account layout
- Compliance notice display slots and visual treatment
- Empty states, loading states, responsive grids, focus states, motion, and accessibility behavior
- Presentational wrappers for plugin-provided data such as badges, routine steps, ingredients, bundle relationships, and notices

### Must Not Own

- Product meta, term meta, custom taxonomies, CPTs, or admin field registration
- Audience/problem/routine canonical mappings
- Bundle composition rules, FBT product IDs, cross-sell restrictions, or savings calculations
- CBD/compliance flag logic or jurisdiction-sensitive copy sources
- Checkout/cart logic, pricing rules, coupon behavior, tax calculations, payment gateway behavior
- Third-party API credentials, webhooks, REST endpoints, or feature flags

---

## Plugin: `resq-core`

### Owns

- Audience, concern/problem, routine, routine step, ingredient, bundle, FBT, and canonical product data
- Canonical mappings between front-end routes/content and WooCommerce products or variations
- Product helpers consumed by theme templates
- Bundle/kit/FBT relationships and cross-sell/upsell rules
- CBD/compliance flags, notice selection, claim-risk categories, and cross-sell restrictions
- Product ingredient profiles and product helper metadata
- Demo fixture data definitions and WP-CLI import helpers when fixture phases begin
- WooCommerce hooks for cart, checkout, account, emails, query filtering, validation, notices, and admin fields
- Settings API, options, feature flags, capabilities, transients, cache invalidation

### Must Not Own

- Page layout and visual design
- Full Woo template overrides or theme template parts
- Hard-coded brand colors, typography, or motion
- Markup-heavy hooked output where the theme has a documented render slot

---

## WooCommerce Owns

- Product post types, product types, variations, attributes, price, stock, tax class, shipping class
- Cart/session, checkout, order, coupon, account, and email primitives
- Gateway-provided markup and payment flow internals
- Native related/upsell/cross-sell storage unless filtered by the plugin

Plugin code may register data around WooCommerce products, but it must not replace WooCommerce as the commerce source of truth without a documented ADR.

---

## Routine-Commerce Ownership Matrix

| Concern | Plugin responsibility | Theme responsibility |
|---|---|---|
| Audiences | Register/read audience data and mappings | Render audience gateways and labels |
| Concerns/problems | Register/read concern data and route mappings | Render concern cards, filters, and landing sections |
| Routines | Store routine definitions and product relationships | Render routine ladders and PDP/cart suggestions |
| Routine steps | Provide ordered steps and current-product context | Render step checklists and selected states |
| Canonical products | Resolve route/content/product to canonical Woo product ID | Link UI to canonical product/PDP/cart actions |
| Bundles/kits | Provide composition, savings, validation, and cart rules | Render bundle cards, bundle PDP blocks, and cart suggestions |
| FBT | Provide frequently-bought-together product IDs | Render FBT block with empty-safe UI |
| CBD/compliance | Determine flags, restrictions, notice copy source | Display notices and isolate CBD visual pathways |
| Ingredients | Store ingredient profiles and claim-safe descriptors | Render ingredient blocks without adding claims |
| Demo fixtures | Define safe fixture taxonomy/meta and import scripts | Render fixture data through normal templates |

---

## Namespace and Prefix Conventions

| Context | Convention | Example |
|---|---|---|
| Plugin functions (global) | `resq_core_*` for infrastructure; `resq_*` for public storefront helpers | `resq_core_get_option()`, `resq_get_product_routines()` |
| Theme functions (global) | `resq_theme_*` | `resq_theme_template_part()` |
| Plugin classes | PSR-4 `ResQ\Core\` | `ResQ\Core\Settings\Options` |
| Theme classes | `ResQ_Clean_Pro\` | `ResQ_Clean_Pro\Assets\Enqueue` |
| Plugin hooks | `resq_core_*` | `resq_core_feature_enabled` |
| Theme hooks | `resq_theme_*` | `resq_theme_before_product_card` |
| Option keys | `resq_core_*` | `resq_core_features` |
| Post meta keys | `_resq_*` | `_resq_compliance_flags` |
| Text domains | `resq-core` / `resq-clean-pro` | - |

Public storefront helpers intentionally use the shorter `resq_*` prefix because theme templates call them frequently. They are still plugin-owned.

---

## Required Helper Functions

These functions are the public API between layers. The theme may call plugin helpers. The plugin must not call theme helpers.

### Plugin Infrastructure Helpers

| Function | Purpose | Returns |
|---|---|---|
| `resq_core()` | Singleton/bootstrap accessor | plugin instance/container |
| `resq_core_get_option( string $key, mixed $default = null )` | Read plugin option with default | mixed |
| `resq_core_feature_enabled( string $feature )` | Check feature flag | bool |
| `resq_core_get_badge_data( int $product_id )` | Badge label/type/priority | `array|null` |
| `resq_core_get_cross_sells( int $product_id )` | Curated cross-sell product IDs | `int[]` |
| `resq_core_get_compliance_notices( string $context, int $product_id = 0, string $zone = '' )` | Notices for context; pass `$zone` (with `$product_id` 0) for zone-scoped slots | `array[]` |
| `resq_core_is_active()` | Whether plugin bootstrap completed | bool |

### Plugin Storefront Data Helpers

Phase 3 implemented all 19 storefront helpers with live data reads (see `12-PLUGIN-HELPER-CONTRACTS.md` and [`archive/phase-notes/14-PHASE-3-IMPLEMENTATION-NOTES.md`](archive/phase-notes/14-PHASE-3-IMPLEMENTATION-NOTES.md)). Theme templates call them behind `function_exists()` / `resq_core_is_active()` guards; empty arrays and nulls remain valid when data is absent.

| Function | Purpose | Returns |
|---|---|---|
| `resq_get_product_audiences( int $product_id )` | Audience terms | `array[]` |
| `resq_get_product_concerns( int $product_id )` | Concern/problem terms | `array[]` |
| `resq_get_product_routines( int $product_id )` | Routines including product | `array[]` |
| `resq_get_routine_steps( int $routine_id, int $current_product_id = 0 )` | Ordered routine steps | `array[]` |
| `resq_get_product_ingredient_profile( int $product_id )` | Claim-safe ingredient profile | `array[]` |
| `resq_get_canonical_product_id( int|string $source_id, string $source_type = 'product' )` | Resolve to canonical product | `int|null` |
| `resq_resolve_product_context( int|string $context_id, string $context_type )` | Page/term/route/gateway shopping context | `array` |
| `resq_is_cbd_product( int $product_id )` | Whether product is CBD-regulated | bool |
| `resq_requires_compliance_notice( int $product_id, string $context = 'pdp' )` | Whether notice must render | bool |
| `resq_get_compliance_zone( int $product_id )` | Compliance zone slug | string |
| `resq_can_cross_sell_products( int $source_product_id, int $target_product_id )` | Cross-sell safety gate | bool |
| `resq_get_product_routine_ladder( int $product_id )` | PDP routine ladder payload | `array` |
| `resq_get_recommended_routine_addons( int $product_id )` | Cart drawer suggestions | `array[]` |
| `resq_get_bundle_products( int $bundle_id )` | Products/qty in bundle | `array[]` |
| `resq_get_frequently_bought_together( int $product_id )` | FBT product IDs | `int[]` |
| `resq_get_product_card_data( int $product_id )` | Aggregated card payload | `array` |
| `resq_get_gateway_featured_products( string $gateway )` | Gateway shelf product IDs | `int[]` |
| `resq_get_learn_links_for_product( int $product_id )` | PDP Learn bridge links | `array[]` |
| `resq_get_product_badges( int $product_id )` | Badge objects sorted by priority | `array[]` |

### Theme Helpers

| Function | Purpose | Returns |
|---|---|---|
| `resq_theme_get_asset_url( string $path )` | Versioned asset URL | string |
| `resq_theme_template_part( string $slug, string $name = '', array $args = [] )` | Load template part with args | void |
| `resq_theme_class( string $base, array $modifiers = [] )` | Class string builder | string |
| `resq_theme_render_badge( int $product_id )` | Render badge markup from plugin data | void |
| `resq_theme_render_compliance_notices( string $context, int $product_id = 0, string $zone = '' )` | Render notice slot; pass `$zone` (with `$product_id` 0) for zone-scoped slots | void |
| `resq_theme_wc_active()` | Whether WooCommerce is active | bool |

Theme helpers that consume plugin data must guard with `function_exists()` or `resq_core_is_active()`.

---

## Fallback Behavior

### Theme Active, Plugin Deactivated

| Area | Expected behavior |
|---|---|
| Front-end | No PHP fatals; layouts render with Woo/native content only |
| Audience/problem/routine UI | Hidden or simplified; static navigation can remain |
| Badges, FBT, bundles, routine ladders | Hidden or empty; no broken wrappers |
| Canonical mappings | Theme links to native Woo product/category URLs only |
| CBD/compliance | No product-specific CBD logic; optional generic placeholder only if already configured |
| Learn-to-shop bridges | Render editorial content; product pulls are omitted |
| Woo templates | Standard Woo hooks/output still run |

Implementation rule: every theme call to a plugin-owned helper is wrapped in `function_exists()` or a plugin-active guard. Empty arrays/nulls are valid results and must not break markup.

### Plugin Active, Theme Switched

| Area | Expected behavior |
|---|---|
| Data layer | Options, meta, mappings, fixture definitions remain intact |
| Admin | Settings/admin fields remain accessible |
| Woo logic | Hooks still fire where Woo exposes standard hooks |
| Front-end | Data may be unused if the active theme has no render slots |
| Theme dependency | Plugin never requires theme files or constants |

Implementation rule: plugin never assumes `resq-clean-pro` is active. Optional enhancements may check `get_template()` but cannot be required for core behavior.

### WooCommerce Deactivated

| Layer | Expected behavior |
|---|---|
| Theme | Falls back to standard WP templates; no Woo-specific fatals |
| Plugin | Admin notice: WooCommerce required; Woo-facing hooks not registered |

---

## Metadata Ownership

All ResQ keys are plugin-registered. Theme reads via helpers and must not update plugin-owned keys.

### Options

| Option key | Owner | Purpose |
|---|---|---|
| `resq_core_version` | plugin | Schema/version tracking |
| `resq_core_features` | plugin | Feature flag map |
| `resq_core_settings` | plugin | General settings |
| `resq_core_compliance` | plugin | Compliance copy sources and toggles |
| `resq_core_merchandising` | plugin | Routine/bundle display settings |

### Product Meta

> Phase 2A schema: `11-PLUGIN-DATA-SCHEMA.md`. Helper contracts: `12-PLUGIN-HELPER-CONTRACTS.md`. Phase 3 delivery record: [`archive/phase-notes/14-PHASE-3-IMPLEMENTATION-NOTES.md`](archive/phase-notes/14-PHASE-3-IMPLEMENTATION-NOTES.md). Current status: [`CHECKPOINT.md`](CHECKPOINT.md).

| Meta key | Owner | Purpose | Consumer |
|---|---|---|---|
| `_resq_badge_label` | plugin | Custom badge text | product card, PDP |
| `_resq_badge_type` | plugin | Badge variant slug | theme CSS class |
| `_resq_audience_ids` | plugin | ~~Audience mappings~~ **Superseded** — use `resq_audience` taxonomy | — |
| `_resq_concern_ids` | plugin | ~~Concern mappings~~ **Superseded** — use `resq_concern` taxonomy | — |
| `_resq_routine_ids` | plugin | Routine memberships (`resq_routine` CPT post IDs) | routine ladder |
| `_resq_primary_routine_id` | plugin | Featured routine when product belongs to many | PDP routine ladder |
| `_resq_routine_step_order` | plugin | Optional step position hint on product | routine ladder |
| `_resq_canonical_product_id` | plugin | Canonical parent override where needed | route resolver |
| `_resq_bundle_product_ids` | plugin | Bundle/kit composition | bundle card/PDP/cart |
| `_resq_fbt_product_ids` | plugin | Manual FBT override | FBT block |
| `_resq_compliance_flags` | plugin | Writable CBD/medical/pet/baby/proof/donation risk flags | notices/restrictions |
| `_resq_compliance_zone` | plugin | Compliance zone slug (`standard`, `cbd`, `baby`, `pet-health`) | isolation, filters, notices |
| `_resq_ingredient_profile` | plugin | Ingredient IDs/descriptors | ingredient blocks |
| `_resq_short_benefit_tags` | plugin | Claim-safe benefit tags for cards/PDP | product card, PDP |
| `_resq_product_card_subtitle` | plugin | Optional card subtitle line | product card |
| `_resq_gateway_featured` | plugin | Gateway slugs where product is featured | gateway shelves |
| `_resq_learn_links` | plugin | Learn guide post IDs/labels | PDP Learn bridge |
| `_resq_donation_eligible` | plugin | Donation/mission display eligibility | mission modules |

**Not stored as meta (helper-derived):** `_resq_cbd_product`, `_resq_requires_compliance_notice` — computed from `_resq_compliance_flags`, `_resq_compliance_zone`, and context. See `11-PLUGIN-DATA-SCHEMA.md`.

**Taxonomies (registered in Phase 3):** `resq_audience`, `resq_concern`, `resq_ingredient`, `resq_product_role`, `resq_compliance_zone` — assigned to products. Audience and concern use native taxonomy assignment, not `_ids` meta.

Standard Woo meta such as `_price`, `_stock`, `_sku`, `_tax_class`, and variation attributes remains Woo-owned.

### Term and Route Meta

| Meta key | Owner | Purpose |
|---|---|---|
| `_resq_category_hero_image` | plugin | Category/gateway hero image |
| `_resq_category_intro` | plugin | Category/gateway intro copy |
| `_resq_audience_type` | plugin | Human/pet/bundle/CBD/Learn planning flag |
| `_resq_compliance_category` | plugin | CBD or other regulated category flag |
| `_resq_canonical_targets` | plugin | Product IDs pointed to by landing/Learn/category content |

### Transients

| Transient pattern | Owner | TTL | Purpose |
|---|---|---|---|
| `resq_core_fbt_{product_id}` | plugin | 12h | Cached FBT computation |
| `resq_core_cross_sell_{product_id}` | plugin | 12h | Cached cross-sell rules |
| `resq_core_routine_{routine_id}` | plugin | 12h | Cached routine steps |
| `resq_core_route_target_{hash}` | plugin | 12h | Cached canonical route mapping |

Transients clear on plugin deactivation. Options and post meta are not deleted on deactivation.

---

## WooCommerce Integration Boundaries

| Concern | Owner | Mechanism |
|---|---|---|
| PLP/PDP/cart/checkout HTML structure | theme | Woo template overrides |
| Gateway/category layouts | theme | Page templates/template parts |
| Product card markup | theme | `content-product.php` + template parts |
| Product card data | Woo + plugin | Woo product APIs + plugin helpers |
| Routine ladder data | plugin | `resq_get_product_routines()`, `resq_get_routine_steps()` |
| Routine ladder markup | theme | `template-parts/product/routine-ladder.php` |
| Bundle/kit composition | plugin | `resq_get_bundle_products()` + Woo/cart validation |
| Bundle card UI | theme | `template-parts/product/bundle-card.php` |
| FBT product IDs | plugin | `resq_get_frequently_bought_together()` |
| FBT markup | theme | `template-parts/product/frequently-bought-together.php` |
| CBD/compliance decisions | plugin | flags, helpers, notices, restrictions |
| Compliance notices display | theme | notice slots across surfaces |
| Cart drawer suggestions | shared | plugin data, theme drawer UI |
| Checkout fields/validation | plugin | Woo filters/actions |
| Checkout layout | theme | Woo checkout templates |
| Product tabs content registration | plugin | `woocommerce_product_tabs` |
| Product tabs style/layout | theme | tab templates/CSS |
| Related/upsell args | Woo + plugin | Woo product relationships + plugin filters |
| Account endpoints | plugin | endpoint registration |
| Account templates | theme | Woo account overrides |

Boundary line: plugin returns arrays, IDs, booleans, and value objects; theme renders HTML.

---

## Activation and Deactivation Expectations

### Plugin Activation

1. Set default options if missing.
2. Store `resq_core_version`.
3. Register future taxonomies/meta/CPTs when introduced.
4. Flush rewrite rules only when route structures are registered.
5. Produce no front-end output during activation.

### Plugin Deactivation

1. Clear plugin transients.
2. Unschedule plugin cron events.
3. Preserve options, post meta, term meta, and user data.
4. Leave WooCommerce products/orders untouched.

### Theme Activation

1. Register nav menu locations when implemented.
2. Set display-only theme defaults if needed.
3. Do not create product data, plugin options, or fixture content.

---

## Communication Between Layers

| Need | Pattern |
|---|---|
| Theme needs settings/data | Plugin helper returns safe data |
| Plugin needs display | Plugin exposes data; theme renders via template part |
| New product field | Plugin registers meta/admin UI; theme reads helper |
| New storefront slot | Theme adds template part; plugin optionally supplies data |
| Compliance logic | Plugin decides; theme displays |
| Learn route points to product | Plugin resolves canonical product; theme links/renders |

### Anti-patterns

- Plugin echoing markup for routine ladders, bundle cards, cart drawer, or gateway pages
- Theme registering or mutating product meta
- Duplicate Woo products created only to support different landing-page copy
- CBD products cross-sold into standard skincare/pet care surfaces without explicit compliance approval
- Theme hardcoding source-blueprint example URLs, prices, claims, or product lists as final truth

---

## File Naming and Structure

### Theme

```text
resq-clean-pro/
  style.css
  functions.php
  index.php
  header.php, footer.php
  template-parts/
    navigation/
    archive/
    product/
    cart/
    compliance/
    learn/
  woocommerce/
  assets/css/, assets/js/
```

### Plugin

```text
resq-core/
  resq-core.php
  includes/
    class-plugin.php
    settings/
    woocommerce/
    merchandising/
    compliance/
    fixtures/
    admin/
  languages/
```

One concern per class file. Bootstrap files stay thin.

---

## Change Checklist

- [ ] Does this change preserve the theme/plugin/WooCommerce boundary?
- [ ] Are new helpers documented before theme use?
- [ ] Are new meta/options documented here?
- [ ] Does `03-WOO-TEMPLATE-MAP.md` list the affected surface?
- [ ] Does plugin-inactive fallback avoid fatals and broken markup?
- [ ] Does the change avoid rewriting source blueprints in place?
- [ ] Does any sensitive claim route through `05-COMPLIANCE-RULES.md`?
