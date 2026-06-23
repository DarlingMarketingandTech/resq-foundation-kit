# 03 — Woo Template Map

> Inventory of WooCommerce and storefront surfaces. Use `woo-template-planner` before adding, moving, or overriding WooCommerce templates.

## Override Root

Theme path: `wp-content/themes/resq-clean-pro/woocommerce/`

WooCommerce template overrides belong in the theme. Plugin work supplies data, helpers, filters, and commerce behavior.

## Surface Inventory

| Surface | Purpose | Data source | Theme responsibility | Plugin responsibility | WooCommerce responsibility | Fallback / empty state | Future implementation notes |
|---|---|---|---|---|---|---|---|
| Global header/nav | Stable route entry to People, Pets, CBD, Bundles, Learn, search, cart | WP menus, plugin route metadata later | Header markup, mega-menu, mobile drawer, active states | Optional route/category metadata and compliance flags | None beyond cart fragments if used | Plain menu with no dynamic labels | Keep nav stable; filters refine inside pages |
| Mega-menu | Expose audience/problem/routine pathways without hiding categories | Menu items, term meta, Learn links | Responsive panels, keyboard/focus behavior | Supply featured audiences/concerns only if enabled | None | Collapse to normal menu links | CBD links visually isolated and not mixed with standard lanes |
| Footer | Trust, policies, Learn, mission, compliance notices | WP menus, plugin compliance notices | Footer layout, notice slot, policy link presentation | Notice rules/content source | Account/cart policy links if native | Static footer links only | Donation/mission copy requires proof review |
| Shop archive | Main catalog index | Woo product query, plugin sort/filter rules | `archive-product.php`, toolbar, grid, empty state | Query filters, default sort, route context | Product loop, result count, ordering, pagination | Native Woo loop or no-products state | Do not hardcode planning categories as final taxonomy |
| Audience gateway: Human | Entry page for human routines and concerns | WP page/term content, plugin audience mappings | Gateway hero, cards, product shelves, Learn bridges | Audience/concern/routine/product mappings | Product queries/cards | Editorial page without product shelves | Blueprint examples inform structure only |
| Audience gateway: Pet | Entry page for pet care concerns and routines | WP page/term content, plugin audience mappings | Gateway hero, concern cards, pet-safe copy display | Audience/concern/routine/product mappings | Product queries/cards | Editorial page without product shelves | Pet health copy must stay cautious |
| CBD gateway/category | Isolated CBD shopping area | Woo category/term meta, compliance flags | Isolated layout, disclaimers, no mixed cards | CBD flags, notice requirements, cross-sell restrictions | Product loop/cart/checkout | Empty category or notice-only page | No CBD products in standard cross-sell zones by default |
| Bundles & savings landing | Explain kits/routines and value | Woo products, bundle metadata | Bundle cards, savings display, anchors, empty states | Bundle composition, savings data, validation | Product/bundle product records | Hide bundle sections with no products | Decide extension vs plugin rules before implementation |
| Category archive | Term-specific PLP | Woo term query, term meta, plugin mappings | Category header, filters, grid, breadcrumbs | Term meta, route-to-canonical mappings | Product loop/pagination | Native archive header + no-products state | Filters should refine, not replace navigation |
| Concern landing page | Problem-led discovery route | WP page or taxonomy term + product mappings | Landing sections, concern cards, product shelves | Concern mappings and canonical target resolution | Product card data | Editorial page with CTA to shop | Avoid medical/veterinary cure language |
| Learn index | Education hub | WP posts/pages | Learn card grid, topic navigation | Optional ingredient/product bridge metadata | None | Standard blog/archive list | Learn content may bridge to products but not create duplicates |
| Learn-to-shop guide | Editorial guide pointing to products/routines | WP content, plugin canonical mappings | Guide layout, product callouts, CTA modules | Resolve canonical products and allowed claims/notices | Add-to-cart/PDP links | Article renders without product modules | Do not duplicate product records for SEO variants |
| Product search | Search across product and Learn content | WP/Woo search query | `search.php`, product-first grid, content result cards | Optional relevance weighting | Product results | Search-empty recommendations | CBD query results may need isolated grouping |
| Product filters | Refine current collection | Woo attributes, plugin audience/concern/routine taxonomies | Filter UI, applied chips, mobile drawer | Register filterable data and query filters | Attribute filtering/queries | Hide unavailable filters | Filters are not primary nav replacements |
| Product card | Reusable PLP/shelf card | Woo product + plugin helpers | `woocommerce/content-product.php`, badges, CTA, routine/bundle hints | Badge, audience/concern/routine/CBD helper data | Image/title/price/rating/add-to-cart | Minimal Woo card without plugin data | Cards must not display risky claims from examples |
| Single product page (PDP) | Canonical buyable product page | Woo product, plugin helpers | `single-product.php`, gallery, summary, tabs, routine/bundle/notice slots | Routine steps, ingredient profiles, FBT, compliance, canonical mapping | Product data, variations, add-to-cart, tabs | Native PDP with empty custom slots | Canonical parent is the source for price/stock |
| PDP routine ladder | Show current product inside a routine | `resq_get_product_routines()`, `resq_get_routine_steps()` | Step checklist UI and upgrade CTA | Routine definitions, step order, bundle target | Add-to-cart/cart actions | Hide if no routine data | Use for clarity, not pressure or false savings |
| PDP ingredient profile | Claim-safe ingredient explanation | `resq_get_product_ingredient_profile()` | Ingredient cards/accordion | Ingredient descriptors and risk flags | Product attributes if used | Hide or show native attributes | Avoid adding unsupported claims in theme copy |
| Frequently bought together | Suggest complementary products | `resq_get_frequently_bought_together()` | FBT template part and add UI | FBT product IDs and restrictions | Cart/add-to-cart | Hide with no IDs | Must respect CBD isolation |
| Related products | Native related section | Woo related products, plugin filters | Style/render related block | Filter count/exclusions/restrictions | Related products query | Native related output | Phase 4 may keep hook-only strategy |
| Bundle product PDP | Sell curated kit/bundle | Woo bundle product + plugin bundle data | Bundle layout, included item list, savings UI | Bundle composition, validation, restrictions | Product type/add-to-cart/cart line item | Standard product PDP | Depends on bundle extension decision |
| Cart drawer | Keep shopping context after add-to-cart | Woo cart/session, plugin suggestions | Drawer UI, focus trap, item summary, suggestion card | Routine/FBT/cart suggestion rules | Cart fragments/session | Redirect/standard cart if disabled | Suggestions must not block checkout |
| Cart page | Review cart and cross-sells | Woo cart, plugin notices/suggestions | `cart/cart.php`, totals layout, empty state, notice slots | Cart notices, restrictions, suggestion IDs | Cart items, totals, coupons, cross-sells | Native cart/empty cart | Cart should remain functional without plugin |
| Checkout | Complete purchase safely | Woo checkout, plugin compliance/validation | `checkout/form-checkout.php`, notice placement, layout | Checkout fields/validation/notices/compliance gates | Payment, taxes, order creation | Native checkout | Do not override gateway iframe internals. Open decision: whether an "isolated checkout mode" hides header/footer/category links during checkout (see source blueprint concept). |
| Thank-you page | Order confirmation | Woo order | `checkout/thankyou.php` layout | Optional mission/next-step data | Order summary | Native thank-you | Avoid unverified donation claims |
| My Account | Customer account management | Woo account endpoints | Account layout/navigation templates | Custom endpoints if later needed | Login, orders, addresses, account forms | Native account templates | Account logic stays Woo/plugin |
| Compliance notices | Product/category/cart/checkout notices | Plugin flags and notice source | Notice markup, placement, accessibility | Rule selection and copy source | None | Hidden when not required | CBD, baby, pet, proof, donation risk flags |
| Emails | Transactional messages | Woo order/email data | Optional email shell styling | Email content hooks and data | Email sending/templates | Native Woo emails | Avoid major email overrides until needed |

