# 22 — Future Features Roadmap

> Planning reference for all features beyond Phase 9. Captured so nothing is bolted on awkwardly post-launch. Features are not committed to any sprint until the preceding phase gate passes and, where noted, owner decisions are resolved.

---

## How to use this doc

This doc is a living planning index, not a sprint backlog. For each feature it records: what's already built that supports it, what's still missing, hard dependencies that must resolve before work starts, and a suggested phase. When a feature graduates to active implementation, create a numbered runbook (`23-...`, `24-...`) following the pattern of `20-PHASE-8-IMPLEMENTATION-NOTES.md`.

**Architecture authority still lives in docs `00`–`12`.** This doc does not override them; it links to them.

---

## Phase 10 — Compliance, Accessibility, and Performance QA

Phase 10 is already scoped in [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md). The entries below expand each task with what's already in place and what's still needed.

### A1 — CBD Disclaimer and COA Slots (PDP)

**What it is:** Dedicated template slots on CBD PDPs for Certificate of Analysis (COA) links, THC disclosure percentage, and state restriction notice.

**Already built:**
- `_resq_compliance_flags` and `_resq_compliance_zone` meta schema in plugin.
- `resq_is_cbd_product()` and `resq_requires_compliance_notice()` helpers.
- `resq_theme_render_compliance_notices( $context, $product_id )` render helper.
- CBD notice slots on category, PDP, cart, and checkout established in Phase 8 — see [`20-PHASE-8-IMPLEMENTATION-NOTES.md`](20-PHASE-8-IMPLEMENTATION-NOTES.md).
- CBD isolation in cross-sell and FBT logic via `resq_can_cross_sell_products()`.

**What's missing:**
- COA document URL field (`_resq_coa_url`) — not yet in schema; add to `11-PLUGIN-DATA-SCHEMA.md` and plugin admin fields before Phase 10 lands.
- THC disclosure value field (`_resq_thc_disclosure`) — same gap.
- State restriction notice copy variants in compliance option (`resq_core_compliance`) — copy must be owner-reviewed before shipping.
- PDP template slot for COA download link (theme side, `template-parts/product/compliance-coa.php`).

**Dependencies / blockers:** Owner/legal sign-off on COA copy and THC disclosure language (see [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md)). Production COA documents from manufacturer.

**Suggested phase:** 10.

---

### A2 — CBD Cart Isolation (Direct Add-to-Cart Prevention)

**What it is:** Prevent CBD and baby/infant products from landing in the same cart order when a shopper bypasses the standard UI — e.g., via a direct `?add-to-cart=ID` URL parameter.

**Already built:**
- `resq_can_cross_sell_products()` — cross-sell safety gate blocks CBD→standard and baby→CBD in merchandising UI.
- `_resq_compliance_zone` assigned per product.
- Plugin owns `woocommerce/` cart and checkout hook slots (see `01-THEME-PLUGIN-CONTRACT.md`).

**What's missing:**
- `woocommerce_add_to_cart_validation` filter in `resq-core` to inspect cart contents when a new item is added and reject the add with a notice if the resulting cart violates CBD isolation rules.
- Admin-configurable option in `resq_core_compliance` to enable/disable cart-level enforcement separately from merchandising-UI enforcement (allows toggling without code change).
- User-facing rejection notice copy — must be claim-safe and tested in checkout.

**Dependencies / blockers:** Phase 9 fresh-install gate must pass first (confirms cart hooks fire correctly on clean WC install). Owner decision on rejection behavior: hard block with notice, or soft warning with confirmation.

**Suggested phase:** 10.

---

### A3 — State Restriction Logic for CBD

**What it is:** A mechanism to block or warn shoppers in states where CBD sales are restricted, evaluated at checkout or product view.

**Already built:**
- `_resq_compliance_zone` and `_resq_compliance_flags` schema supports jurisdiction tagging per product.
- Plugin owns checkout field/validation hooks.

**What's missing:**
- Decision: geo-IP check at page load vs. checkout field validation vs. both. Each has different accuracy/UX tradeoffs and different liability implications.
- Geo-IP approach requires a data source (MaxMind GeoLite2 free tier is common; WooCommerce has its own MaxMind integration). Add dependency note to plugin if used.
- Restricted state list — must come from owner/legal, not hardcoded by dev.
- `resq_core_compliance` option structure for storing restricted state codes.
- Checkout validation hook that reads the shipping state field against the restricted list when `_resq_compliance_zone` is `cbd`.
- Notice copy for restricted-state shoppers.

