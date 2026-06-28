# 24 — Non-Product Landing Pages: Content Map & Build Spec

> Working spec for the gateway / category landing surfaces (Phase 6 shells + presentation layer).
> Consolidates the external content blueprint (Gemini "non-product landing pages") **adapted to
> `docs/05-COMPLIANCE-RULES.md`**. This file is the single source so copy and structure do not drift
> across the multiple pages.

## Status & scope

| Item | Value |
| --- | --- |
| Surfaces in scope | Human gateway, Pet gateway, Bundles gateway, CBD gateway (isolated), Learn index, 404 |
| Category lanes (therapeutic / baby / hair / pet topical) | **In progress (draft, review-gated)** — plugin registry + rewrite routing + `resq-lane.php` template; see § Lane registry below |
| Copy status | **Draft presentation copy** — comfort/cosmetic language only; not owner/legal-approved production copy |
| Architecture | Additive presentation layer over the existing plugin-driven gateway shell (`template-parts/gateway/`). Product truth stays in `resq-core`. |

## Compliance adaptations applied (blueprint → ResQ)

The source blueprint used several phrases that violate `docs/05`. The following substitutions are
**mandatory** anywhere this content is used:

| Blueprint phrase (do NOT ship) | Approved replacement |
| --- | --- |
| "Botanical **Therapeutics**", "Veterinary-Grade", "clinical collections" | "Botanical care", "gentle topical care", "everyday collections" |
| "**eliminate** hot spots", "**stop** razor burn", "**restore cell vibrancy**" | "help soothe the look and feel of hot spots", "support post-shave comfort", "support a healthy-looking skin barrier" |
| "drives nutrient-dense honey **deep into the tissue**", "cellular" | "delivers clean, lasting moisture", (drop cellular/tissue penetration claims) |
| "100% lick-safe" | "lick-safe" (no absolute percentages on safety) |
| "**1:1 Matching Pledge** / identical product donation / immediately donated" | "A portion of every pet-care purchase supports animal shelters and rescue partners" — mission-aligned, no buy-1-give-1 mechanism claim until fulfillment + accounting prove it |
| "diabetic care / for diabetic dogs" | "treats for special dietary routines" |
| "medical-grade Manuka honey" (as efficacy) | OK as **ingredient/sourcing** descriptor only; never paired with disease/symptom cure claims |
| Hardcoded bundle "Save 15% / 10%" | No hardcoded percentages. Bundle savings render from live WooCommerce data via the plugin shelf. |
| CBD "deep cellular calming and joint comfort" | Neutral "everyday balance"; all CBD copy is DRAFT pending compliance review and ships behind isolation + notice + age gate. |

## URL & template map

| Surface | Template | URL | Sections (top → bottom) |
| --- | --- | --- | --- |
| Human gateway | `page-gateway-human.php` → `gateway/page-shell` (`human`) | `/shop/human` | hero-lead → segment-grid (3) → concern-cards* → filter-shell* → product-shelf* |
| Pet gateway | `page-gateway-pet.php` → `gateway/page-shell` (`pet`) | `/shop/pet` | hero-lead → segment-grid (3) → concern-cards* → filter-shell* → product-shelf* → mission-band |
| Bundles gateway | `page-gateway-bundles.php` → `gateway/page-shell` (`bundles`) | `/shop/bundles` | hero-lead → value-band (3 points) → product-shelf* (real bundles) |
| CBD gateway (isolated) | `page-gateway-cbd.php` → `gateway/page-shell` (`cbd`, `is_cbd=true`) | `/shop/human/cbd-wellness`, `/shop/pet/cbd-wellness` | cbd-notice → hero-lead → purity-matrix (3) → filter-shell* → product-shelf* |
| Learn index | `page-learn-index.php` | `/learn` | hero (eyebrow) → guide-grid* → product-bridge* |
| 404 | `404.php` | — | recovery hub (home / human / pet links) |

`*` = existing plugin-driven, empty-safe parts (render only when `resq-core` provides data).

## Content source of truth

