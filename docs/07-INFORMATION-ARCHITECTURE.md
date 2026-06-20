# 07 — Information Architecture

> Navigation, URL, content type, and route strategy for the routine-commerce storefront.

## Global Navigation

Global navigation should remain stable and predictable:

- **Shop For Humans**
- **Shop For Pets**
- **Bundles & Savings**
- **CBD / Wellness** or CBD subpaths, isolated from standard lanes
- **Learn**
- Search
- Cart/account links

Blueprint labels such as `/shop/human`, `/shop/pet`, and `/shop/bundles` are useful examples. Final route slugs remain an implementation decision.

## Primary Shopping Paths

| Path type | Purpose | Product truth |
|---|---|---|
| Audience path | Start by who the shopper is buying for | Plugin audience mapping |
| Concern/problem path | Start by discomfort, use case, or routine need | Plugin concern mapping |
| Routine path | Show ordered regimen or kit | Plugin routine/step data |
| Bundle path | Sell curated kit or replenishment pack | Plugin bundle data + Woo product |
| Learn path | Educate, then bridge to canonical products | Plugin canonical mapping |
| Search/filter path | Refine available products | Woo query + plugin filters |

## Human Path

Human paths may include skincare, grooming, hair/scalp, baby/infant, and CBD examples. The architecture supports these as front-end content routes and product filters, not as proof that final Woo categories or products exist.

## Pet Path

Pet paths may include topical care, coat/grooming, treats, and pet CBD examples. Pet health copy must stay cautious and should not imply veterinary treatment.

## Bundle Path

Bundle paths group products by routine, outcome, replenishment, or value. Bundles must still resolve to real Woo products or a documented bundle engine.

## Learn Path

Learn pages explain ingredients, routines, application, mission, or safety. They can point to products through plugin mappings, but they do not own product data.

## CBD Isolation

CBD routes must be clear and isolated:

- Dedicated category/gateway paths.
- CBD notice slots on category, PDP, cart, and checkout.
- No automatic mixing with standard product cards, FBT, related products, or cart drawer suggestions.
- No hidden CBD markup inside standard pages.

## URL Pattern Candidates

| Pattern | Use | Notes |
|---|---|---|
| `/shop/` | Main catalog | Woo shop archive |
| `/shop/human/` | Human gateway | Could be WP page or product category |
| `/shop/pet/` | Pet gateway | Could be WP page or product category |
| `/shop/bundles/` | Bundle landing | Could be WP page with product shelves |
| `/shop/cbd/` | CBD isolated gateway | Requires compliance flags |
| `/shop/{audience}/{concern}/` | Concern landing | Route maps to canonical products |
| `/learn/` | Learn index | WP posts/pages |
| `/learn/{topic}/` | Learn guide | Bridges to canonical products |
| `/product/{slug}/` | Canonical PDP | Woo product permalink |

## Content Types

| Content type | Owner | Purpose |
|---|---|---|
| Woo product | WooCommerce | Buyable product, variation, stock, price |
| Product category/tag/attribute | Woo/plugin | Product grouping and filters |
| Audience/concern/routine metadata | Plugin | Storefront discovery model |
| Gateway page | Theme display + WP content | Audience/bundle/CBD entry |
| Concern landing | Theme display + plugin mappings | Problem-led discovery |
| Learn guide | WP content + plugin mappings | Education and product bridge |
| Bundle/kit product | Woo + plugin | Buyable grouped routine/value product |

## Landing Page Types

- Audience gateway
- Concern/problem landing
- Bundle/savings landing
- CBD isolated gateway
- Ingredient education page
- Routine education page
- Mission/support page
- Search results page

## Filters vs Navigation

Navigation should answer "Where am I shopping?" Filters should answer "What do I want to narrow within this path?"

Use filters for species, size, routine step, ingredient, concern, price, format, and availability. Do not create separate nav branches for every filter combination.

## Canonical vs Front-End Route

Front-end routes can be many. Canonical products should be few.

| Front-end route | Canonical target |
|---|---|
| Audience gateway card | Product category, concern route, or canonical product |
| Concern landing CTA | Canonical product or routine |
| Learn guide product callout | Canonical product |
| Routine ladder step | Canonical product |
| Bundle card | Bundle product or kit target |

The plugin resolves targets. The theme renders links and cards.