**Dependencies / blockers:** Owner/legal must supply the restricted state list and approve the notice copy before this ships. Payment processor terms also affect what's required here — some processors require active state blocking for CBD, not just a notice.

**Suggested phase:** 10 (geo-IP or checkout-field variant); or post-launch if legal review extends.

---

### A4 — Age Verification Gate (CBD)

**What it is:** A modal or page-level "Are you 21+?" gate shown before CBD products are visible or purchasable. Cookie- or session-based.

**Already built:**
- CBD isolation pathways (separate nav/category/routes) mean the gate can be scoped to CBD URL prefixes only.
- Plugin feature-flag system (`resq_core_features`) ready for `age_gate_enabled` flag.

**What's missing:**
- Gate implementation decision: modal on CBD category/PDP entry vs. dedicated landing page redirect vs. third-party age verification service.
  - **Cookie-based modal** — low friction, lowest cost, weakest verification; appropriate for general soft-gate.
  - **Session-based** — same friction as cookie, doesn't persist across sessions.
  - **Third-party service** (AgeChecker.net, Veratad, etc.) — stronger verification, has cost and integration overhead; required by some payment processors for CBD.
- Owner/legal decision on which level of verification is required.
- Template: `template-parts/compliance/age-gate-modal.php` (theme side).
- JS: focus trap, ARIA role dialog, keyboard dismiss.
- Plugin hook to inject gate into CBD URL responses.
- Cookie/session key convention (add to `11-PLUGIN-DATA-SCHEMA.md`).

**Dependencies / blockers:** Owner decision on gate type. Payment processor terms — confirm whether processor requires third-party verification before implementing a soft cookie gate. Do not build until processor is confirmed.

**Suggested phase:** 10 if soft gate is approved; may slip to post-launch if third-party service is required.

---

### H1 — WCAG 2.2 AA Audit and Remediation

**What it is:** Full accessibility audit of all storefront surfaces against the target declared in [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md).

**Already built:**
- Focus trap on cart drawer and mobile drawer (Phase 4/5).
- `prefers-reduced-motion` defaults (Phase 4).
- Keyboard operability established on nav, filters, cart.
- Form labels and errors are WooCommerce-native (compliant by default; custom fields need per-field audit).

**What's missing:**
- Automated scan: run axe-core or similar against all Phase 5/6/8 surfaces and log findings.
- Manual keyboard walkthrough: mega-menu, mobile drawer, cart drawer, checkout, account.
- Color contrast verification against final brand token values (tokens are set in Phase 4; need audit against 4.5:1 body / 3:1 UI rule).
- `alt` audit on any product images imported via `wp resq-catalog`.
- Screen reader walkthrough of routine ladder, FBT block, badge markup.
- Remediation of any blocking findings before Phase 11 gate.

**Dependencies / blockers:** Requires real catalog images to be present (no images yet at Phase 9). Phase 10 owns this work per `06-BUILD-ROADMAP.md`.

**Suggested phase:** 10.

---

### H2 — FTC/FDA Copy Compliance Final Review

**What it is:** Structured review of all customer-facing copy against the claim rules in [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md) before launch.

**Already built:**
- Compliance rules fully documented.
- `_resq_short_benefit_tags` and `_resq_product_card_subtitle` are the only plugin-owned copy fields — they were designed claim-safe.
- `Product Data and Strategy/compliance-review-checklist.md` is the owner sign-off document.

**What's missing:**
- Populated checklist with actual copy from the live catalog (not fixture content).
- Review of category/gateway page editorial copy (lives in WP Page content, not plugin schema — needs manual review).
- Review of WooCommerce email templates (order confirmation, etc.) for claim language.
- Owner signature on `compliance-review-checklist.md` before Phase 11.

**Dependencies / blockers:** Real catalog content must be imported. Owner availability for sign-off.

**Suggested phase:** 10 (review); Phase 11 gate (sign-off).

---

### H3 — Privacy Policy and Cookie Consent

**What it is:** CCPA and GDPR basics — privacy policy page, cookie consent banner, and analytics deferral until consent is given.

**Already built:**
- [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md) flags analytics consent as a prerequisite to tag loading.

