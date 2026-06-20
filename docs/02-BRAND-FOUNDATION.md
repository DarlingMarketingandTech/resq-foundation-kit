# 02 — Brand Foundation

> Visual and voice rules for agents using **Taste Skill** — constrained by accessibility and performance (see `05-COMPLIANCE-RULES.md`).

## Brand snapshot

| Attribute | Value |
|---|---|
| Brand name | _TBD_ |
| Primary audience | _TBD_ |
| Positioning | _TBD_ |
| Tone of voice | _TBD_ |

## Color tokens

Define CSS custom properties in theme; plugin must not hard-code hex values.

```css
/* Example — replace with real palette */
:root {
  --resq-color-primary: /* TBD */;
  --resq-color-secondary: /* TBD */;
  --resq-color-surface: /* TBD */;
  --resq-color-text: /* TBD */;
  --resq-color-accent: /* TBD */;
}
```

## Typography

| Role | Font stack | Notes |
|---|---|---|
| Display | _TBD_ | |
| Body | _TBD_ | |
| UI / labels | _TBD_ | |

## Spacing and density

- Base unit: _TBD_ (e.g. 4px or 8px grid)
- Commerce pages: favor scannable hierarchy over decorative density
- Mobile-first breakpoints: _TBD_

## Imagery

- Product photography standards: _TBD_
- Lifestyle vs packshot ratio on PLP: _TBD_
- Lazy-load and `srcset` required for catalog images

## Motion

- Respect `prefers-reduced-motion`
- No motion that delays checkout or obscures primary CTAs
- Max animation duration for UI feedback: _TBD_

## Voice (Stop Slop aligned)

- Lead with concrete benefits, not filler adjectives
- Avoid banned patterns from Stop Slop (generic “elevate your journey” copy)
- Merchant-facing strings: clear, scannable, honest about shipping/returns

## Reference assets

- Logo files: _path TBD_
- Figma / brand deck: _link TBD_
