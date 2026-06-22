# 12 — Plugin Helper Contracts

> Public helper function contracts for `resq-core`. Theme may call these functions. Plugin must not call theme helpers. **Phase 2B implemented empty-safe stubs for all helpers; Phase 3 replaces stubs with real data reads.**

## Status

| Item | Value |
|---|---|
| Phase | 2B complete — contracts + empty-safe stubs; Phase 3 complete — real data reads |
| Implementation | Live reads in `wp-content/plugins/resq-core/includes/helpers/` |
| Schema reference | `11-PLUGIN-DATA-SCHEMA.md` |
| Ownership | `01-THEME-PLUGIN-CONTRACT.md` |

## Global Rules

1. All helpers are plugin-owned global functions prefixed with `resq_` (storefront) or `resq_core_` (infrastructure).
2. Never throw exceptions to the theme. Return empty arrays, `null`, `false`, or empty strings.
3. Invalid or missing `$product_id` returns safe empty defaults.
4. Theme must wrap calls in `function_exists()` or `resq_core_is_active()` guards.
5. Helpers return data only — no HTML, no echoed output.
6. Variation IDs resolve to parent product ID where noted.

---

## Product Intelligence

### `resq_get_product_audiences`

| Field | Value |
|---|---|
| Purpose | Return audience terms assigned to a product |
| Signature | `resq_get_product_audiences( int $product_id ): array` |
| Parameters | `$product_id` — WooCommerce product or variation ID |
| Return type | `array[]` — list of audience objects |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Requires active WooCommerce; product must exist |
| Data dependencies | `resq_audience` taxonomy |

**Example return:**

```php
[
    [
        'term_id' => 12,
        'slug'    => 'human',
        'name'    => 'Human',
        'label'   => 'Human',
    ],
    [
        'term_id' => 13,
        'slug'    => 'pet',
        'name'    => 'Pet',
        'label'   => 'Pet',
    ],
]
```

**Fallback:** `[]` when product not found, plugin inactive, or no terms assigned.

**Error handling:** Invalid ID → `[]`. No PHP notices.

---

### `resq_get_product_concerns`

| Field | Value |
|---|---|
| Purpose | Return concern/problem terms for discovery and filters |
| Signature | `resq_get_product_concerns( int $product_id ): array` |
| Parameters | `$product_id` — WooCommerce product or variation ID |
| Return type | `array[]` — list of concern objects with parent context |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Requires active WooCommerce |
| Data dependencies | `resq_concern` taxonomy |

**Example return:**

```php
[
    [
        'term_id'   => 45,
        'slug'      => 'hot-spots',
        'name'      => 'Hot Spots',
        'label'     => 'Hot Spots',
        'parent_id' => 40,
        'parent_slug' => 'pet-topical',
    ],
]
```

**Fallback:** `[]`.

**Error handling:** Invalid ID → `[]`.

---

### `resq_get_product_routines`

| Field | Value |
|---|---|
| Purpose | Return routines that include this product |
| Signature | `resq_get_product_routines( int $product_id ): array` |
| Parameters | `$product_id` — WooCommerce product or variation ID |
| Return type | `array[]` — list of routine summary objects |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Requires active WooCommerce |
| Data dependencies | `_resq_routine_ids` meta, `resq_routine` CPT |

**Example return:**

```php
[
    [
        'routine_id'    => 201,
        'title'         => 'Daily Manuka Regimen',
        'slug'          => 'daily-manuka-regimen',
        'step_count'    => 3,
        'is_primary'    => true,
        'bundle_target' => 305,
    ],
]
```

**Fallback:** `[]`. `is_primary` is `true` when routine ID matches `_resq_primary_routine_id`, or first routine when unset.

**Error handling:** Invalid ID → `[]`.

---

### `resq_get_routine_steps`

| Field | Value |
|---|---|
| Purpose | Return ordered steps for a routine |
| Signature | `resq_get_routine_steps( int $routine_id, int $current_product_id = 0 ): array` |
| Parameters | `$routine_id` — `resq_routine` CPT post ID; `$current_product_id` — optional marker for current PDP product |
| Return type | `array[]` — ordered step objects |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Steps reference Woo product IDs |
| Data dependencies | `_resq_routine_steps` meta on routine CPT |