**What's missing:**
- Owner decision: what markets are being served at launch (US only, or also EU/UK)? GDPR applies if selling to EU; CCPA applies to California regardless.
- Privacy policy content — requires legal review, not generated by dev.
- Cookie consent implementation decision: native WP approach, WooCommerce's built-in cookie notice, or a dedicated consent plugin (Complianz, CookieYes, etc.). Each has different GDPR compliance depth.
- Analytics tag loading deferred behind consent signal (see Group F — Analytics).

**Dependencies / blockers:** Owner decision on target markets and consent depth. Legal review of privacy policy content.

**Suggested phase:** 10 (structure); Phase 11 (final copy and sign-off).

---

## Phase 11 — Preflight Release Package

Phase 11 tasks are scoped in [`06-BUILD-ROADMAP.md`](06-BUILD-ROADMAP.md). The additions below are features that must reach a shippable state before the release tag — not polished post-launch, but present and safe.

### F1 — Conversion Tracking (GA4, Meta Pixel)

**What it is:** Purchase and funnel event tracking for GA4 and optionally Meta Pixel or TikTok Pixel.

**Already built:**
- WooCommerce provides purchase, add-to-cart, and checkout event hooks (`woocommerce_thankyou`, `woocommerce_add_to_cart`, etc.).
- Plugin owns checkout hooks and can inject tracking scripts without blurring the theme/plugin boundary — script enqueuing belongs in the plugin.

**What's missing:**
- Owner decision: which platforms to track at launch (GA4 required; Meta/TikTok optional).
- Consent deferral mechanism (see H3) — no tag may fire before consent if required.
- GA4 Measurement ID and configuration — owner must supply.
- Implementation approach: native code vs. Google Tag Manager vs. a WooCommerce integration plugin (MonsterInsights, WooCommerce Google Analytics, PixelYourSite). GTM is the most flexible; native code is the most auditable.
- `resq_core_features['conversion_tracking']` flag and settings for Measurement ID storage.
- Server-side event consideration for CBD (client-side pixels can be blocked by ad blockers; server-side via Meta CAPI or GA4 MP API is stronger for regulated categories).

**Dependencies / blockers:** Consent/cookie decision (H3) must precede tag implementation. Owner must supply tracking IDs. Payment processor and ad platform terms must be reviewed for CBD tracking — some platforms prohibit pixel tracking of CBD purchase events.

**Suggested phase:** 11 (basic GA4 at minimum before launch).

---

### G3 — Tax Configuration

**What it is:** WooCommerce tax settings, nexus state setup, and tax class assignment for digital vs. physical products and CBD-specific tax rules.

**Already built:**
- WooCommerce handles tax calculation natively. Product tax class assignment is Woo-owned.
- Plugin does not interfere with tax logic.

**What's missing:**
- Owner/accountant input on nexus states (where the business has sales tax obligations).
- Tax class assignment for each SKU type (standard physical, digital, CBD — rules vary by state).
- CBD-specific tax rules (some states tax CBD differently or exempt it).
- Configuration runbook for WooCommerce Tax settings (rates, display, prices entered with/without tax).
- Confirmation that "Price, tax, coupon, bundle, and savings display must match real WooCommerce calculations" (per [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md)) holds after tax config.

**Dependencies / blockers:** Owner/accountant decision. Must be resolved before production orders are placed.

**Suggested phase:** 11.

---

### G4 — Returns and Refunds Workflow

**What it is:** A documented WooCommerce refund process and a published refund policy page.

**Already built:**
- WooCommerce admin has native refund tools (manual refund, automated refund via payment gateway).
- Refund policy page exists as a draft.

**What's missing:**
- Refund policy content — must match actual business policy; legal review recommended before publishing.
- Decision on CBD refunds: can CBD be returned? Policy must be explicit.
- WooCommerce admin training note for owner: how to issue a refund, partial refund, and restock.
- Return shipping policy if physical return is required.

**Dependencies / blockers:** Owner decision on refund terms and CBD return policy. Must be final before launch.

**Suggested phase:** 11.

---

## Post-Launch Phase 1 — Promotions and Growth

### B1 — Coupon Codes

**What it is:** WooCommerce native coupon infrastructure with a defined strategy for coupon types (percentage, fixed cart, fixed product, free shipping, auto-apply) and polished cart/checkout UI for coupon entry.

**Already built:**
- WooCommerce coupons are available natively.
- Cart and checkout templates are theme-overridden (Phase 5); coupon field is present in standard Woo output.

