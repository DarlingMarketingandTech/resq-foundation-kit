# Phase 10 — Compliance, Accessibility, Performance QA

> **Implementation authority** for Phase 10. Confirm quality gates before the Phase 11 preflight release package. Validation only — no new merchandising features.

## Checkpoint context

| Item | Value |
| --- | --- |
| Phase 9 | Local sandbox validation — complete (runbook [`21`](21-PHASE-9-IMPLEMENTATION-NOTES.md)) |
| Phase 10 | Compliance, accessibility, performance QA — **in progress** |
| Plugin version | `0.5.0` (hold until Phase 11 preflight unless gate requires patch) |
| Theme version | `0.5.0` |
| Owner sign-off | [`Product Data and Strategy/compliance-review-checklist.md`](Product%20Data%20and%20Strategy/compliance-review-checklist.md) |

**Architecture references:** [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md), [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md) § Phase 10, [`CHECKPOINT.md`](CHECKPOINT.md).

> **Doc numbering:** [`22-FUTURE-FEATURES-ROADMAP.md`](22-FUTURE-FEATURES-ROADMAP.md) is reserved for post-launch planning. This runbook is doc **23**.

---

## Goal

Verify CBD isolation, claim-safe copy patterns, WCAG 2.2 AA readiness, checkout safety, and baseline performance on the LocalWP sandbox before preflight packaging.

---

## Delivered hardening (code changes this phase)

The dev-actionable Phase 10 items from [`22-FUTURE-FEATURES-ROADMAP.md`](22-FUTURE-FEATURES-ROADMAP.md)
landed as code this phase — A1, A2, A3, A4, H1 (partial), H3, plus the notice-gap
fix. Each compliance feature is flag-gated and ships with neutral chrome copy;
legal/claim strings stay empty for owner sign-off (H2). The remaining Phase 10
work is the browser-based validation in the streams below and owner/legal decisions
(OD-1/5/6/8 in doc 22).

### D-A2. CBD cart isolation — add-to-cart validation (roadmap A2)

Cart-level backstop that rejects an add-to-cart which would mix CBD and non-CBD
products in one cart — including a direct `?add-to-cart=ID` URL that bypasses the
merchandising UI. Scoped to the **CBD boundary only**: human + pet and baby +
standard carts stay allowed (per [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md)
§ CBD Isolation). `resq_can_cross_sell_products()` is deliberately **not** reused —
its audience-matching would wrongly block a human + pet cart.

| Change | File |
| --- | --- |
| `validate_cart_compliance_isolation()` on `woocommerce_add_to_cart_validation` + `cart_isolation_enforced()` guard | `resq-core/includes/woocommerce/class-merchandising-hooks.php` |
| `cart_isolation_enabled` default (`true`) in `default_compliance()` | `resq-core/includes/class-options.php` |
| Option schema entry | `11-PLUGIN-DATA-SCHEMA.md` |

Enforcement requires `cbd_isolation` flag **AND** `cbd_isolation_enabled` **AND**
`cart_isolation_enabled` (all default on). Notices are claim-safe and directional.

### D-N1. Zone-level compliance notice fix

`resq_core_get_compliance_notices()` returned `[]` for any `$product_id <= 0`, so
the CBD gateway disclaimer strip (`cbd-notice.php`, product-less call) rendered
nothing even with copy configured. Added an optional `$zone` param for a
product-less, zone-scoped lookup; `cbd-notice.php` now requests the `cbd` zone.

| Change | File |
| --- | --- |
| `resq_core_get_compliance_notices( $context, $product_id = 0, $zone = '' )` + `resq_core_build_zone_notice()` | `resq-core/includes/helpers/infrastructure.php` |
| `resq_theme_render_compliance_notices()` threads `$zone` | `resq-clean-pro/inc/helpers.php` |
| CBD strip requests `cbd` zone | `resq-clean-pro/template-parts/gateway/cbd-notice.php` |
| Signatures synced | `01-THEME-PLUGIN-CONTRACT.md`, `12-PLUGIN-HELPER-CONTRACTS.md` |

Strip stays silent until `resq_core_compliance.notice_text['cbd']` is set (copy is
owner/legal-gated) — the fix restores the plumbing. `footer.php` (no product, no
zone) still renders nothing, intentional.