**Example return:**

```php
[
    [
        'order'           => 1,
        'title'           => 'Cleanse',
        'product_id'      => 101,
        'canonical_id'    => 101,
        'bundle_id'       => 0,
        'is_current'      => false,
        'is_optional'     => false,
        'product_title'   => 'Face & Body Wash',
        'product_url'     => 'https://example.com/product/face-body-wash/',
        'in_stock'        => true,
    ],
    [
        'order'           => 2,
        'title'           => 'Treat',
        'product_id'      => 102,
        'canonical_id'    => 102,
        'bundle_id'       => 0,
        'is_current'      => true,
        'is_optional'     => false,
        'product_title'   => 'All-in-One Treatment',
        'product_url'     => 'https://example.com/product/all-in-one-treatment/',
        'in_stock'        => true,
    ],
]
```

**Fallback:** `[]` when routine not found or has no steps.

**Error handling:** Invalid routine ID → `[]`. Missing step products are omitted or marked `in_stock => false` per Phase 3 policy.

---

### `resq_get_product_ingredient_profile`

| Field | Value |
|---|---|
| Purpose | Return claim-safe ingredient profile for PDP and Learn modules |
| Signature | `resq_get_product_ingredient_profile( int $product_id ): array` |
| Parameters | `$product_id` — WooCommerce product or variation ID |
| Return type | `array[]` — list of ingredient descriptor objects |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Requires active WooCommerce |
| Data dependencies | `_resq_ingredient_profile` meta, `resq_ingredient` taxonomy |

**Example return:**

```php
[
    [
        'term_id'     => 60,
        'term_slug'   => 'manuka-honey',
        'label'       => 'Certified Organic Manuka Honey',
        'descriptor'  => 'Supports hydration and skin comfort.',
        'claim_safe'  => true,
    ],
]
```

**Fallback:** `[]`.

**Error handling:** Invalid ID → `[]`. Theme must not append claims beyond returned descriptors.

---

## Canonical Mapping

### `resq_get_canonical_product_id`

| Field | Value |
|---|---|
| Purpose | Resolve any source to a canonical WooCommerce product ID |
| Signature | `resq_get_canonical_product_id( int|string $source_id, string $source_type = 'product' ): ?int` |
| Parameters | `$source_id` — ID or route slug; `$source_type` — resolver context |
| Return type | `?int` — canonical product ID or `null` |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Yes |
| Data dependencies | `_resq_canonical_product_id`, `_resq_canonical_targets`, routine/bundle mappings |

**Allowed `$source_type` values:**

`product`, `variation`, `term`, `page`, `route`, `routine`, `bundle`, `learn`

**Resolution priority** (from `09-CANONICAL-PRODUCT-STRATEGY.md`):

1. `product` — return self or `_resq_canonical_product_id` override
2. `variation` — parent product ID
3. `bundle` — bundle product ID (self)
4. `routine` — first step product or routine bundle target
5. `term` — `_resq_canonical_targets` on term
6. `page` — `_resq_canonical_targets` on page
7. `route` — route mapping table (Phase 3)
8. `learn` — Learn post canonical targets

**Example:**

```php
resq_get_canonical_product_id( 102, 'product' );  // 102
resq_get_canonical_product_id( 550, 'variation' ); // parent ID
resq_get_canonical_product_id( 'hot-spots', 'route' ); // null if unmapped
```

**Fallback:** `null` when no mapping exists. Theme hides product bridges.

**Error handling:** Unknown `$source_type` → `null`.

---

### `resq_resolve_product_context`

| Field | Value |
|---|---|
| Purpose | Resolve a page, term, route, or post into shopping context for templates |
| Signature | `resq_resolve_product_context( int|string $context_id, string $context_type ): array` |
| Parameters | `$context_id` — WP post ID, term ID, or route slug; `$context_type` — `page`, `term`, `route`, `learn`, `gateway` |
| Return type | `array` — context object |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Partial — product IDs when mapped |
| Data dependencies | Term/page meta, taxonomies, gateway config |

**Example return:**

```php
[
    'context_type'     => 'gateway',
    'context_id'       => 'human',
    'audience'         => 'human',
    'concerns'         => [],
    'featured_products'=> [101, 102, 103],
    'canonical_targets'=> [101, 102],
    'compliance_zone'  => 'standard',
    'filters'          => [
        'audience' => ['human'],
        'concern'  => [],
    ],
]
```

