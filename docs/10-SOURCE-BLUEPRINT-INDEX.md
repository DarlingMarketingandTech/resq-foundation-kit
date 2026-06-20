# 10 — Source Blueprint Index

> Index of preserved source strategy files. Do not rewrite these source blueprints in place; use this index and the architecture docs to absorb decisions.

## Source Files

| File | Purpose | Key ideas | Informs docs | Open questions / decisions |
|---|---|---|---|---|
| `docs/source-blueprints/storefront-strategy-taxonomy-blueprint.md` | Broad storefront strategy and taxonomy blueprint | Stable global nav; audience/problem paths; Single Parent Rule; decoupled front-end content; routine ladder; CBD isolation; cart drawer; isolated checkout | `00`, `01`, `03`, `04`, `05`, `07`, `08`, `09` | Final route slugs; final taxonomy terms; legal copy; bundle engine; cart drawer implementation |
| `docs/source-blueprints/shop-human-pet-category-landing-blueprint.md` | Human and pet gateway page structure and copy examples | Human gateway hero; ingredient authority module; routine cards; pet symptom-led grid; proof/mission sections | `03`, `04`, `05`, `07`, `08` | Which copy is compliant; proof substantiation; donation claim policy; final human/pet categories |
| `docs/source-blueprints/bundles-savings-landing-blueprint.md` | Bundles & Savings landing page blueprint | Bundle hero; human routine bundles; pet kits; savings display; single-cart promise; mission tie-in | `03`, `04`, `05`, `07`, `08`, `09` | Bundle product type/extension; savings calculation source; donation claim substantiation; final bundle SKUs |

## How to Use These Blueprints

Use the blueprints for:

- Structural patterns.
- Strategic intent.
- Fixture inspiration.
- Open decision discovery.
- Copy-risk review prompts.

Do not use the blueprints as:

- Final product catalog truth.
- Approved legal, medical, veterinary, CBD, or donation copy.
- Final URL taxonomy.
- Final prices, bundle savings, or SKU definitions.
- A reason to duplicate canonical products.

## Traceability Notes

- `storefront-strategy-taxonomy-blueprint.md` is the main source for routine-commerce strategy, canonical parent products, CBD isolation, and cart drawer direction.
- `shop-human-pet-category-landing-blueprint.md` is the main source for audience gateway page concepts.
- `bundles-savings-landing-blueprint.md` is the main source for bundle/card/routine kit page concepts.

## Decisions Captured in Architecture Docs

- Routine-commerce is the north star.
- WooCommerce remains the immediate implementation target.
- Platform concepts stay portable enough for later WooCommerce/Medusa/Shopify comparison.
- Plugin owns the data model and helper contracts.
- Theme owns presentation and empty-safe render slots.
- CBD isolation is required across navigation, recommendations, cart, and checkout.
- Canonical products protect inventory, SEO, margin, and operations.

## Remaining Open Questions

- What are the final audience, concern, routine, and ingredient taxonomies?
- Which bundle engine will be used?
- What claims are substantiated for production copy?
- What donation/mission claims are operationally provable?
- How should CBD age/compliance requirements vary by jurisdiction?
- Which Learn content types ship in the first fixture set?