**Known follow-up:** `set_defaults()` uses `add_option`, which never overwrites an
existing `resq_core_compliance`, so sites activated before D-A2 don't persist
`cart_isolation_enabled` in the DB. Runtime reads default it to `true`, so
enforcement is active regardless; the future compliance settings panel must merge
`default_compliance()` into the stored option so the key is editable.

### D-A1. CBD COA / THC disclosure PDP slot (roadmap A1)

New product meta `_resq_coa_url` and `_resq_thc_disclosure`, helper
`resq_get_product_cbd_disclosure()` (CBD-lane + `coa_disclosure` flag gated), and a
PDP slot (`template-parts/product/compliance-coa.php`, hooked at
`woocommerce_single_product_summary` priority 26). Renders only when a CBD product
has COA/THC data populated, so it stays silent until the owner adds real values.

### D-A3. CBD state-restriction checkout gate (roadmap A3)

`ResQ_Core_Compliance_Gates::validate_state_restriction()` on
`woocommerce_after_checkout_validation` blocks checkout to a restricted state when
the cart contains CBD. Gated by the `state_restriction` flag. **Inert until the
owner supplies `restricted_states`** (empty default blocks nothing); notice copy
falls back to a neutral message when `state_restriction_notice` is blank.

### D-A4. Age verification gate (roadmap A4)

Cookie-based soft gate on CBD surfaces — `template-parts/compliance/age-gate-modal.php`
+ `assets/js/age-gate.js` (focus-trapped, scroll-locked, confirm sets
`resq_age_confirmed` cookie, decline exits to home). Wired in theme
`inc/compliance.php`, gated by the `age_gate` flag; min age from
`age_gate_min_age` (21). Context = CBD gateway page, CBD PDP, or CBD category.
**Owner decision pending (OD-5):** whether a soft cookie gate satisfies the
payment processor or a third-party verification service is required — a swap
behind the same flag.

### D-H3. Cookie consent banner (roadmap H3)

Site-wide banner — `template-parts/compliance/cookie-consent.php` +
`assets/js/cookie-consent.js`. Records accept/decline in `resq_cookie_consent`,
dispatches a `resq-consent` JS event, and exposes `resq_theme_has_analytics_consent()`
for Phase 11 analytics tags to gate on. Gated by `cookie_consent`. Privacy-policy
link uses WP's `get_privacy_policy_url()` (owner sets the page).

### D-H1. Static accessibility pass (roadmap H1, partial)

All new compliance surfaces are built accessibly (dialog roles, labelled regions,
focus trap on the age gate, SR-only "opens in new tab" on the COA link). Fixed an
existing defect: the mobile-drawer and cart-drawer **close** buttons carried a
static `aria-expanded="true"` (invalid on a non-toggle) — removed. Added a real
`.btn` component base (previously undefined project-wide; existing cart buttons
relied on it). **Still pending:** axe/Lighthouse scan and screen-reader walkthrough
on a running site, color-contrast verification against final token values, and the
product-image `alt` audit (needs real catalog images — none in repo yet).

### H2. FTC/FDA copy compliance review — owner action