**Fallback:** Minimal context with empty arrays when unmapped:

```php
[
    'context_type'      => $context_type,
    'context_id'        => $context_id,
    'audience'          => '',
    'concerns'          => [],
    'featured_products' => [],
    'canonical_targets' => [],
    'compliance_zone'   => 'standard',
    'filters'           => [],
]
```

**Error handling:** Never throws. Unknown context → empty-safe defaults.

---

## Compliance

### `resq_is_cbd_product`

| Field | Value |
|---|---|
| Purpose | Whether product is in the CBD-regulated lane |
| Signature | `resq_is_cbd_product( int $product_id ): bool` |
| Parameters | `$product_id` — WooCommerce product or variation ID |
| Return type | `bool` |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Yes |
| Data dependencies | `_resq_compliance_flags` (contains `cbd`), `resq_compliance_zone` term/meta |

**Derivation:** Returns `true` when compliance zone is `cbd` OR `_resq_compliance_flags` includes `cbd`.

**Fallback:** `false`.

**Error handling:** Invalid ID → `false`.

---

### `resq_requires_compliance_notice`

| Field | Value |
|---|---|
| Purpose | Whether a notice slot must render for product and context |
| Signature | `resq_requires_compliance_notice( int $product_id, string $context = 'pdp' ): bool` |
| Parameters | `$product_id` — product ID; `$context` — `pdp`, `category`, `cart`, `checkout`, `card` |
| Return type | `bool` |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Yes |
| Data dependencies | `_resq_compliance_flags`, `_resq_compliance_zone`, `resq_core_compliance` options |

**Derivation:** Not stored. Computed from flags, zone, context, and global toggles.

**Fallback:** `false`.

**Error handling:** Invalid ID → `false`.

---

### `resq_get_compliance_zone`

| Field | Value |
|---|---|
| Purpose | Return compliance zone slug for structural isolation |
| Signature | `resq_get_compliance_zone( int $product_id ): string` |
| Return type | `string` — `standard`, `cbd`, `baby`, `pet-health` |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Yes |
| Data dependencies | `_resq_compliance_zone` meta, `resq_compliance_zone` taxonomy |

**Fallback:** `'standard'`.

**Error handling:** Invalid ID → `'standard'`.

---

### `resq_can_cross_sell_products`

| Field | Value |
|---|---|
| Purpose | Whether `$target_product_id` may be suggested from `$source_product_id` context |
| Signature | `resq_can_cross_sell_products( int $source_product_id, int $target_product_id ): bool` |
| Parameters | Source and target Woo product IDs |
| Return type | `bool` |
| Theme-safe | Yes |
| REST later | Yes — critical for recommendation APIs |
| WooCommerce dependency | Yes |
| Data dependencies | Compliance zones, audiences, flags, `resq_core_compliance['cbd_isolation_enabled']` |

**Rules (default when CBD isolation enabled):**

- `cbd` zone → `standard` zone: **blocked**
- `standard` zone → `cbd` zone: **blocked**
- Human audience → pet audience: **blocked** unless explicit bundle context
- Baby-flagged products: **blocked** from generic cart upsells
- Same zone + compatible audience: **allowed**

**Fallback:** `false` when either product invalid.

**Error handling:** Invalid IDs → `false`.

---

## Routine Commerce

### `resq_get_product_routine_ladder`

| Field | Value |
|---|---|
| Purpose | Return the primary routine ladder payload for PDP display |
| Signature | `resq_get_product_routine_ladder( int $product_id ): array` |
| Parameters | `$product_id` — current PDP product ID |
| Return type | `array` — ladder object or empty array |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Yes |
| Data dependencies | `_resq_primary_routine_id`, `_resq_routine_ids`, routine CPT, `resq_core_merchandising['routine_ladder_enabled']` |

**Example return:**

```php
[
    'routine_id'     => 201,
    'title'          => 'Complete Your Daily 3-Step Regimen',
    'description'    => '',
    'steps'          => [ /* from resq_get_routine_steps() */ ],
    'bundle_target'  => 305,
    'bundle_label'   => 'Upgrade to Full Routine Kit',
    'bundle_savings' => '', // computed Phase 8; empty until then
]
```