**What's missing:**
- Coupon strategy document (types, rules, stacking policy, expiry conventions) — owner decision.
- Theme polish for the coupon entry field in cart and checkout: style the input, success state, and rejection notice to match brand design tokens.
- CBD coupon restrictions — confirm whether coupon-discounted CBD orders cause payment processor issues (some processors flag deeply discounted regulated products).
- Auto-apply coupon mechanism: WooCommerce supports URL-based auto-apply (`?coupon_code=X`); decide if this is used for promotions.

**Dependencies / blockers:** Coupon strategy doc (owner-driven). Payment processor terms for CBD discounts.

**Suggested phase:** Post-launch phase 1.

---

### B2 — Sale Badges and Scheduled Pricing

**What it is:** A defined process for scheduling sales, surfacing sale badges on PLPs/PDPs, and ensuring sale copy is claim-safe.

**Already built:**
- `resq_get_product_badges()` returns badge objects sorted by priority.
- `on_sale` condition is a supported badge trigger in the Phase 8 badge system — see [`20-PHASE-8-IMPLEMENTATION-NOTES.md`](20-PHASE-8-IMPLEMENTATION-NOTES.md).
- `_resq_badge_label` and `_resq_badge_type` meta support custom badge copy.
- WooCommerce has native sale price and scheduled sale date fields.

**What's missing:**
- Process document for scheduling sales: how the owner sets sale start/end dates in WP Admin without touching the importer, and what catalog-import behavior is on re-import (does it overwrite manually-set sale prices?).
- Claim-safe sale copy rules — "Sale" is safe; "Lowest Price Ever," "Limited Time Only (when it isn't)," and similar urgency copy may not be — define the allowed set.
- Confirm bundle savings display shows correctly on bundle PDPs and gateway cards (already computed as `bundle_savings` in Phase 8 data; verify render path).

**Dependencies / blockers:** Owner decision on sale scheduling process. Compliance review of any urgency/scarcity copy per [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md).

**Suggested phase:** Post-launch phase 1.

---

### D1 — Customer Account Dashboard

**What it is:** A useful My Account experience — routine history, reorder shortcuts, and relevant account content beyond the WooCommerce defaults.

**Already built:**
- My Account template shell (Phase 5).
- `resq_get_product_routines()` and routine data — reorder context can be derived from order history + routine membership.
- Plugin owns account endpoint registration hooks.

**What's missing:**
- Content strategy for the dashboard: what does the owner want customers to see first? Order history, active routines, saved routines, loyalty points (if added)?
- Custom account endpoint for "My Routines" — endpoint registration in plugin, template in theme.
- Reorder shortcut: a "reorder" button on past orders that re-adds items to cart. WooCommerce has this natively in some versions; confirm or implement.
- Account copy review for claim-safe language per [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md).

**Dependencies / blockers:** Owner decision on dashboard content priorities.

**Suggested phase:** Post-launch phase 1.

---

### D2 — Email Automation Triggers

**What it is:** Post-purchase routine completion emails, reorder reminders, and abandoned cart recovery.

**Already built:**
- WooCommerce fires hooks for order status transitions, account events, and email sends.
- `resq_get_recommended_routine_addons()` provides the data payload for routine-aware reorder nudges.
- Plugin owns WooCommerce email hooks.

**What's missing:**
- ESP/CRM decision (see Open Decisions table). This blocks all personalized email work.
- If using WooCommerce native emails + an extension: identify which extension handles abandoned cart and automated sequences (AutomateWoo is the WooCommerce-native choice; Klaviyo, Mailchimp, and ActiveCampaign each have WooCommerce integrations with varying capability).
- Email template designs — must match brand tokens and pass claim review.
- CBD email caution: confirm ESP terms of service for sending CBD-related transactional and marketing emails. Some ESPs restrict CBD sender accounts.
- Routine completion nudge wiring: connect `resq_get_recommended_routine_addons()` output to a timed post-purchase trigger (e.g., "It's been 30 days — time for step 2").

**Dependencies / blockers:** ESP/CRM decision must come first. ESP terms review for CBD. Owner approval of email copy before sending.

**Suggested phase:** Post-launch phase 1.

---

### D3 — CRM Integration

**What it is:** Connecting order and customer data to a CRM for segmentation and lifecycle marketing.

**Already built:**
- WooCommerce customer and order data is available via WC REST API and native hooks.