All marketing copy + segment/mission/value/purity definitions live in
`wp-content/themes/resq-clean-pro/inc/landing-content.php`, keyed by gateway slug, returned by
`resq_theme_get_landing_content( string $slug ): array`. Templates never hardcode copy; they read
the map. The WP page title/content still render where the existing hero used them (back-compat).

### Map shape

```
[
  'eyebrow'      => string,
  'headline'     => string,
  'subcopy'      => string,
  'primary_cta'  => [ 'label' => string, 'path' => string ],   // path resolved against gateway base
  'segments'     => [ [ 'label','description','image','image_variant','cta_label','path' ], ... ],
  'value_points' => [ [ 'title','body' ], ... ],               // bundles
  'mission'      => [ 'badge','headline','body','cta_label','path' ],  // pet
  'purity'       => [ [ 'title','body' ], ... ],               // cbd (DRAFT)
]
```

## Per-surface copy (approved-for-draft)

### Human gateway (`/shop/human`)
- Eyebrow: `ResQ Organics — Human Collection`
- Headline: `Botanical care for human skin health.`
- Subcopy: `Built around raw Manuka honey and pure aloe vera, our collections work with your skin's natural 5.5 pH balance to soothe the feel of irritation, support lasting moisture, and care for your skin barrier — without synthetic fragrance or fillers.`
- Primary CTA: `Shop all human skincare` → `therapeutic-skin-care/`
- Segments:
  1. **Women's Skincare** — "Everyday care to balance the look of tone, gently clear impurities, and nourish for a smoother, hydrated appearance." → `womens-skincare/` ("The Daily Radiance Routine")
  2. **Men's Grooming** — "Purposeful, stripped-down skincare to support post-shave comfort, hydrate coarse skin, and care for your barrier." → `mens-grooming/` ("Precision Grooming Trio")
  3. **Baby & Infant Care** — "Hyper-gentle, fragrance-free formulas made for delicate skin; patch test and follow label directions." → `baby-infant-care/` ("Pure Comfort Bath")

### Pet gateway (`/shop/pet`)
- Eyebrow: `ResQ Organics — Pet Care`
- Headline: `Gentle topical care for your companions.`
- Subcopy: `Clean, non-toxic comfort for the animals you love. Our lick-safe, fragrance-free topical formulas help soothe the look and feel of hot spots, dry paws, and sensitive skin as part of a gentle care routine.`
- Primary CTA: `Shop all pet care` → `topical-skin-care/`
- Segments:
  1. **Topical Skin Care** — "Gentle relief for the look and feel of raw hot spots, itchy patches, and irritated skin." → `topical-skin-care/` ("Soothed & Calm Companion")
  2. **Coat & Grooming** — "Manuka-infused shampoos and conditioners that leave coats soft, shiny, and tangle-free." → `coat-grooming/` ("Premium Coat Wash")
  3. **Treats & Dietary Care** — "Low-glycemic, pure-ingredient treats made for dogs on special dietary routines." → `treats-diabetic-care/` ("Wholesome Treats")
- Mission band:
  - Badge: `Giving back`
  - Headline: `The ResQ rescue pledge.`
  - Body: `A portion of every pet-care purchase supports animal shelters and rescue groups nationwide — helping rescued animals get the gentle care they need.` (mechanism/ratio claims withheld pending fulfillment + accounting proof)
  - CTA: `See our rescue partners` → Learn

### Bundles gateway (`/shop/bundles`)
- Eyebrow: `Routine kits`
- Headline: `Complete household routines. Built-in savings.`
- Subcopy: `Group corresponding step-by-step products into one routine and save versus buying each on its own. Every bundle's savings reflect live pricing shown at checkout.`
- Primary CTA: `View savings kits` → `#resq-gateway-shelf-heading`
- Value points (no prices — real bundles render in the plugin shelf below):
  1. **Grouped routines** — "Cleanse, treat, and restore steps curated to work together."
  2. **Save versus standalone** — "Kit pricing is lower than buying each product on its own."
  3. **One simple checkout** — "Add a full routine to your cart in a single step."

