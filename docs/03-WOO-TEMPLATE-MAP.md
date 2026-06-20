# 03 — Woo Template Map

> Inventory of WooCommerce storefront surfaces: default source, override location, owner, status, and hook strategy.

Use skill `woo-template-planner` before adding or moving overrides.

## Override root

Theme path: `wp-content/themes/resq-clean-pro/woocommerce/`

## Legend

| Column | Meaning |
|---|---|
| **Owner** | `theme` = markup/layout · `plugin` = logic/data · `shared` = plugin data + theme render |
| **Status** | `planned` · `in-progress` · `done` |
| **Prefer** | `template` = override file · `hook` = Woo action/filter · `both` |

---

## Storefront surface inventory

### 1. Shop archive

| Attribute | Value |
|---|---|
| Woo template | `archive-product.php` (theme root, not under `woocommerce/`) |
| Theme override | `resq-clean-pro/archive-product.php` |
| Owner | theme |
| Status | planned |
| Prefer | template |

**Planned customizations:**

- Page header with shop title and optional intro (from plugin option or static theme mod)
- Product grid using `content-product.php`
- Toolbar: result count, ordering, pagination (Woo hooks)
- Empty state when no products match

**Key hooks:**

- `woocommerce_before_shop_loop` / `woocommerce_after_shop_loop`
- `woocommerce_shop_loop_item_title`
- Plugin may filter `woocommerce_product_query` for default sort rules (Phase 7)

**Differs from category archive:** Shop is the main catalog index; no term-specific hero or intro unless globally configured.

---

### 2. Category archive

| Attribute | Value |
|---|---|
| Woo template | `taxonomy-product_cat.php` or falls back to `archive-product.php` |
| Theme override | `archive-product.php` (shared) + `template-parts/archive/category-header.php` |
| Owner | shared |
| Status | planned |
| Prefer | both |

**Planned customizations:**

- Category hero (image + intro from term meta via plugin)
- Subcategory tiles when parent category has children
- Same product grid/card as shop archive
- Breadcrumbs

**Key hooks:**

- `woocommerce_before_main_content`
- Plugin provides term meta: `_resq_category_hero_image`, `_resq_category_intro`
- Theme template part reads via helper or direct meta through plugin wrapper

**Planning categories (top-level only):** Pets, People, CBD, Bundles, Learn — used as fixture taxonomy labels in Phase 5, not hardcoded in templates.

---

### 3. Product card (PLP loop item)

| Attribute | Value |
|---|---|
| Woo template | `woocommerce/content-product.php` |
| Theme override | `woocommerce/content-product.php` |
| Owner | theme |
| Status | planned |
| Prefer | template |

**Card anatomy (markup order):**

1. Product link wrapper
2. Image (`woocommerce_template_loop_product_thumbnail`)
3. Badge slot — theme calls `resq_theme_render_badge()` (plugin data)
4. Title
5. Price
6. Optional short excerpt / attribute hint (Phase 7)
7. Add to cart or "View product" CTA

**Key hooks:**

- `resq_theme_before_product_card` / `resq_theme_after_product_card` (theme)
- `woocommerce_before_shop_loop_item_title`
- Badge data: `resq_core_get_badge_data()` (plugin)

---

### 4. Single product page (PDP)

| Attribute | Value |
|---|---|
| Woo templates | `single-product.php` + `woocommerce/content-single-product.php` |
| Theme overrides | `single-product.php`, `woocommerce/content-single-product.php` |
| Partial overrides | `woocommerce/single-product/product-image.php`, `tabs/tabs.php`, `add-to-cart/*.php` |
| Owner | shared |
| Status | planned |
| Prefer | both |

**PDP zones:**

| Zone | Owner | Mechanism |
|---|---|---|
| Gallery | theme | `product-image.php` override |
| Title, price, rating | theme | Standard Woo templates |
| Add to cart | theme | Variable/simple add-to-cart partials |
| Badge | shared | Plugin data, theme render |
| Trust signals (shipping, returns) | shared | Plugin content source, theme placement |
| Product tabs (description, attributes) | theme layout | Woo tabs; plugin may add tab via filter |
| Compliance notices (CBD, disclaimers) | shared | `resq_theme_render_compliance_notices( 'pdp' )` |
| Upsells | theme render | Woo hook `woocommerce_after_single_product_summary` |
| Cross-sells (inline PDP) | shared | Plugin IDs, theme template part |
| FBT block | shared | See section 11 |
| Sticky add-to-cart (mobile) | theme | JS + CSS; Phase 8 polish |

**Key hooks:**

- `woocommerce_single_product_summary`
- `woocommerce_after_single_product_summary` (related, upsells priority 15/20)
- `woocommerce_product_tabs` (plugin adds compliance/spec tabs if needed)

---

### 5. Cart