**Fallback:** `[]` when feature disabled, no routines, or no steps.

**Error handling:** Invalid ID → `[]`.

---

### `resq_get_recommended_routine_addons`

| Field | Value |
|---|---|
| Purpose | Return next-step or complete-kit suggestions for cart drawer |
| Signature | `resq_get_recommended_routine_addons( int $product_id ): array` |
| Parameters | `$product_id` — product just added or in cart |
| Return type | `array[]` — suggestion objects |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Yes |
| Data dependencies | Routine steps, bundle targets, compliance rules, cart drawer feature flag |

**Example return:**

```php
[
    [
        'type'        => 'routine_step',
        'product_id'  => 103,
        'canonical_id'=> 103,
        'title'       => 'Step 3: Night Serum',
        'reason'      => 'complete_routine',
    ],
    [
        'type'        => 'bundle',
        'product_id'  => 305,
        'canonical_id'=> 305,
        'title'       => 'Full Routine Kit',
        'reason'      => 'upgrade_to_kit',
    ],
]
```

**Fallback:** `[]`. Respects `resq_can_cross_sell_products()` for each suggestion.

**Error handling:** Invalid ID → `[]`.

---

### `resq_get_bundle_products`

| Field | Value |
|---|---|
| Purpose | Return products and quantities in a bundle/kit |
| Signature | `resq_get_bundle_products( int $bundle_id ): array` |
| Parameters | `$bundle_id` — WooCommerce bundle product ID |
| Return type | `array[]` — included product objects |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Yes |
| Data dependencies | `_resq_bundle_product_ids` meta; bundle extension meta when adopted |

**Example return:**

```php
[
    [
        'product_id'   => 101,
        'canonical_id' => 101,
        'qty'          => 1,
        'title'        => 'Face & Body Wash',
        'price'        => '39.95', // formatted string from Woo
        'in_stock'     => true,
    ],
    [
        'product_id'   => 102,
        'canonical_id' => 102,
        'qty'          => 1,
        'title'        => 'All-in-One Treatment',
        'price'        => '39.95',
        'in_stock'     => true,
    ],
]
```

**Fallback:** `[]` when not a bundle or no composition defined.

**Error handling:** Invalid ID → `[]`.

---

### `resq_get_frequently_bought_together`

| Field | Value |
|---|---|
| Purpose | Return FBT product IDs for PDP and cart surfaces |
| Signature | `resq_get_frequently_bought_together( int $product_id ): array` |
| Parameters | `$product_id` — WooCommerce product ID |
| Return type | `int[]` — canonical product IDs |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Yes |
| Data dependencies | `_resq_fbt_product_ids` meta; Woo cross-sell/upsell fallback; compliance filters |

**Example return:**

```php
[103, 104]
```

**Fallback:** Filtered Woo native cross-sells when manual override empty. `[]` when none allowed.

**Error handling:** Invalid ID → `[]`. All IDs pass `resq_can_cross_sell_products()`.

---

## Presentation Helpers

### `resq_get_product_card_data`

| Field | Value |
|---|---|
| Purpose | Aggregate scannable product card payload for PLP/gateway shelves |
| Signature | `resq_get_product_card_data( int $product_id ): array` |
| Parameters | `$product_id` — WooCommerce product ID |
| Return type | `array` — card data object |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Yes — title, price, image, URL from Woo |
| Data dependencies | Multiple meta keys, taxonomies, badge helpers |

**Example return:**

```php
[
    'product_id'      => 102,
    'canonical_id'    => 102,
    'title'           => 'All-in-One Treatment',
    'subtitle'        => 'Daily skin comfort support',
    'url'             => 'https://example.com/product/all-in-one-treatment/',
    'image_id'        => 500,
    'image_url'       => 'https://example.com/wp-content/uploads/product.jpg',
    'price_html'      => '<span class="price">$39.95</span>',
    'badges'          => [ /* from resq_get_product_badges() */ ],
    'benefit_tags'    => ['Fragrance-Free', 'Sulfate-Free'],
    'audiences'       => ['human'],
    'concerns'        => ['dry-skin'],
    'compliance_zone' => 'standard',
    'requires_notice' => false,
    'in_stock'        => true,
]
```

