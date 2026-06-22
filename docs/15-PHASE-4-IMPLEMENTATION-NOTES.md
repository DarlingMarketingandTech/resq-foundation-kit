# 15 — Phase 4 Implementation Notes

> Theme global foundation shell for `resq-clean-pro`.

## Checkpoint Context

| Item | Value |
|---|---|
| Phase 3 | Plugin routine-commerce model — complete |
| Phase 4 | Theme global foundation — in progress |
| Theme version | `0.2.0` |
| Branch target | `phase-4-theme-global-foundation` |
| Tag target | `v0.5-phase-4-theme-global-foundation` |

---

## What Phase 4 Delivered

### Theme structure

| Path | Purpose |
|---|---|
| `header.php` / `footer.php` | Global layout wrappers |
| `template-parts/global/header-navigation.php` | Responsive primary nav shell |
| `template-parts/global/mobile-drawer.php` | Mobile nav drawer |
| `template-parts/compliance/notices.php` | Compliance notice slot |
| `template-parts/product/routine-ladder.php` | PDP routine ladder shell (plugin-guarded) |
| `inc/setup.php` | Theme supports, menu locations |
| `inc/helpers.php` | `resq_theme_*` helpers |
| `inc/navigation.php` | Planning-route nav definitions |
| `inc/assets.php` | CSS/JS enqueue |
| `assets/css/tokens.css` | Neutral accessible `--resq-*` tokens |
| `assets/css/base.css` | Reset, focus, containers, grid |
| `assets/css/layout.css` | Header, footer, responsive nav |
| `assets/css/components.css` | Routine ladder, badges, notices |
| `assets/js/navigation.js` | Mobile drawer toggle |

### Nav routes (planning-level)

| Label | Route |
|---|---|
| Shop For Humans | `/shop/human/` |
| Shop For Pets | `/shop/pet/` |
| Bundles & Savings | `/shop/bundles/` |
| CBD & Wellness | `/shop/human/cbd-wellness/` (when CBD isolation enabled) |
| Learn | `/learn/` |

Brand placeholder: **ResQ** (text only).

### Plugin guard pattern

All data slots check `function_exists()` / `resq_core_is_active()` before calling plugin helpers. Missing plugin omits slots without fatals.

---

## Exit Criteria

- [ ] Theme activates with or without plugin
- [ ] Global layout renders on basic WP pages
- [ ] Navigation is keyboard-accessible and responsive
- [ ] Theme does not register or mutate product data

---

## Deferred

| Item | Phase |
|---|---|
| Woo template overrides (`archive-product`, `single-product`, etc.) | 5 |
| Gateway page templates | 6 |
| Mega-menu content panels | 6 |
| Cart drawer UI | 8 |
| Final brand palette/fonts | When brand assets lock |

---

## Read Next

1. `06-BUILD-ROADMAP.md` — Phase 5 Woo template shells
2. `03-WOO-TEMPLATE-MAP.md` — override inventory