| Attribute | Value |
|---|---|
| Woo templates | `woocommerce/cart/cart.php`, `cart/cart-empty.php`, `cart/cart-totals.php`, `cart/cross-sells.php` |
| Theme overrides | Same paths under theme `woocommerce/cart/` |
| Owner | shared |
| Status | planned |
| Prefer | template |

**Planned layout:**

- Line items table (responsive stack on mobile)
- Coupon area (Woo native; plugin may restrict coupons later)
- Cart totals sidebar / below on mobile
- Cross-sells block (Woo default; theme styles)
- Trust/compliance notice slot above checkout CTA — `resq_theme_render_compliance_notices( 'cart' )`
- Empty cart: CTA back to shop + optional featured categories

**Plugin boundaries:**

- Cart item data, fees, notices logic → plugin hooks
- Markup and responsive layout → theme templates

---

### 6. Checkout

| Attribute | Value |
|---|---|
| Woo templates | `woocommerce/checkout/form-checkout.php`, `form-billing.php`, `form-shipping.php`, `payment.php`, `review-order.php`, `thankyou.php` |
| Theme overrides | `woocommerce/checkout/*.php` (incremental — start with `form-checkout.php`) |
| Owner | shared |
| Status | planned |
| Prefer | both |

**Planned sections:**

1. Compliance notice banner (checkout context) — required for CBD-age/disclaimer when enabled
2. Billing / shipping columns
3. Order review
4. Payment gateway area (unchanged gateway markup; theme styles only)
5. Terms checkbox area
6. Thank-you page: order summary + continue shopping

**Plugin boundaries:**

- `woocommerce_checkout_fields` — add/remove/validate fields
- Checkout notices, validation messages
- Age verification or category gates (logic only; theme renders modal/banner)

**Safety:** Do not override payment gateway iframe internals. Style wrappers only. See `05-COMPLIANCE-RULES.md`.

---

### 7. My Account

| Attribute | Value |
|---|---|
| Woo templates | `woocommerce/myaccount/my-account.php`, `navigation.php`, `dashboard.php`, `orders.php`, `view-order.php`, `form-login.php`, `form-edit-account.php`, `form-edit-address.php` |
| Theme overrides | Start with `my-account.php`, `navigation.php`, `dashboard.php`; expand as needed |
| Owner | theme (layout) + plugin (custom endpoints if any) |
| Status | planned |
| Prefer | template |

**Planned layout:**

- Sidebar navigation (collapsible on mobile)
- Dashboard welcome + recent orders snippet
- Consistent form styling with checkout

**Plugin (future):**

- Custom endpoints (e.g. subscriptions, rescue program) registered in plugin
- Templates still live in theme under `woocommerce/myaccount/`

---

### 8. Search (product + content)

| Attribute | Value |
|---|---|
| Woo behavior | Product search via `?s=` + `post_type=product` or Woo product search widget |
| WP template | `search.php` (theme root) |
| Theme overrides | `search.php`, optionally `woocommerce/product-searchform.php` |
| Owner | theme |
| Status | planned |
| Prefer | template |

**Planned behavior:**

- Unified search results page with product-first layout when Woo active
- Product results use `content-product.php` in a grid
- Non-product results (Learn content) use separate loop partial `template-parts/content-search.php`
- Empty state with category links (Pets, People, CBD, Bundles, Learn)

**Plugin:** May filter `pre_get_posts` for search relevance weighting (Phase 7). No search template in plugin.

---

### 9. Related products

| Attribute | Value |
|---|---|
| Woo default | Hooked via `woocommerce_after_single_product_summary` → `woocommerce_output_related_products` |
| Theme override | Optional `woocommerce/single-product/related.php` OR template part via hook removal/re-add |
| Owner | shared |
| Status | planned |
| Prefer | hook (Phase 4 shell) → template (Phase 8 polish) |

**Strategy:**

- Phase 4: Keep Woo hook; style via CSS targeting `.related.products`
- Phase 7+: Plugin filters `woocommerce_output_related_products_args` for count/columns/rules
- Phase 8: Custom `related.php` if card design diverges from PLP card

---

### 10. Bundles

| Attribute | Value |
|---|---|
| Dependency | WooCommerce Product Bundles extension **or** custom bundle product type (decision Phase 7) |
| Theme template | `woocommerce/single-product/add-to-cart/bundle.php` (if extension used) or custom template part |
| Owner | shared |
| Status | planned |
| Prefer | both |

**Contract (until extension chosen):**

- Plugin owns bundle configuration data and cart validation hooks
- Theme owns bundle PDP layout template part: `template-parts/product/bundle-options.php`
- PLP card shows bundle indicator badge via `resq_core_get_badge_data()`

**Planning category:** Bundles — fixture products in Phase 5 use simple grouped/bundle-like placeholders without final pricing.

---

### 11. Frequently bought together (FBT)

| Attribute | Value |
|---|---|
| Not a native Woo template | Custom block on PDP |
| Theme template part | `template-parts/product/frequently-bought-together.php` |
| Owner | shared |
| Status | planned |
| Prefer | both |

