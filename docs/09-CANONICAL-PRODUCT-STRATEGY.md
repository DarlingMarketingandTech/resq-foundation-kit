# 09 — Canonical Product Strategy

> Prevent duplicate products by separating front-end marketing routes from the buyable WooCommerce product source of truth.

## Single Parent Rule

If the formula, inventory, fulfillment, and base product identity are the same, use one canonical WooCommerce product record. Use variations for legitimate selectable differences such as size, scent, count, format, or other Woo-supported attributes.

Do not create duplicate products only because:

- The shopper is human vs pet.
- A page targets a different concern.
- A Learn article needs a product CTA.
- A category page has a different SEO angle.
- A bundle page wants different copy.
- A source blueprint names a specific use case.

## Variation Strategy

Use variations when the shopper is choosing a concrete product option:

- Size or volume.
- Pack count.
- Scent/fragrance-free option.
- Format when the product remains the same family.

Do not use variations for unrelated products or for compliance-sensitive segmentation that should be handled by flags, routes, or categories.

## Duplicate Avoidance

| Duplicate pressure | Correct response |
|---|---|
| "Dog hot spot cream" and "cat hot spot cream" use the same product | One canonical product with species/concern mappings |
| Learn article wants tailored CTA | Learn bridge maps to canonical product |
| Category page needs custom copy | Category/landing copy maps to canonical product |
| Bundle includes product | Bundle composition points to canonical product |
| Product appears in multiple routines | Routine mappings point to same canonical product |

## Landing Page Mapping

Landing pages own context, not inventory.

| Page type | Maps through | Canonical target |
|---|---|---|
| Audience gateway | Audience mapping | Product/category/routine |
| Concern landing | Concern mapping | Product/routine |
| Bundle landing | Bundle relationship | Bundle product or included products |
| CBD gateway | CBD flag/category | CBD products only |
| Learn guide | Canonical resolver | Product/routine/bundle |

## Category Page Mapping

Categories can organize browsing, but they should not multiply product records. A product can belong to more than one category if it is genuinely relevant, while still keeping one product ID for price, inventory, reviews, and cart behavior.

## Filters

Filters refine within a path:

- Audience.
- Concern/problem.
- Routine.
- Ingredient.
- Size/format.
- Species, when relevant.
- Price.
- Availability.

Filters should not become hidden duplicate categories or product copies.

## Canonical Resolver

The plugin should eventually expose:

```php
resq_get_canonical_product_id( int|string $source, string $source_type = 'product' );
```

Possible `source_type` values:

- `product`
- `variation`
- `term`
- `page`
- `route`
- `routine`
- `bundle`
- `learn`

### Fallback Order

When multiple `source_type` values could resolve a canonical product, the resolver should attempt them in this priority:

1. `product` — direct WooCommerce product ID
2. `variation` — variation parent
3. `bundle` — bundle product target
4. `routine` — routine-to-product mapping
5. `term` — taxonomy term canonical target
6. `page` — WP page canonical target
7. `route` — front-end route mapping
8. `learn` — Learn guide bridge

A safe default is `null` when no mapping exists, allowing the theme to hide product bridges.

## Why This Protects the Business

| Area | Benefit |
|---|---|
| Inventory | One stock record prevents oversell/undersell errors |
| SEO | Canonical PDP reduces duplicate content and split authority |
| Margin | Bundle/savings math is centralized and auditable |
| Operations | Fulfillment, reporting, and reviews stay tied to one product |
| Compliance | CBD and claim-sensitive products can be flagged once and respected everywhere |
| Analytics | Product performance is not fragmented across duplicate records |
| Maintenance | Landing pages can change without touching product data |

## Anti-patterns

- Creating a new product for every concern route.
- Copying product descriptions into landing pages with manual prices.
- Hardcoding add-to-cart IDs in theme templates.
- Creating separate CBD/non-CBD mixed bundles without documented compliance review.
- Letting source-blueprint examples become final SKU architecture.
