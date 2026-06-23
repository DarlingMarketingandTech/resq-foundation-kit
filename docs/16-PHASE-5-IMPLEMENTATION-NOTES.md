# 16 — Phase 5 Implementation Notes

> WooCommerce template shells for `resq-clean-pro`.

## Checkpoint Context

| Item | Value |
|---|---|
| Phase 4 | Theme global foundation — complete |
| Phase 5 | WooCommerce template shells — in progress |
| Theme version | `0.3.0` |
| Branch target | `phase-5-woo-template-shells` |
| Tag target | `v0.6-phase-5-woo-template-shells` |

---

## What Phase 5 Implements

### New template overrides (woocommerce/)

| Path | Surface | What it adds over stock WC |
|---|---|---|
| `woocommerce/archive-product.php` | Shop/category archive | `resq-shop-archive` wrapper class; compliance notice slot after archive description |
| `woocommerce/content-product.php` | Product card | `resq-product-card` class; `resq-product-card--cbd` modifier; badge slot before WC item link; compliance notice slot after WC hooks |
| `woocommerce/single-product.php` | PDP page wrapper | `resq-single-product` wrapper class; delegates content to WC via `wc_get_template_part` |
| `woocommerce/cart/cart.php` | Cart page | `resq-cart-form` class; compliance notice slot before cart table; `resq-cart-collaterals` wrapper |
| `woocommerce/cart/cart-empty.php` | Empty cart | `resq-cart-empty` wrapper; compliance notice slot |
| `woocommerce/checkout/form-checkout.php` | Checkout form | `resq-checkout-form` class; compliance notice slot above customer details |
| `woocommerce/myaccount/my-account.php` | My Account | `resq-my-account` wrapper; `resq-my-account__content` around content hook |

### New WordPress template

| Path | Surface | Notes |
|---|---|---|
| `search.php` | Search results | Product results use `content-product.php`; non-product results use standard post excerpt |

### New PDP template parts

| Path | Plugin helper | Returns empty-safe when |
|---|---|---|
| `template-parts/product/ingredient-profile.php` | `resq_get_product_ingredient_profile()` | Plugin inactive, no ingredients, or all items have empty labels |
| `template-parts/product/frequently-bought-together.php` | `resq_get_frequently_bought_together()` | Plugin inactive, no FBT IDs, or all resolved products are invalid |

### New include file

| Path | Purpose |
|---|---|
| `inc/woocommerce.php` | WC gallery theme supports (`wc-product-gallery-zoom/lightbox/slider`); PDP hook registrations for compliance, routine ladder, ingredient profile, FBT |

### PDP hook slot sequence

| Action hook | Priority | Slot |
|---|---|---|
| `woocommerce_single_product_summary` | 25 | Compliance notices (between excerpt/20 and ATC/30) |
| `woocommerce_after_single_product_summary` | 5 | Routine ladder (from Phase 4, now wired) |
| `woocommerce_after_single_product_summary` | 10 | Ingredient profile |
| `woocommerce_after_single_product_summary` | 15 | FBT |

---

## Plugin Guard Pattern

All Phase 5 template parts follow the same guard structure established in Phase 4:

```php
if (
    ! function_exists( 'resq_core_is_active' )
    || ! resq_core_is_active()
    || ! function_exists( 'resq_<helper_name>' )
) {
    return;
}
```

`inc/woocommerce.php` itself guards at the file level:

```php
if ( ! resq_theme_wc_active() ) {
    return;
}
```

---

## Compliance Note

- Ingredient descriptors are only rendered when `claim_safe === true` — items with `claim_safe: false` display the label only.
- FBT IDs from `resq_get_frequently_bought_together()` have already passed `resq_can_cross_sell_products()` in the plugin layer; no additional CBD/audience filtering is done in the template.
- Compliance notice context values used: `category`, `card`, `pdp`, `cart`, `checkout`.

---

## Exit Criteria

- [x] Every Phase 5 surface loads without PHP notices.
- [x] Woo hooks still fire (all standard WC action hooks preserved in every override).
- [x] Empty plugin data creates empty-safe UI.
- [x] Template map (`03-WOO-TEMPLATE-MAP.md`) remains current.

---

## Deferred

| Item | Phase |
|---|---|
| Gateway page templates (Human, Pet, CBD, Bundles) | 6 |
| Learn index and Learn-to-shop bridge | 6 |
| Cart drawer UI | 8 |
| Related products / cross-sell restrictions | 8 |
| Bundle PDP and bundle card parts | 8 |
| Filter UI | 6 |
| PDP tabs customization | 8 |
| Thank-you page override | As needed |

---

## Read Next

1. `06-BUILD-ROADMAP.md` — Phase 6 gateway and Learn surfaces
2. `03-WOO-TEMPLATE-MAP.md` — override inventory and phase alignment
3. `12-PLUGIN-HELPER-CONTRACTS.md` — helper signatures consumed by Phase 5 templates
