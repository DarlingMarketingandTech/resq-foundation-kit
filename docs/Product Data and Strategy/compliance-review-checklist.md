# ResQ Catalog — Compliance Review Checklist

> Owner sign-off before public launch. All flagged SKUs are **published** in the catalog import with compliance zones and `_resq_compliance_flags`; storefront notices and CBD isolation apply automatically.

**Import command:** `wp resq-catalog import`  
**Plugin version:** `0.5.0`  
**Generated from:** strategy CSVs + `includes/catalog/data/*.php`

---

## Sign-off legend

| Status | Meaning |
| --- | --- |
| ☐ | Pending owner review |
| ☑ | Approved for launch |

---

## 1. CBD zone (`zone: cbd`, flag: `cbd`)

| SKU | Product | Final check needed (from strategy) | Owner sign-off |
| --- | --- | --- | --- |
| `RQ-PCBD-OIL` (+ 300/500/1000mg vars) | Pet CBD Hemp Oil | CBD compliance, lab testing, and allowed claims must be reviewed. | ☐ |
| `RQ-PCBD-TREATS` (+ 2mg/5mg vars) | CBD-Infused Organic Dog Treats | Confirm count per bag and CBD mg per treat. | ☐ |
| `RQ-HCBD-OIL` (+ 300/500/1000/1500mg vars) | Full Spectrum CBD Oil | CBD compliance, COAs, and state rules need review. | ☐ |
| `RQ-HCBD-GUMMIES` | CBD Gummies | Confirm count, mg per gummy, and cannabinoid profile. | ☐ |
| `RQ-HCBD-SLEEPGUMMIES` | Sleep Gummies — 10mg CBN & 25mg CBD | Avoid medical sleep claims; confirm count and mg. | ☐ |
| `RQ-HCBD-RUB` | CBD Intensive Relief Rub — 2000mg | Confirm mg, size, and topical compliance language. | ☐ |
| `RQ-HCBD-BATHBOMB` (+ scent vars) | CBD Bath Bomb | Confirm scent list and whether CBD mg varies. | ☐ |
| `RQ-HCBD-SOFTGELS` | Weight Control Softgels — CBD & THCv | **Highest claim-risk SKU**; legal/regulatory review needed. | ☐ |
| `RQ-KIT-PCBD-CALM-500` | Canine Calm Starter Kit — 500mg | Bundle inherits CBD compliance; confirm component COAs. | ☐ |
| `RQ-KIT-PCBD-CALM-1000` | Canine Calm Starter Kit — 1000mg | Same as above. | ☐ |
| `RQ-KIT-PET-SENIOR` | Senior Dog Comfort Bundle | Contains `RQ-PCBD-OIL-500MG`; CBD + pet-health review. | ☐ |
| `RQ-KIT-HCBD-NIGHT` | CBD Night Routine Kit | Sleep/CBN claim review. | ☐ |
| `RQ-KIT-HCBD-RELIEF` | CBD Relief Routine Kit | Topical + oral CBD claim review. | ☐ |
| `RQ-KIT-HCBD-DAILY` | CBD Daily Wellness Bundle | Full spectrum + gummies claim review. | ☐ |
| `RQ-PK-HCBD-BATH-3` | CBD Bath Bomb 3-Pack | Confirm mg per bomb in multipack copy. | ☐ |
| `RQ-PK-HCBD-BATH-5` | CBD Bath Bomb 5-Pack | Same as above. | ☐ |
| `RQ-PK-HCBD-GUMMIES-2` | CBD Gummies 2-Pack | Confirm count and mg per pack. | ☐ |
| `RQ-PK-HCBD-GUMMIES-3` | CBD Gummies 3-Pack | Same as above. | ☐ |

---

## 2. Baby zone (`zone: baby`, flag: `baby`)

| SKU | Product | Final check needed | Owner sign-off |
| --- | --- | --- | --- |
| `RQ-BABY-WASH` | Gentle Baby Face & Body Wash | Confirm bottle size and baby-safe claims. | ☐ |
| `RQ-BABY-CREAM` (+ 2oz/4oz/8oz vars) | Soothing Baby Diaper Rash + Skin Care Cream | Confirm whether zinc oxide, active ingredients, or cosmetics/drug rules apply. | ☐ |
| `RQ-DUO-BABY-BATH` | Baby Bath + Skin Care Duo | Bundle inherits baby compliance. | ☐ |

---

## 3. Pet health zone (`zone: pet-health`, flag: `pet-health`)

| SKU | Product | Final check needed | Owner sign-off |
| --- | --- | --- | --- |
| `RQ-PET-SKINCREAM` (+ vars) | Pet Skin Treatment Cream | Confirm exact jar sizes and landed margin. | ☐ |
| `RQ-PET-HORSECREAM` (+ vars) | Horse Skin Care Treatment Cream | Confirm whether formula/label differs from pet skin treatment. | ☐ |
| `RQ-PET-SHAMPOO` | All-Natural Manuka Honey Pet Shampoo | Confirm bottle size; price per ounce on PDP. | ☐ |
| `RQ-PET-CONDITIONER` | Manuka Honey Pet Conditioner | Confirm bottle size and COGS/margin vs shampoo. | ☐ |
| `RQ-KIT-PET-HOTSPOT` | Pet Hot Spot Rescue Kit | Pet-health topical bundle. | ☐ |
| `RQ-DUO-PET-COAT` | Pet Coat Care Duo | Confirm bottle sizes in duo copy. | ☐ |
| `RQ-KIT-HORSE-SKIN` | Horse Skin & Coat Care Kit | Horse-specific label/claim review. | ☐ |