**What's missing:**
- ESP/CRM platform decision (see Open Decisions table).
- Integration method: native WooCommerce extension vs. Zapier/Make bridge vs. direct API.
- Data mapping: which fields sync (routine membership, CBD vs. standard purchaser, audience, concern)?
- CBD data handling: confirm the chosen CRM permits storing CBD purchase context for marketing segmentation — terms vary.

**Dependencies / blockers:** ESP/CRM decision. CBD-specific terms review for chosen platform.

**Suggested phase:** Post-launch phase 1.

---

### D4 — Routine Completion Nudge Emails

**What it is:** Triggered emails using `resq_get_recommended_routine_addons()` data to prompt "time to reorder step 2 of your routine" messages.

**Already built:**
- `resq_get_recommended_routine_addons( int $product_id )` returns step-aware addon suggestions.
- Routine step order is stored per product.
- Plugin owns WooCommerce post-purchase hooks.

**What's missing:**
- ESP trigger integration (blocked on D2/D3).
- Timing logic: "N days after purchase of step 1, send step 2 nudge." N is a product-level or routine-level setting — not yet in schema. Add `_resq_routine_step_followup_days` to schema when this feature is scheduled.
- Email content template with claim-safe routine step copy.

**Dependencies / blockers:** D2 (email automation) and D3 (CRM) must be decided first.

**Suggested phase:** Post-launch phase 1 (after D2 and D3).

---

### E1 — Product Reviews Strategy

**What it is:** A defined approach to product reviews — WooCommerce native or a dedicated reviews plugin — with an upgrade path documented.

**Already built:**
- WooCommerce native reviews are available by default on any PDP.
- Theme PDP template shell (Phase 5) includes the WooCommerce review tab slot.

**What's missing:**
- Owner decision: native WooCommerce reviews vs. a dedicated platform.
  - **Native WooCommerce** — zero cost, no additional integration, limited moderation and syndication. Fine for launch.
  - **Judge.me** — free tier available, photo reviews, verified buyer badges, good WooCommerce integration.
  - **Okendo** — stronger UGC tools, higher cost, better for post-launch scale.
  - **Yotpo** — full suite including loyalty; highest cost; overkill for launch.
- If staying native: confirm theme review slot renders correctly and star ratings are accessible.
- If upgrading post-launch: document the migration path (native reviews can be exported; platform lock-in is a real concern).
- CBD review caution: some review platforms moderate or flag CBD-related health claim language in reviews — check platform policies before launch.

**Dependencies / blockers:** Owner decision on platform. Review copy must not introduce unapproved health claims (see [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md)).

**Suggested phase:** Post-launch phase 1 (strategy); native is acceptable at launch.

---

### E2 — Trust Badges

**What it is:** Claim-safe trust indicators on PDP and checkout — e.g., satisfaction guarantee, secure checkout, free returns if offered.

**Already built:**
- Compliance notice display slots exist across surfaces (Phase 5/8).
- Theme owns display slots; plugin determines which notices render.

**What's missing:**
- Template slots specifically for trust badges on PDP (below add-to-cart) and checkout (above payment block). Identify which existing template parts accommodate this vs. which need new slots.
- Badge asset production (icons/SVG).
- Copy review — "money-back guarantee," "secure checkout," and "free shipping" claims must match actual policy.

**Dependencies / blockers:** Return/refund policy (G4) must be finalized before guarantee copy is published. Shipping policy (see Group C) affects free-shipping badge.

**Suggested phase:** Post-launch phase 1.

---

## Post-Launch Phase 2 — Fulfillment and Operations

### C1 — Fulfillment Model Decision

**What it is:** Establishing whether the owner fulfills in-house or via a 3PL, which determines where label creation and tracking live.

**Already built:**
- WooCommerce order management is native and functional.
- Orders have status hooks that can trigger external calls.

**What's missing:**
- Owner decision: in-house fulfillment (owner prints labels from WP Admin or a separate tool) vs. 3PL (warehouse picks, packs, ships; WC order data syncs to 3PL system)?
- This decision gates all of C2 and C3.

**Dependencies / blockers:** Owner operational decision. Must precede any shipping integration work.

**Suggested phase:** Post-launch phase 2 (decision should be made before launch even if implementation follows).

---

### C2 — Shipping Label Creation

**What it is:** Integration with a shipping carrier API to generate labels from WP Admin or an external tool.

