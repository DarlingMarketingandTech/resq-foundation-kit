# Brand Harmonization Proofs

Standalone HTML mockups for comparing three brand-unification directions before any WordPress or theme work.

**v2 — Premium mockups:** Full homepage slices (not wireframes). Each direction includes hero, bento gateways, routine ladder, product grid, bundle spotlight, reviews, Learn bridge, CBD compliance strip, and newsletter.

## View locally

1. Open `brand-proofs/index.html` in any browser (double-click or drag into Chrome/Edge/Firefox).
2. Click each direction card to compare full-page mockups.
3. Resize the window or use DevTools device mode to check mobile.

No build step, server, or npm required.

## Share with Terry

**Option A — Zip the folder**

```bat
cd C:\dev\resq-foundation-kit
powershell Compress-Archive -Path brand-proofs -DestinationPath brand-proofs.zip
```

Send `brand-proofs.zip`; Terry unzips and opens `index.html`.

**Option B — Static deploy (optional)**

Deploy the `brand-proofs/` folder to any static host:

- **Vercel:** `cd brand-proofs && npx vercel --yes` (no framework preset needed)
- **Netlify Drop:** drag the `brand-proofs` folder to [app.netlify.com/drop](https://app.netlify.com/drop)
- **GitHub Pages:** push folder to `gh-pages` branch or enable Pages on `/brand-proofs`

Send Terry the preview URL.

## Files

| File | Purpose |
|------|---------|
| `index.html` | Gallery landing with links to all directions |
| `direction-a-masterbrand.html` | Unified masterbrand + audience accent lanes (**recommended**) |
| `direction-b-house-of-brands.html` | Distinct Pets vs People sub-brands |
| `direction-c-amazon-anchored.html` | Amazon listing aesthetic on web |
| `assets/base.css` | Shared layout structure |
| `assets/direction-*.css` | Per-direction brand skin |
| `assets/brand-dna.md` | Audit notes from current site + Amazon |

## After a direction is chosen

1. Owner sign-off on direction (A, B, or C — or hybrid).
2. Fill in `docs/02-BRAND-FOUNDATION.md` (palette, type, imagery rules).
3. Update `wp-content/themes/resq-clean-pro/assets/css/tokens.css`.
4. Plan product photography against chosen direction (real packshots vs AI-assisted placeholders).
5. Do **not** import legacy site copy — use claim-safe language per `docs/05-COMPLIANCE-RULES.md`.

## Constraints

- Mockups use CSS placeholders, not live product photos (offline-safe).
- Google Fonts load from CDN when online; layout still works offline with system fallbacks.
- CBD lane includes disclaimer placeholder only — full age gate is Phase 10 scope.