No code: this is a structured copy review + owner sign-off against
[`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md), recorded in
[`Product Data and Strategy/compliance-review-checklist.md`](Product%20Data%20and%20Strategy/compliance-review-checklist.md).
Mechanism is ready (all plugin copy fields are claim-safe by design); the sign-off
is an owner/legal task before the Phase 11 gate.

**Target Problem Lane guardrails (June 2026):** Per-lane allowed/prohibited claim
territory is now in [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md) § Target
Problem Lane Copy Rules. Owner sign-off table: compliance checklist §7. Draft lane
briefs must be scrubbed for risky phrases (`eliminates`, `cellular micro-repair`,
undocumented `vet-recommended`, etc.) before any lane page ships.

> **Local sandbox:** when `resq_core_is_local_sandbox()` is true (WP environment
> `local`/`development`, or a `.local`/`.test` host), compliance surfaces use
> `[Dev preview]` placeholder copy instead of staying silent, age gate and cookie
> consent are suppressed, and cart/state checkout enforcement is off. Production
> keeps owner-gated silence until copy is approved.

> **Production posture:** every Phase 10 feature flag ships **enabled**; legal/claim
> strings (`notice_text`, `restricted_states`, `state_restriction_notice`) stay
> empty until owner/legal sign-off. Flip any flag off in `resq_core_features`, or
> leave claim strings empty, to keep a surface dormant.

---

## Prerequisites

Run from LocalWP **Open site shell** at the WordPress site root. Catalog imported (`wp resq-catalog import`). `resq-core` and `resq-clean-pro` active at `0.5.0`.

```bat
wp option get siteurl
wp plugin list --fields=name,status,version
wp theme list --fields=name,status,version
```

---

## Stream A — Compliance (CBD + claim language)

**Authority:** [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md), owner checklist.

### A1. CBD isolation — data layer

For each non-CBD human product, FBT and cross-sells must contain zero CBD SKUs:

```bat
wp eval "foreach(['RQ-HUM-AIOCREAM','RQ-HUM-WASH'] as $sku){ $id=wc_get_product_id_by_sku($sku); echo $sku.': '; $fbt=resq_get_frequently_bought_together($id); $cs=resq_core_get_cross_sells($id); $bad=0; foreach(array_merge((array)$fbt,(array)$cs) as $r){ $rid=is_object($r)?$r->get_id():$r; if(resq_is_cbd_product($rid)) $bad++; } echo 'cbd_leaks='.$bad.PHP_EOL; }"
```

Expected: `cbd_leaks=0` for all non-CBD SKUs tested.

### A2. CBD isolation — rendered surfaces

HTTP-scan non-CBD surfaces for CBD product slugs (17 CBD products in catalog):

| Surface | URL | Pass criteria |
| --- | --- | --- |
| Human gateway | `/human/` | No `/product/*cbd*` links |
| Human PDP | `/product/all-in-one-intensive-skin-treatment-moisturizer/` | No CBD slugs in FBT/related |
| Human category | `/product-category/anti-aging-serums/` | No CBD product cards |
| Search (generic) | `/?s=skin` | No CBD slugs unless query is CBD-specific |
| Search (CBD term) | `/?s=cbd` | CBD results allowed in CBD context |
| Shop filtered human | `/shop/?resq_audience=human` | No CBD zone products |
| Header nav | `/` | Human/pet links resolve to gateways, not CBD PDPs |
| Footer nav | `/` | Same as header (uses `resq_theme_get_gateway_page_url`) |

### A3. CBD gateway disclaimer slot

Browse `/cbd/` — confirm CBD-isolated layout and disclaimer/notice slot present (browser).

### A4. Zone-scoped copy review

Spot-check catalog strings for high-risk SKUs from owner checklist (do **not** change copy without owner approval):

| Zone / flag | Example SKU | Check |
| --- | --- | --- |
| `cbd` | `RQ-HCBD-SOFTGELS` | No weight-loss/medical claims in title or short description |
| `baby` | `RQ-BABY-CREAM` | No "hypoallergenic guarantee" or newborn safety overclaims |
| `pet-health` | `RQ-KIT-PET-HOTSPOT` | No "treats/heals" veterinary claims |
| `medical-adjacent` | `RQ-PET-DIABETIC-TREATS` | Diabetic wording is routine-safe, not diagnostic |

Document pending owner sign-offs in Validation results — do not block gate on unchecked boxes.

### A4b. Target Problem Lane draft briefs (pre-publish)

When lane pages are built, review draft copy against
[`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md) § Target Problem Lane Copy Rules
and record per-lane sign-off in compliance checklist §7. **No lane ships on draft
brief text as-is** — strategy briefs contain phrases that fail Medical/Pet/Baby
caution rules until scrubbed.

### A5. CBD cart isolation (add-to-cart backstop)

Verifies D-A2 against a real, initialized cart (`wc_load_cart()`), so
`WC()->cart->get_cart()` is populated — a bare `wp eval` filter call cannot
exercise it (CLI request has no cart). Run the self-contained checker:

```bat
wp eval-file C:\tmp\resq-verify-cart-isolation.php
```

| Case | Pass criteria |
| --- | --- |
| standard in cart + add CBD | blocked (`passed=false`) + CBD notice |
| standard in cart + add another standard | allowed (`passed=true`) |
| CBD in cart + add standard | blocked + reverse-direction notice |
| empty cart + add CBD | allowed (first item never conflicts) |

Expected terminal line: `Success: Cart isolation behaves correctly…`.

---

## Stream B — Accessibility (WCAG 2.2 AA target)

Browser-required. Log blockers vs documented exceptions.

| Check | Surface | Pass criteria |
| --- | --- | --- |
| Skip link | Any page | "Skip to content" link present, targets `#primary-content` |
| Focus visible | Shop, PDP | Interactive elements show focus ring |
| Cart drawer focus trap | PDP AJAX add | Focus enters drawer; Tab cycles within; Escape closes; focus restored |
| Cart drawer ARIA | PDP AJAX add | `role="dialog"`, labelled "Added to cart", close button named |
| Mobile nav | `/` @ 375px | Menu toggle operable; drawer opens/closes |
| Filter controls | `/shop/?resq_audience=human` | Filter UI keyboard reachable |
| Form labels | `/checkout/` (with cart item) | Billing fields have associated labels |
| Reduced motion | CSS | `@media (prefers-reduced-motion)` respected in theme tokens |
| Alt text | PLP cards | Product images have alt or placeholder alt |

---

## Stream C — Checkout / payment safety

| Check | Method | Pass criteria |
| --- | --- | --- |
| Empty cart redirect | `GET /checkout/` | 302 → `/cart/` when cart empty |
| Checkout with items | Add product, visit `/checkout/` | Form renders; no PHP fatals |
| No custom payment iframe | Code review | Foundation kit does not override gateway iframe markup |
| Plugin-off checkout | `wp plugin deactivate resq-core` | Checkout/cart still render without fatals |
| Nonce safety | Code review | No bypass of Woo nonce validation in theme/plugin |

---

## Stream D — Performance baseline

Run Lighthouse (mobile) or equivalent on LocalWP URLs. Document scores and gaps — defer non-blocking optimizations to post-launch.

| URL | LCP target | CLS target | Notes |
| --- | --- | --- | --- |
| `/` | < 2.5s | < 0.1 | Home |
| `/shop/` | < 2.5s | < 0.1 | PLP |
| `/human/` | < 2.5s | < 0.1 | Gateway |
| `/product/all-in-one-intensive-skin-treatment-moisturizer/` | < 2.5s | < 0.1 | PDP |
| `/cart/` | < 2.5s | < 0.1 | Cart (with item) |

Local sandbox scores are indicative only — production CDN, image optimization, and caching will differ.

---

## Exit criteria (from [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md))

- [ ] Compliance review findings resolved or documented.
- [ ] Accessibility blockers resolved or documented.
- [ ] Checkout remains gateway-safe.
- [ ] Performance risks are listed with next actions.

---

## Validation results

### A5 — CBD cart isolation (2026-06-24)

Verified on LocalWP via `wp eval-file C:\tmp\resq-verify-cart-isolation.php`
against a real initialized cart. Picked CBD `CBD Gummies 3-Pack` and non-CBD
`Diabetic Dog Treats 3-Pack` from the imported catalog.

| Case | Expected | Result |
| --- | --- | --- |
| standard in cart + add CBD | block | `passed=false` + CBD notice ✅ |
| standard in cart + add another standard | allow | `passed=true` ✅ |
| CBD in cart + add standard | block | `passed=false` + reverse notice ✅ |
| empty cart + add CBD | allow | `passed=true` ✅ |

Filter confirmed registered; both directional notices fired. **PASS.**

_(Remaining streams filled after gate run.)_

---

### B — Accessibility code review (2026-06-24)

Code-review pass covering all items answerable without a browser.

| Check | File(s) reviewed | Result |
| --- | --- | --- |
| Skip link present | `header.php` | `<a class="screen-reader-text" href="#primary-content">` — present ✅ |
| Skip link target | All page templates (`index.php`, gateways, shop, PDP, search) | `<main id="primary-content">` — all templates covered ✅ |
| Cart drawer ARIA | `template-parts/cart/drawer.php` | `role="dialog"`, `aria-modal="true"`, `aria-labelledby="resq-cart-drawer-title"`, close `aria-label` ✅ |
| Cart drawer focus trap | `assets/js/cart-drawer.js` | `trapFocus()` + `handleTrapKeydown()` + focus restoration on close ✅ |
| Cart drawer Escape | `assets/js/cart-drawer.js` | `document.keydown` Escape handler ✅ |
| Mobile nav ARIA | `template-parts/global/mobile-drawer.php` | `role="dialog"`, `aria-modal="true"`, `aria-label`, close `aria-label` ✅ |
| Mobile nav `aria-hidden` toggle | `assets/js/navigation.js` | **BUG FIXED (2026-06-24)** — outer drawer `aria-hidden` was never toggled; `setOpen()` now sets `aria-hidden="false/true"` ✅ |
| Mobile nav focus trap | `assets/js/navigation.js` | **BUG FIXED (2026-06-24)** — no Tab trap existed; `trapFocus()` added, bound on open / unbound on close ✅ |
| Mobile nav Escape | `assets/js/navigation.js` | `document.keydown` Escape + `toggle.focus()` restoration ✅ |
| Filter controls ARIA | `template-parts/gateway/filter-shell.php` | `aria-labelledby="resq-filter-shell-heading"` on section ✅; keyboard reachability → browser check |
| Alt text — gateway shelf | `template-parts/gateway/product-shelf.php` | `alt="<?php echo esc_attr($card['title']); ?>"` ✅ |
| Alt text — PLP cards | `woocommerce/content-product.php` | Uses WooCommerce `woocommerce_template_loop_product_thumbnail` → inherits attachment alt; verify populated in browser |
| Reduced motion | `assets/css/tokens.css`, `assets/css/components.css` | `@media (prefers-reduced-motion: reduce)` present in both files ✅ |
| Form labels — checkout | `woocommerce/checkout/form-checkout.php` | Fields rendered via `do_action('woocommerce_checkout_billing')` (Woo core) — labels owned by WooCommerce; browser verify |

**Browser-only items remaining:** focus ring visibility, filter keyboard reachability, PLP alt text population, checkout label association.

---

### C — Checkout / payment safety code review (2026-06-24)

Code-review verifier script: `wp eval-file C:\tmp\resq-verify-checkout-safety.php`

| Check | Method | Result |
| --- | --- | --- |
| No custom payment iframe | `woocommerce/checkout/form-checkout.php` code review | No `<iframe>` in file; uses only `do_action('woocommerce_checkout_order_review')` ✅ |
| Plugin-off checkout safety | `inc/helpers.php` code review | `resq_theme_render_compliance_notices()` is a theme function; guards `resq_core_get_compliance_notices()` with `function_exists()` internally — returns cleanly when plugin off ✅ |
| Nonce — cart drawer AJAX | `includes/woocommerce/class-merchandising-hooks.php` | `check_ajax_referer('resq_cart_drawer', 'nonce')` called before any processing ✅ |
| Nonce — cart form | `woocommerce/cart/cart.php` | `wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce')` preserved ✅ |
| No resq-* payment hook override | Runtime hook scan | No resq-* callbacks on `woocommerce_pay_order_before_submit`, `woocommerce_checkout_process`, or `woocommerce_checkout_order_created` — confirmed by verifier script |

**Browser / manual items remaining:**

```bat
wp eval-file C:\tmp\resq-verify-checkout-safety.php
```

Then manually:

1. `GET /checkout/` with empty cart → expect 302 → `/cart/`
2. Add a product, visit `/checkout/` → form renders, no PHP fatals
3. Plugin-off smoke:

```bat
wp plugin deactivate resq-core
```

_(visit /cart/ and /checkout/ in browser — expect no fatal errors)_

```bat
wp plugin activate resq-core
```

---

## Read next

1. [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md) — Phase 11 preflight
2. [`Product Data and Strategy/compliance-review-checklist.md`](Product%20Data%20and%20Strategy/compliance-review-checklist.md) — owner sign-off
3. [`.codex/skills/preflight-package-check/SKILL.md`](../.codex/skills/preflight-package-check/SKILL.md) — pre-merge checklist