**Interface contract:**

```php
// Plugin provides
$product_ids = resq_core_get_fbt_products( $product_id ); // int[]

// Theme renders
resq_theme_template_part( 'product/frequently-bought-together', null, [
    'product_ids' => $product_ids,
    'primary_id'  => $product_id,
] );
```

**Data sources (Phase 7 priority order):**

1. Manual `_resq_fbt_product_ids` post meta
2. Order-history-based rules (plugin)
3. Category affinity fallback

**Placement:** Below add-to-cart on PDP, above related products hook priority.

---

### 12. Compliance notices

| Attribute | Value |
|---|---|
| Not a single Woo template | Rendered slots across surfaces |
| Theme template part | `template-parts/compliance/notices.php` |
| Owner | shared |
| Status | planned |
| Prefer | both |

**Contexts and placement:**

| Context | Surfaces | Owner split |
|---|---|---|
| `global` | Footer, sitewide banner | plugin rules, theme slot in `footer.php` |
| `pdp` | Below price or above add-to-cart | CBD/product flags from `_resq_compliance_flags` |
| `cart` | Above proceed to checkout | plugin content |
| `checkout` | Top of form | age attestation, shipping restrictions |
| `cbd` | Category + PDP + checkout | stricter copy set; no final legal text in repo until compliance review |

**Plugin API:**

```php
resq_core_get_compliance_notices( string $context ); // array of [ 'id', 'message', 'type', 'dismissible' ]
```

**Theme:** `resq_theme_render_compliance_notices( $context )` — never hard-code jurisdiction-specific legal claims in theme files.

See `05-COMPLIANCE-RULES.md` for non-negotiable constraints.

---

## Master inventory table

| Surface | Woo default | Theme override path | Owner | Status | Prefer |
|---|---|---|---|---|---|
| Shop archive | `archive-product.php` | `archive-product.php` | theme | planned | template |
| Category archive | `archive-product.php` / taxonomy | `archive-product.php` + category header part | shared | planned | both |
| Product card | `content-product.php` | `woocommerce/content-product.php` | theme | planned | template |
| Single product | `single-product.php` | `single-product.php` + partials | shared | planned | both |
| Cart | `cart/cart.php` | `woocommerce/cart/cart.php` | shared | planned | template |
| Cart empty | `cart/cart-empty.php` | `woocommerce/cart/cart-empty.php` | theme | planned | template |
| Checkout | `checkout/form-checkout.php` | `woocommerce/checkout/form-checkout.php` | shared | planned | both |
| Thank you | `checkout/thankyou.php` | `woocommerce/checkout/thankyou.php` | theme | planned | template |
| My Account | `myaccount/my-account.php` | `woocommerce/myaccount/my-account.php` | theme | planned | template |
| Search | `search.php` | `search.php` | theme | planned | template |
| Related products | hook + `related.php` | optional `woocommerce/single-product/related.php` | shared | planned | hook |
| Bundles | extension-dependent | `template-parts/product/bundle-options.php` | shared | planned | both |
| FBT | custom | `template-parts/product/frequently-bought-together.php` | shared | planned | both |
| Compliance notices | custom slots | `template-parts/compliance/notices.php` | shared | planned | both |
| Emails | `emails/*.php` | optional `woocommerce/emails/` | shared | planned | template |

---

## Hooks vs templates decision guide

| Behavior | Prefer | Notes |
|---|---|---|
| Layout / HTML structure | Template override | Keep logic minimal |
| Price formatting, labels | Plugin filter | `woocommerce_get_price_html` |
| Add cart notice | Plugin hook | Theme styles via `.woocommerce-message` |
| Custom product tab content | Plugin registers | Theme styles tab |
| Related product count | Plugin filter | Theme renders output |
| FBT / compliance blocks | Template part | Plugin supplies data array |
| Dequeue Woo assets | Theme (careful) | Measure impact; document in PR |

---

## CodeGraph workflow

Before editing a template:

1. Query CodeGraph for existing overrides and callers
2. Confirm no duplicate override in old theme paths
3. Update this map in the same PR
4. Confirm owner column still accurate (theme vs plugin vs shared)

---

## Verification checklist (per template)

- [ ] Template loads without PHP notices (with and without plugin active)
- [ ] Mobile layout checked
- [ ] Plugin hooks still fire after template change
- [ ] Compliance slots present where required for surface
- [ ] Fallback behavior matches `01-THEME-PLUGIN-CONTRACT.md`
- [ ] Entry added/updated in master inventory table above

## Phase alignment

| Phase | Template map work |
|---|---|
| Phase 1 | This document complete (contract lock) |
| Phase 4 | Shell override files created for all `planned` rows |
| Phase 6 | Smoke test every surface |
| Phase 7 | Merchandising hooks wired (FBT, related, badges) |
| Phase 8 | Visual polish, optional `related.php`, sticky ATC |
