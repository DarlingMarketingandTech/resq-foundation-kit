---
name: preflight-package-check
description: Pre-merge and pre-release verification checklist for theme and plugin packages.
---

# Preflight Package Check

## Use when

- About to merge a feature branch
- Preparing a staging or production deploy
- Running final QA on a theme or plugin release

## Checklist

- [ ] `wp db export` backup taken before any DB mutation
- [ ] Build passes (e.g. `npm run build` for Sage/Vite)
- [ ] Smoke test: home, PLP, PDP, add-to-cart, checkout start
- [ ] No PHP fatals or warnings in debug log
- [ ] No critical JS console errors
- [ ] Template map (`docs/03-WOO-TEMPLATE-MAP.md`) up to date
- [ ] Theme/plugin contract (`docs/01-THEME-PLUGIN-CONTRACT.md`) not violated
- [ ] Rollback path documented in PR description
- [ ] No secrets or PII in committed files
- [ ] CodeGraph index reflects current file structure