**Already built:**
- WooCommerce order data (address, weight, dimensions) is available via WC APIs.

**What's missing:**
- Fulfillment model decision (C1) required first.
- If in-house: carrier integration decision.
  - **ShipStation** — multi-carrier, strong WooCommerce integration, fee per shipment or subscription.
  - **Shippo** — pay-per-label, good API, WooCommerce plugin available.
  - **EasyPost** — developer-friendly API, no built-in WooCommerce plugin (requires custom integration).
  - **Direct carrier APIs** (USPS, UPS, FedEx) — most complex to maintain; only if carrier-specific discounts justify it.
- CBD shipping carrier restrictions: USPS prohibits shipping hemp/CBD products; UPS and FedEx policies have changed. Document which carriers are permitted before label integration is built — the carrier choice affects the entire integration.

**Dependencies / blockers:** C1 (fulfillment model). Carrier policy review for CBD is blocking — do not build label integration until permitted carriers are confirmed.

**Suggested phase:** Post-launch phase 2.

---

### C3 — Shipment Tracking

**What it is:** Customer-facing tracking number delivery via WooCommerce order status email and/or an order tracking page.

**Already built:**
- WooCommerce order status emails exist natively.
- Order status hooks (`woocommerce_order_status_changed`) can inject tracking data.

**What's missing:**
- Carrier integration (C2) must be in place first.
- Tracking number storage: WooCommerce doesn't have a native tracking field; requires either a plugin (Shipment Tracking by WooCommerce, Advanced Shipment Tracking) or a custom meta key on the order.
- Customer-facing tracking page: either link to carrier tracking URL or embed a tracking widget.
- Email template addition: tracking number block in the "Completed" order email.

**Dependencies / blockers:** C2 (label creation / carrier integration).

**Suggested phase:** Post-launch phase 2.

---

### C4 — CBD Shipping Restrictions Documentation

**What it is:** A documented carrier permit list and a mechanism to warn or block at checkout if a shipping destination or chosen carrier is incompatible with CBD shipment.

**Already built:**
- Plugin owns checkout validation hooks.
- `_resq_compliance_zone` is available per cart item.

**What's missing:**
- Legal/carrier research: current USPS, UPS, FedEx, and regional carrier policies for hemp-derived CBD (policies change; verify at implementation time).
- Checkout validation hook that checks whether any cart item has `compliance_zone = cbd` and the selected shipping method uses a prohibited carrier.
- Owner-supplied list of approved carriers.

**Dependencies / blockers:** Legal/carrier research. Owner confirmation of approved carriers. Do not implement until C1 and C2 are resolved.

**Suggested phase:** Post-launch phase 2.

---

### G1 — WP Admin Product Management Runbook

**What it is:** Documentation of what's safe to edit in WP Admin vs. what must go through `wp resq-catalog import`, so the owner can manage the catalog without breaking plugin-managed data.

**Already built:**
- `wp resq-catalog import` is the authoritative catalog importer — see [`19-CATALOG-IMPORT-NOTES.md`](19-CATALOG-IMPORT-NOTES.md).
- Plugin meta keys are documented in `11-PLUGIN-DATA-SCHEMA.md`.
- WooCommerce native fields (price, stock, description) are safe to edit via Admin.

**What's missing:**
- A clear table for the owner: "safe to edit in Admin" vs. "edit via CSV + re-import" vs. "edit via WP-CLI only."
- Risk documentation: what happens if the owner edits a `_resq_*` meta key directly in Admin vs. through the importer?
- Plugin admin field UI (deferred in Phase 2B) — if present by this phase, document which fields are editable and which are import-only.

**Dependencies / blockers:** None blocking; can be written after Phase 9 validation.

**Suggested phase:** Post-launch phase 2.

---

### G2 — Inventory and Stock Management

**What it is:** Low-stock notifications and a reorder workflow for the owner.

**Already built:**
- WooCommerce has native low-stock notifications (WooCommerce → Settings → Products → Inventory).
- Stock is managed natively per product/variation.

**What's missing:**
- Owner configuration: set low-stock threshold per product or globally.
- Reorder point strategy: does the owner want email notifications, a dashboard widget, or integration with a supplier ordering system?
- If 3PL is used (see C1), stock sync between the 3PL and WooCommerce may be needed — this is a significant integration and should be scoped separately.

**Dependencies / blockers:** Fulfillment model decision (C1) affects stock sync requirements.

