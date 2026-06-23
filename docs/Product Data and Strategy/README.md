# Product Data and Strategy — Artifact Index

Strategy research and import reference files. **Not** live catalog authority — the importer reads PHP data in `wp-content/plugins/resq-core/includes/catalog/data/`; these CSVs informed that data.

## Operational artifacts

| File | Role |
| --- | --- |
| [`compliance-review-checklist.md`](compliance-review-checklist.md) | Owner sign-off for CBD, baby, pet-health, and medical-adjacent SKUs |
| [`resq-catalog-import.csv`](resq-catalog-import.csv) | WooCommerce CSV reference export (~77 rows); backup for manual import |
| [`woo import template.csv`](woo%20import%20template.csv) | Empty WooCommerce import column template |

## Strategy source CSVs

| File | Contents |
| --- | --- |
| Product Architecture Map | Canonical families, bundle opportunities |
| Product Pricing | Recommended prices and "Final Check Needed" notes |
| Recommended Taxonomy | Woo `product_cat` and card meta guidance |
| Bundle Pricing Model | Kit/duo/pack prices |
| Bundle Opportunities | Bundle composition ideas |
| Bulk Buy Strategy | Multipack and subscribe-and-save strategy (not yet implemented in Woo) |
| Audience Positioning | Human/pet/CBD positioning |
| SEO Content Map | Content and keyword mapping |
| Competitor Benchmarks | Market anchors |
| Tactic Source Notes | Research provenance |

Full filenames are prefixed with `ResQ Organics - New Store Recommendation Architecture and Pricing Research -`.

## Related commands

```bat
wp resq-catalog import
wp resq-catalog export-csv
```

See [`../19-CATALOG-IMPORT-NOTES.md`](../19-CATALOG-IMPORT-NOTES.md).