**Fallback:** Empty array fields with Woo-native title/price when available; `[]` when product not found.

**Error handling:** Invalid ID → `[]`.

---

### `resq_get_gateway_featured_products`

| Field | Value |
|---|---|
| Purpose | Return product IDs featured on a gateway page shelf |
| Signature | `resq_get_gateway_featured_products( string $gateway ): array` |
| Parameters | `$gateway` — slug: `human`, `pet`, `bundles`, `cbd`, `learn` |
| Return type | `int[]` — canonical product IDs, ordered |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Yes |
| Data dependencies | `_resq_gateway_featured` meta; gateway term/page `_resq_canonical_targets`; audience/compliance filters |

**Example:**

```php
resq_get_gateway_featured_products( 'human' ); // [101, 102, 103]
```

**Fallback:** Query products with matching `_resq_gateway_featured` and audience taxonomy when explicit list empty. `[]` when none.

**Error handling:** Unknown gateway slug → `[]`.

---

### `resq_get_learn_links_for_product`

| Field | Value |
|---|---|
| Purpose | Return Learn guide links related to a product |
| Signature | `resq_get_learn_links_for_product( int $product_id ): array` |
| Parameters | `$product_id` — WooCommerce product ID |
| Return type | `array[]` — link objects |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | No — WP posts |
| Data dependencies | `_resq_learn_links` meta; reverse Learn post mappings |

**Example return:**

```php
[
    [
        'post_id' => 801,
        'title'   => 'How Manuka Honey Supports Skin Comfort',
        'url'     => 'https://example.com/learn/manuka-honey-guide/',
        'label'   => 'Learn about Manuka Honey',
    ],
]
```

**Fallback:** `[]`.

**Error handling:** Invalid product ID → `[]`.

---

### `resq_get_product_badges`

| Field | Value |
|---|---|
| Purpose | Return badge objects for product card and PDP |
| Signature | `resq_get_product_badges( int $product_id ): array` |
| Parameters | `$product_id` — WooCommerce product ID |
| Return type | `array[]` — badge objects sorted by priority |
| Theme-safe | Yes |
| REST later | Yes |
| WooCommerce dependency | Partial — sale/on-sale from Woo |
| Data dependencies | `_resq_badge_label`, `_resq_badge_type`, `resq_core_merchandising['default_badge_config']` |

**Example return:**

```php
[
    [
        'label'    => 'New',
        'type'     => 'new',
        'priority' => 10,
    ],
    [
        'label'    => 'Bundle',
        'type'     => 'bundle',
        'priority' => 20,
    ],
]
```

**Fallback:** Default badge rules from options when no custom badge. `[]` when none apply.

**Error handling:** Invalid ID → `[]`.

---

## Infrastructure Helpers (Phase 1 — retained)

These are documented in `01-THEME-PLUGIN-CONTRACT.md` and implemented in Phase 2B:

| Function | Returns |
|---|---|
| `resq_core()` | Plugin instance |
| `resq_core_get_option( string $key, mixed $default = null )` | mixed |
| `resq_core_feature_enabled( string $feature )` | bool |
| `resq_core_get_badge_data( int $product_id )` | `array\|null` — superseded by `resq_get_product_badges()` for theme use |
| `resq_core_get_cross_sells( int $product_id )` | `int[]` |
| `resq_core_get_compliance_notices( string $context, int $product_id = 0 )` | `array[]` |
| `resq_core_is_active()` | bool |

**Note:** Theme templates should prefer the storefront helpers in this doc. Infrastructure helpers remain for plugin internals and legacy call sites.

---

## Contract Checklist

Before theme templates rely on a helper:

- [ ] Function is listed in this doc with signature and return shape
- [ ] Fallback behavior is empty-safe
- [ ] Schema keys exist in `11-PLUGIN-DATA-SCHEMA.md`
- [ ] `03-WOO-TEMPLATE-MAP.md` names the consuming surface
- [ ] Compliance-sensitive helpers respect `05-COMPLIANCE-RULES.md`

---

## Read Next

1. `13-PHASE-2A-IMPLEMENTATION-NOTES.md` — Phase 2B implementation scope
2. `11-PLUGIN-DATA-SCHEMA.md` — data model details