**Suggested phase:** Post-launch phase 2.

---

## Post-Launch Phase 3 — Subscriptions and Advanced Commerce

### B3 — Subscribe and Save

**What it is:** A subscription pricing tier (e.g., 10–15% off recurring orders) for eligible products.

**Already built:**
- Nothing — this is intentionally deferred.
- WooCommerce supports subscriptions via extensions only (not native).

**What's missing:**
- Payment processor confirmation: **do not build until the payment processor explicitly permits recurring CBD charges.** Stripe's standard merchant account prohibits CBD subscriptions; CBD-friendly processors (Authorize.Net with a CBD merchant account, PayKings, Pinwheel) vary in subscription support.
- Subscription plugin decision once processor is confirmed:
  - **WooCommerce Subscriptions** — official, most compatible, significant cost (~$279/year).
  - **YITH WooCommerce Subscriptions** — lower cost, fewer third-party integrations.
  - **Sumo Subscriptions** — lifetime license option, active development.
  - Custom build — only if none of the above integrate with the confirmed processor.
- Product eligibility list: not all SKUs should be subscription-eligible (CBD, bundles, and one-time-use items need case-by-case owner decisions).
- Subscription-specific compliance review: recurring CBD charge disclosures may have additional FTC requirements.

**Dependencies / blockers:** Payment processor confirmed + subscription support verified. This is a hard prerequisite — do not prototype or design until resolved.

**Suggested phase:** Post-launch phase 3.

---

### D5 — Loyalty and Repeat Purchase Program

**What it is:** A points or rewards program for repeat customers.

**Already built:**
- Nothing — captured as post-launch consideration only.

**What's missing:**
- Owner decision on whether loyalty is in scope and what form it takes (points, tiers, referral).
- Plugin decision: WooCommerce Points and Rewards (official extension), YITH WooCommerce Points and Rewards, or a third-party platform (Smile.io, LoyaltyLion).
- CBD-specific caution: loyalty point accumulation on CBD purchases may affect payment processor terms — confirm before implementing.

**Dependencies / blockers:** Owner decision. Payment processor terms.

**Suggested phase:** Post-launch phase 3.

---

### F2 — Performance Monitoring Baseline

**What it is:** Core Web Vitals baseline measurement, image optimization strategy, and caching layer decisions.

**Already built:**
- `prefers-reduced-motion` defaults (Phase 4).
- No product images in repo — image optimization strategy is deferred until real images exist.
- [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md) notes: avoid layout shift on PLP/PDP images, defer non-critical JS.

**What's missing:**
- Real product images (none in catalog yet). Image optimization strategy (WebP conversion, lazy loading, `srcset`) cannot be fully validated without them.
- CLS measurement on PDP: image dimensions must be set before the image loads to prevent layout shift.
- Caching layer decision: object cache (Redis/Memcached via hosting), page cache (WP Rocket, W3 Total Cache, host-native), CDN (Cloudflare, Bunny.net, host-native). Decision depends on hosting environment.
- Core Web Vitals baseline run (Lighthouse, PageSpeed Insights, or CrUX) once real content is in place.
- Woo asset dequeue strategy — [`05-COMPLIANCE-RULES.md`](05-COMPLIANCE-RULES.md) says "dequeue only after measuring impact."

**Dependencies / blockers:** Real product images and hosting environment must be known before optimization work is meaningful.

**Suggested phase:** Post-launch phase 3 (full optimization); Phase 10 covers baseline measurement and blockers.

---

### F3 — A/B Testing Surface

**What it is:** Identified test points for future experiments — hero messaging, CTA copy, bundle presentation, routine ladder order.

**Already built:**
- Not in current scope. Captured for awareness.

**What's missing:**
- Owner decision on A/B testing investment (tools have cost: Optimizely, VWO, or Google Optimize successor).
- Integration approach that doesn't break Woo cart/checkout nonces or compliance notice rendering.
- CBD copy experiment caution: A/B testing CBD claim language is high risk without legal review of each variant.

**Dependencies / blockers:** Owner decision and budget. Not a Phase 10/11 concern.

**Suggested phase:** Post-launch phase 3 or later.

---

## Open Decisions Required Before Building

The following decisions block one or more features. Nothing in those feature groups should be built until the decision is made and documented.