### CBD gateway (isolated) — DRAFT, review required
- Eyebrow: `Isolated wellness collection`
- Headline: `High-purity botanical balance, isolated for transparency.`
- Subcopy: `A separate, age-gated collection of wellness drops, gummies, and topicals — formulated in a regulated environment and kept fully isolated from our standard storefront.`
- Primary CTA: `View collection` → `#resq-gateway-shelf-heading`
- Purity matrix (neutral, no medical claims):
  1. **Third-party tested** — "Batch Certificates of Analysis available for transparency."
  2. **Low / zero-THC thresholds** — "Verified trace-extraction isolation per published lab results."
  3. **Clean oil carriers** — "Infused into organic coconut and hemp-seed oil bases."
- Hard rule: never linked, cross-sold, or bundled from any standard surface; renders behind isolation styling + compliance notice + age gate.

### 404
- Eyebrow: `Error 404`
- Headline: `We can't find that page.`
- Subcopy: `The link may have moved or no longer exists. Let's get you back on track.`
- Links: Home `/`, Shop for Humans `/shop/human`, Shop for Pets `/shop/pet`

## Image assets

Placeholders use `.resq-img-placeholder` (+ `--dark` / `--pet` variants) with a visible caption =
the asset name above. Swap for `<picture>`/`<img>` at production. All decorative placeholders carry
descriptive `aria-label`s; real images must have meaningful `alt` (`docs/05` accessibility).

## Lane registry & routing (Target Problem Lanes)

Plugin source of truth: `wp-content/plugins/resq-core/includes/routing/data/lanes.php`
(`resq_get_lane_registry()`). Theme copy: `inc/lane-content.php` keyed by `copy_key`.

| URL pattern | Type | Template |
| --- | --- | --- |
| `/shop/{audience}/{category}/` | Category landing | `resq-lane.php` → `lane/page-shell` |
| `/shop/{audience}/{category}/{problem}/` | Target Problem Lane | `resq-lane.php` → hero + matrix + ladder + shelf |

**Status values:** `draft` (shows review banner; not production copy) | `approved`

### Category landings (11 blueprint categories)

| URL | copy_key | status |
| --- | --- | --- |
| `/shop/human/womens-skincare` | `human-womens-skincare` | approved |
| `/shop/human/mens-grooming` | `human-mens-grooming` | approved |
| `/shop/human/therapeutic-skin-care` | `human-therapeutic-skin-care` | draft |
| `/shop/human/hair-scalp-care` | `human-hair-scalp-care` | approved |
| `/shop/human/baby-infant-care` | `human-baby-infant-care` | approved |
| `/shop/human/cbd-wellness` | `human-cbd-wellness` | draft (CBD isolated) |
| `/shop/pet/topical-skin-care` | `pet-topical-skin-care` | approved |
| `/shop/pet/coat-grooming` | `pet-coat-grooming` | approved |
| `/shop/pet/horse-care` | `pet-horse-care` | approved |
| `/shop/pet/treats-diabetic-care` | `pet-treats-diabetic-care` | draft |
| `/shop/pet/cbd-wellness` | `pet-cbd-wellness` | draft (CBD isolated) |

### Problem lanes (full docs/05 set — all draft copy)