## Planned Template and Part Paths

Phase 8 template parts marked ✓ are implemented. All others are shells or planned.

| Surface | Theme path | Status |
|---|---|---|
| Shop/category archive | `archive-product.php` | Phase 5 shell |
| Product card | `woocommerce/content-product.php` | Phase 5 shell |
| Single product | `single-product.php`, `woocommerce/content-single-product.php` | Phase 5 shell |
| PDP gallery/add-to-cart/tabs | `woocommerce/single-product/*` | Phase 5 shell |
| Routine ladder | `template-parts/product/routine-ladder.php` | Phase 6 shell; Phase 8 bundle savings ✓ |
| Ingredient profile | `template-parts/product/ingredient-profile.php` | Phase 8 ✓ |
| FBT | `template-parts/product/frequently-bought-together.php` | Phase 8 ✓ |
| Bundle card | `template-parts/product/bundle-card.php` | Optional — not yet created |
| Bundle PDP block | `template-parts/product/bundle-options.php` | Phase 8 ✓ |
| Audience gateway | `template-parts/archive/audience-gateway.php` or page template | Phase 6 shell |
| Concern landing | `template-parts/archive/concern-landing.php` or page template | Phase 6 shell |
| Cart drawer | `template-parts/cart/drawer.php` | Phase 8 ✓ |
| Filter shell | `template-parts/gateway/filter-shell.php` | Phase 8 ✓ — live taxonomy checkboxes |
| Compliance notices | `template-parts/compliance/notices.php` | Phase 5/6 shell |
| Learn guide bridge | `template-parts/learn/product-bridge.php` | Phase 6 shell |
| Cart | `woocommerce/cart/cart.php`, `cart-empty.php`, `cart-totals.php`, `cross-sells.php` | Phase 5 shell |
| Checkout | `woocommerce/checkout/form-checkout.php`, `review-order.php`, `payment.php`, `thankyou.php` | Phase 5 shell |
| Account | `woocommerce/myaccount/*.php` | Phase 5 shell |
| Search | `search.php`, optional `woocommerce/product-searchform.php` | Phase 5 shell |

