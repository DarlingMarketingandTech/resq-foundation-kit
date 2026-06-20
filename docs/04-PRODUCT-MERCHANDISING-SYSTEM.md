# 04 — Product Merchandising System

> How products are presented, badged, cross-sold, and sorted — split between plugin data and theme display.

## Product data model (plugin)

| Field / feature | Storage | Admin UI | Front-end consumer |
|---|---|---|---|
| Badge (e.g. New, Sale) | _TBD_ | _TBD_ | theme card component |
| Featured collection | _TBD_ | _TBD_ | homepage module |
| Cross-sell rules | _TBD_ | _TBD_ | PDP block |
| PLP default sort | Woo + filters | _TBD_ | archive template |

## PLP (product listing)

- Grid columns: _TBD_
- Card elements: image, title, price, badge, quick view (if any)
- Filters: attribute vs category — _TBD_
- Empty state copy: _TBD_

## PDP (product detail)

- Gallery behavior: _TBD_
- Trust signals: reviews, shipping, returns — placement _TBD_
- Upsell / cross-sell blocks: _TBD_
- Sticky add-to-cart on mobile: yes/no _TBD_

## Homepage merchandising zones

| Zone | Content source | Template part |
|---|---|---|
| Hero | _TBD_ | _TBD_ |
| Featured products | _TBD_ | _TBD_ |
| Category tiles | _TBD_ | _TBD_ |
| Social proof | _TBD_ | _TBD_ |

## Agent notes

- Use **woo-merchandiser** agent for catalog/display planning
- Use **ecommerce-taste-review** before approving visual merchandising PRs
- Store mutations (products, categories) only on staging with explicit approval