---

## 4. Medical-adjacent flag (`medical-adjacent`)

| SKU | Product | Final check needed | Owner sign-off |
| --- | --- | --- | --- |
| `RQ-PET-DIABETIC-TREATS` | Diabetic Dog Treats | Confirm bag size, ingredients, and allowed diabetic/low-glycemic wording. | ☐ |
| `RQ-HCBD-SOFTGELS` | Weight Control Softgels — CBD & THCv | Do not lead with weight-loss claims until compliance review. | ☐ |
| `RQ-PK-PET-DIABETIC-2` | Diabetic Dog Treats 2-Pack | Inherits diabetic treat claim review. | ☐ |
| `RQ-PK-PET-DIABETIC-3` | Diabetic Dog Treats 3-Pack | Same as above. | ☐ |

---

## 5. Ingredient assignments marked "confirm"

These ingredient terms are seeded and assigned in catalog data but should be validated against actual formulas:

| Ingredient term | Assigned to (sample SKUs) | Owner sign-off |
| --- | --- | --- |
| `aloe-vera` | `RQ-HUM-WASH`, `RQ-BABY-WASH`, `RQ-BABY-CREAM`, `RQ-HCBD-RUB` | ☐ |
| `coconut-oil` | `RQ-PET-CONDITIONER`, men's lines | ☐ |
| `vitamin-e` | `RQ-HUM-NIGHTSERUM` | ☐ |
| `manuka-honey` | Most human/pet topicals (broad) | ☐ Confirm UMF/MGO on `RQ-HUM-MANUKAHONEY` |

**Manuka Honey anchor:** `RQ-HUM-MANUKAHONEY` — Confirm UMF/MGO certification and pack size.

---

## 6. Pricing / claim notes — all catalog families

Imported at recommended strategy prices. Owner should confirm COGS/margin before launch promotions.

| Product family | SKU prefix | Final check needed |
| --- | --- | --- |
| All-in-One Cream | `RQ-HUM-AIOCREAM` | Confirm package sizes and margin. |
| Age-Defying Night Serum | `RQ-HUM-NIGHTSERUM` | Confirm bottle size and active ingredient claims. |
| Women's Wash | `RQ-HUM-WASH` | Confirm bottle size. |
| Women's Scrub | `RQ-HUM-SCRUB` | Confirm jar size and usage frequency. |
| Men's Moisturizer | `RQ-HUM-MOISTURIZER` | Confirm packaging vs women's moisturizer. |
| Men's Wash+Shave | `RQ-HUM-MENSWASH` | Confirm bottle size. |
| Men's Serum | `RQ-HUM-MENSSERUM` | Confirm formula equivalency and size. |
| Men's Scrub | `RQ-HUM-MENSSCRUB` | Confirm shaving/ingrown-hair claim language. |
| Men's Shampoo / Conditioner | `RQ-HUM-MENSHAMPOO`, `RQ-HUM-MENSCOND` | Confirm bottle size and claim language. |
| Manuka Shampoo / Conditioner | `RQ-HUM-SHAMPOO`, `RQ-HUM-CONDITIONER` | Confirm unisex vs women's line; bottle size. |
| UMF Manuka Honey | `RQ-HUM-MANUKAHONEY` | Confirm UMF/MGO certification and pack size. |
| Pet CBD Oil | `RQ-PCBD-OIL` | CBD compliance (see §1). |
| Human CBD Oil | `RQ-HCBD-OIL` | CBD compliance (see §1). |
| CBD Gummies / Sleep Gummies | `RQ-HCBD-GUMMIES`, `RQ-HCBD-SLEEPGUMMIES` | Count and mg per serving. |
| CBD Rub | `RQ-HCBD-RUB` | mg, size, topical compliance. |
| CBD Bath Bombs | `RQ-HCBD-BATHBOMB` | Scent list and mg consistency. |
| Weight Control Softgels | `RQ-HCBD-SOFTGELS` | Highest claim-risk; regulatory review. |
| All bundles (`RQ-KIT-*`, `RQ-DUO-*`, `RQ-PK-*`) | 21 SKUs | Confirm bundle discount vs component subtotal; CBD bundles need §1 sign-off. |

---

## 7. Storefront behavior verification

After import, confirm in browser:

- [ ] CBD products isolated on CBD gateway (not mixed with standard shop)
- [ ] Baby products show baby compliance notice
- [ ] Pet-health and medical-adjacent products show appropriate notices
- [ ] Variable PDP shows size/strength/scent selector + routine ladder
- [ ] Bundle PDP shows `_resq_bundle_product_ids` composition

---

## 8. Non-blocking follow-ups (Phase 8+)

- Subscribe-and-save and bulk quantity breaks are pricing strategy only — no Woo Subscriptions engine yet.
- Product images not included in catalog import — add via media library or Phase 8 merchandising pass.
- Demo fixtures (`fixture-*` SKUs) can be removed with `wp resq-fixtures reset --yes` after catalog validation.