## Hooks vs Templates Decision Guide

| Behavior | Prefer | Notes |
|---|---|---|
| Layout/HTML structure | Template override | Keep logic minimal |
| Route/category/product mapping | Plugin helper | Theme only consumes resolved data |
| Price, stock, tax, add-to-cart | WooCommerce | Plugin filters only when documented |
| Routine, bundle, FBT data | Plugin helper | Theme renders empty-safe parts |
| Compliance decisions | Plugin helper | Theme displays accessible notice slots |
| Payment gateway internals | Woo/gateway | Theme styles wrappers only |
| Product query filtering | Plugin hook | Document changes in merchandising doc |

## Verification Checklist

- [ ] Surface appears in the inventory table.
- [ ] Theme path or hook strategy is named.
- [ ] Plugin data/helper responsibility is named.
- [ ] WooCommerce responsibility is not replaced by theme/plugin code.
- [ ] Empty/plugin-inactive fallback avoids PHP notices and broken markup.
- [ ] CBD/compliance-sensitive surfaces link back to `05-COMPLIANCE-RULES.md`.
- [ ] Source-blueprint examples were not hardcoded as final catalog truth.

## Phase Alignment

| Phase | Template map work |
|---|---|
| Phase 1 | Inventory and boundaries locked |
| Phase 4 | Global layout and navigation shell |
| Phase 5 | Woo template shells |
| Phase 6 | Gateway, Learn, and routine UI shells |
| Phase 8 | Bundle/FBT/cart drawer implementation — **complete**; delivery record [`20-PHASE-8-IMPLEMENTATION-NOTES.md`](20-PHASE-8-IMPLEMENTATION-NOTES.md) |
| Phase 9 | Fresh-install smoke across all surfaces — active; runbook [`21-PHASE-9-IMPLEMENTATION-NOTES.md`](21-PHASE-9-IMPLEMENTATION-NOTES.md) |
| Phase 10 | Compliance and accessibility verification |