| # | Decision | Blocks | Who decides | Risk if deferred |
|---|---|---|---|---|
| OD-1 | **Payment processor** — which processor is confirmed for CBD transactions, and does it permit subscriptions and recurring charges? | Age gate level (A4), Subscribe & Save (B3), CBD cart isolation enforcement (A2), loyalty (D5), conversion tracking (F1 re: CBD pixels) | Owner + payment processor | Building subscription or age gate infrastructure against the wrong processor wastes the work |
| OD-2 | **ESP/CRM platform** — which email service provider and/or CRM is used? | Email automation (D2), CRM integration (D3), routine nudges (D4) | Owner | All email automation work is blocked; choosing later forces a migration |
| OD-3 | **Fulfillment model** — in-house or 3PL? | Shipping label creation (C2), tracking (C3), stock sync (G2) | Owner | Label integration is built for the wrong model |
| OD-4 | **CBD shipping carriers** — which carriers are permitted for CBD shipments at implementation time? | Label integration (C2), checkout carrier filter (C4) | Owner + legal/carrier research | Labels built for a carrier that later bans CBD shipments |
| OD-5 | **Age gate depth** — soft cookie gate or third-party verification service? | Age gate (A4) | Owner + payment processor terms | Soft gate rejected by processor post-launch |
| OD-6 | **CBD state restriction list** — which states are restricted? | State restriction logic (A3) | Owner + legal | Selling into restricted states creates liability |
| OD-7 | **Review platform** — native WooCommerce or a dedicated reviews plugin? | Reviews strategy (E1) | Owner | Migration from native is possible but disruptive |
| OD-8 | **Analytics consent depth** — CCPA only, or full GDPR? | Cookie consent (H3), conversion tracking (F1) | Owner + legal | Tags that fire without consent create liability |
| OD-9 | **Subscribe & Save product eligibility** — which SKUs are eligible? | Subscribe & Save (B3) | Owner | Over-including CBD or bundles may conflict with processor terms |
| OD-10 | **Tax nexus states and CBD tax class** — which states, which rates? | Tax configuration (G3) | Owner + accountant | Operating without correct tax configuration creates compliance liability |

---

## Architecture Notes — What's Already Built

This section exists so developers don't re-invent what's in place. Check these before estimating or designing any feature in this doc.

### Plugin helpers available today

All 19 storefront helpers are live and return real data — see [`12-PLUGIN-HELPER-CONTRACTS.md`](12-PLUGIN-HELPER-CONTRACTS.md) and [`01-THEME-PLUGIN-CONTRACT.md`](01-THEME-PLUGIN-CONTRACT.md) for signatures. Notably:

- `resq_is_cbd_product()` and `resq_requires_compliance_notice()` — the foundation for age gates, cart isolation, and state restriction checks.
- `resq_can_cross_sell_products()` — already gates cross-sell safety; cart isolation (A2) extends this to add-to-cart validation.
- `resq_get_recommended_routine_addons()` — the data source for routine nudge emails (D4); no new schema work needed for the data layer.
- `resq_get_product_badges()` — sale badge (B2) is already a supported trigger; no new badge infrastructure needed.

### Meta schema hooks to extend

When any feature below requires a new meta key, add it to [`11-PLUGIN-DATA-SCHEMA.md`](11-PLUGIN-DATA-SCHEMA.md) and the ownership matrix in [`01-THEME-PLUGIN-CONTRACT.md`](01-THEME-PLUGIN-CONTRACT.md) before writing any PHP. Keys identified in this doc that are not yet in schema:

- `_resq_coa_url` — COA document URL (A1)
- `_resq_thc_disclosure` — THC percentage disclosure (A1)
- `_resq_routine_step_followup_days` — reorder nudge timing (D4)

### Feature flags

`resq_core_features` option map is the toggle surface for features that need a soft enable/disable (age gate, cart isolation enforcement, state restriction logic). Add flags there rather than constants.

### Theme slot conventions

New compliance and trust slots follow the pattern established in Phase 5/8: `template-parts/compliance/` for compliance content, `template-parts/product/` for PDP blocks. Don't create new top-level template directories.

### Theme/plugin boundary reminder

The boundary in [`01-THEME-PLUGIN-CONTRACT.md`](01-THEME-PLUGIN-CONTRACT.md) applies to all features here without exception. Any new feature that introduces a new data decision (restriction logic, eligibility flag, tracking config) lives in the plugin. Any new surface (age gate modal, trust badge slot, tracking nudge template) lives in the theme. No exceptions without an ADR.