| URL | Canonical SKU | copy_key | compliance_zone |
| --- | --- | --- | --- |
| `/shop/human/mens-grooming/razor-burn` | `RQ-HUM-MOISTURIZER` | `human-mens-grooming-razor-burn` | standard |
| `/shop/human/mens-grooming/beard-and-odor-control` | `RQ-HUM-MENSWASH` | `human-mens-grooming-beard-and-odor-control` | standard |
| `/shop/human/womens-skincare/makeup-removal-detox` | `RQ-HUM-WASH` | `human-womens-skincare-makeup-removal-detox` | standard |
| `/shop/human/womens-skincare/exfoliation-texture` | `RQ-HUM-SCRUB` | `human-womens-skincare-exfoliation-texture` | standard |
| `/shop/human/womens-skincare/overnight-repair` | `RQ-HUM-NIGHTSERUM` | `human-womens-skincare-overnight-repair` | standard |
| `/shop/human/baby-infant-care/diaper-rash` | `RQ-BABY-CREAM` | `human-baby-infant-care-diaper-rash` | baby |
| `/shop/human/hair-scalp-care/post-color-irritation` | `RQ-HUM-SHAMPOO` | `human-hair-scalp-care-post-color-irritation` | standard |
| `/shop/human/hair-scalp-care/split-end-defense` | `RQ-HUM-CONDITIONER` | `human-hair-scalp-care-split-end-defense` | standard |
| `/shop/pet/topical-skin-care/hyperkeratosis` | `RQ-PET-SKINCREAM` | `pet-topical-skin-care-hyperkeratosis` | pet-health |
| `/shop/pet/topical-skin-care/feline-dermatitis` | `RQ-PET-SKINCREAM` | `pet-topical-skin-care-feline-dermatitis` | pet-health |
| `/shop/pet/coat-grooming/seasonal-grass-itch` | `RQ-PET-SHAMPOO` | `pet-coat-grooming-seasonal-grass-itch` | pet-health |
| `/shop/pet/coat-grooming/undercoat-friction-static` | `RQ-PET-CONDITIONER` | `pet-coat-grooming-undercoat-friction-static` | pet-health |
| `/shop/pet/horse-care/mud-fever-support` | `RQ-PET-SHAMPOO` | `pet-horse-care-mud-fever-support` | pet-health |

### Expansion matrix lanes (draft stubs — briefs pending per docs/05)

| URL | Canonical SKU | copy_key | compliance_zone |
| --- | --- | --- | --- |
| `/shop/human/therapeutic-skin-care/skin-chafing` | `RQ-HUM-AIOCREAM` | `human-therapeutic-skin-care-skin-chafing` | standard |
| `/shop/human/baby-infant-care/cradle-cap-comfort` | `RQ-HUM-AIOCREAM` | `human-baby-infant-care-cradle-cap-comfort` | baby |
| `/shop/human/mens-grooming/shaving-bumps` | `RQ-HUM-MOISTURIZER` | `human-mens-grooming-shaving-bumps` | standard |
| `/shop/human/hair-scalp-care/excess-sebum-control` | `RQ-HUM-SHAMPOO` | `human-hair-scalp-care-excess-sebum-control` | standard |
| `/shop/pet/topical-skin-care/senior-dog-elbow-calluses` | `RQ-PET-SKINCREAM` | `pet-topical-skin-care-senior-dog-elbow-calluses` | pet-health |
| `/shop/pet/topical-skin-care/skin-fold-dermatitis` | `RQ-PET-SKINCREAM` | `pet-topical-skin-care-skin-fold-dermatitis` | pet-health |
| `/shop/pet/treats-diabetic-care/diabetic-training-rewards` | `RQ-PET-DIABETIC-TREATS` | `pet-treats-diabetic-care-diabetic-training-rewards` | medical-adjacent |
| `/shop/pet/treats-diabetic-care/weight-management-rewards` | `RQ-PET-DIABETIC-TREATS` | `pet-treats-diabetic-care-weight-management-rewards` | medical-adjacent |

Canonical CTAs resolve via `resq_get_canonical_product_id()` — no hardcoded product IDs in templates.
Routine ladder steps pull from plugin routine data (`resq_get_product_routine_ladder()`).

## Build checklist

- [ ] Copy pulled only from `inc/landing-content.php` (no inline copy in templates)
- [ ] No CBD references on human/pet/bundles/learn/404 surfaces
- [ ] No medical/disease/cure claims; comfort language throughout
- [ ] No hardcoded bundle percentages; savings come from Woo
- [ ] Mission copy uses portion-of-purchases language (no buy-1-give-1)
- [ ] Keyboard focus + reduced-motion respected; placeholders have aria-labels
- [ ] Sections are empty-safe (render nothing if map/plugin data absent)
- [ ] Owner/legal sign-off recorded before any of this becomes production copy
