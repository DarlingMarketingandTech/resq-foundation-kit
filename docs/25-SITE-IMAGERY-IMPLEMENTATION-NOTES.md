# Site Imagery — Implementation Notes (0.6.0)

Delivery record for wiring Media Library photography into catalog products and marketing surfaces. Images live in `wp-content/uploads/2026/06/` with slug filenames that mirror Cloudinary display names.

## Architecture

| Layer | Responsibility |
| --- | --- |
| Plugin | `resq_core_get_attachment_id_by_slug()` — cached slug → attachment ID |
| Plugin | `resq_catalog_get_product_image_map()` / `resq_catalog_get_bundle_image_map()` — SKU → `image` + `gallery` slugs |
| Plugin | `import_product()` — resolves slugs to `set_image_id()` / `set_gallery_image_ids()` (skips missing silently) |
| Theme | `resq_theme_render_image()` — slug render with `.resq-img-placeholder` fallback |
| Theme | Content maps (`landing-content.php`, `lane-content.php`, home partials) — `image_slug` keys |

Product card `image_url` populates automatically once Woo featured images are set; no change to `resq_get_product_card_data()`.

## Apply catalog images

From LocalWP **Open site shell** (WordPress root):

```bat
wp resq-catalog import --reset
wp eval "echo get_post_thumbnail_id(wc_get_product_id_by_sku('RQ-HUM-AIOCREAM'));"
wp eval "echo get_post_thumbnail_id(wc_get_product_id_by_sku('RQ-PET-SKINCREAM'));"
```

Expect non-zero attachment IDs when matching uploads exist.

## Asset gaps (placeholder fallback)

Decision: **do not sideload** missing Cloudinary assets in this slice. Importer skips unresolved slugs; theme slots fall back to gradient placeholders.

| SKU / slot | Notes |
| --- | --- |
| `RQ-HUM-MANUKAHONEY` | Uses `ingredients-cream-honey-aloe-triptych` (no dedicated UMF jar shot in library) |
| `RQ-HUM-MENSCOND` / men's scrub | Uses women's duo bundle / `mens-skin-care-cream-8oz` (no men's conditioner or scrub isolate in library) |
| WordPress dedupe suffixes | Some uploads resolve as `-2` slugs (e.g. `full-spectrum-cbd-oil-2`, `diabetic-dog-treats-2`); maps use actual `post_name` values |
| Site logo | `custom-logo` registered; resolves known logo slugs or Customizer; text "ResQ" fallback |
| `crystal-visions-cbd-bath-bomb-NOT-resq` | Explicitly excluded |

Baby, human CBD, and pet CBD SKUs use uploaded slugs where present in the local Media Library (`baby-*`, `full-spectrum-cbd-oil`, etc.). Re-import after adding new uploads — no code change required beyond the slug map.

## Compliance

- CBD lifestyle imagery only on CBD gateway / human CBD surfaces per [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md).
- Alt text from existing captions; decorative guide-card thumbs use empty alt.

## Smoke checklist

- [ ] Home hero, doorway split, concern lanes, ingredient authority, social proof
- [ ] Gateways: human, pet, CBD, bundles — hero + segment cards
- [ ] One lane landing (dog/women/men) hero image
- [ ] PDP featured + gallery for `RQ-HUM-AIOCREAM`, `RQ-PET-SKINCREAM`
- [ ] Shop archive product cards show thumbnails
- [ ] Learn index hero + guide card thumbnails
- [ ] Header logo (if logo slug present in library)

## Version

Shipped in `resq-core` **0.6.0** and `resq-clean-pro` **0.6.0**.
