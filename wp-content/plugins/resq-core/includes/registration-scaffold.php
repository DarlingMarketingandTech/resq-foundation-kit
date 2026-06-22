<?php
/**
 * Registration scaffolds — Phase 3 taxonomy, CPT, and meta registration.
 *
 * PHASE 2B: All registrations are COMMENTED OUT. Do not uncomment until
 * Phase 3 work begins and data schema in docs/11-PLUGIN-DATA-SCHEMA.md
 * is locked for implementation.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/*
 * ══════════════════════════════════════════════════
 *  PHASE 3 — TAXONOMY REGISTRATION
 *  Reference: docs/11-PLUGIN-DATA-SCHEMA.md § A
 * ══════════════════════════════════════════════════
 */

/*
 * resq_audience — non-hierarchical product taxonomy.
 * Gateway routing, product cards, PLP filters.
 * Intended terms (examples): human, pet.
 *
 * function resq_register_taxonomy_audience(): void {
 *     register_taxonomy( 'resq_audience', 'product', array(
 *         'labels'            => array(
 *             'name'          => __( 'Audiences', 'resq-core' ),
 *             'singular_name' => __( 'Audience', 'resq-core' ),
 *         ),
 *         'hierarchical'      => false,
 *         'public'            => true,
 *         'show_in_rest'      => true,
 *         'show_admin_column' => true,
 *         'rewrite'           => array( 'slug' => 'audience' ),
 *     ) );
 * }
 * add_action( 'init', 'resq_register_taxonomy_audience' );
 */

/*
 * resq_concern — hierarchical product taxonomy.
 * Problem-led discovery, concern landing pages, filters.
 * Intended terms (examples): pet-topical > hot-spots, human-skincare > dry-skin.
 *
 * function resq_register_taxonomy_concern(): void {
 *     register_taxonomy( 'resq_concern', 'product', array(
 *         'labels'            => array(
 *             'name'          => __( 'Concerns', 'resq-core' ),
 *             'singular_name' => __( 'Concern', 'resq-core' ),
 *         ),
 *         'hierarchical'      => true,
 *         'public'            => true,
 *         'show_in_rest'      => true,
 *         'show_admin_column' => true,
 *         'rewrite'           => array( 'slug' => 'concern' ),
 *     ) );
 * }
 * add_action( 'init', 'resq_register_taxonomy_concern' );
 */

/*
 * resq_ingredient — non-hierarchical product taxonomy.
 * Ingredient filters, Learn-to-shop bridges, PDP blocks.
 * Intended terms (examples): manuka-honey, aloe-vera, coconut-oil.
 *
 * function resq_register_taxonomy_ingredient(): void {
 *     register_taxonomy( 'resq_ingredient', 'product', array(
 *         'labels'            => array(
 *             'name'          => __( 'Ingredients', 'resq-core' ),
 *             'singular_name' => __( 'Ingredient', 'resq-core' ),
 *         ),
 *         'hierarchical'      => false,
 *         'public'            => true,
 *         'show_in_rest'      => true,
 *         'show_admin_column' => false,
 *         'rewrite'           => array( 'slug' => 'ingredient' ),
 *     ) );
 * }
 * add_action( 'init', 'resq_register_taxonomy_ingredient' );
 */

/*
 * resq_product_role — non-hierarchical product taxonomy.
 * Routine ladder labels, shelf grouping.
 * Intended terms (examples): cleanser, treatment, restorer, moisturizer.
 *
 * function resq_register_taxonomy_product_role(): void {
 *     register_taxonomy( 'resq_product_role', 'product', array(
 *         'labels'            => array(
 *             'name'          => __( 'Product Roles', 'resq-core' ),
 *             'singular_name' => __( 'Product Role', 'resq-core' ),
 *         ),
 *         'hierarchical'      => false,
 *         'public'            => true,
 *         'show_in_rest'      => true,
 *         'show_admin_column' => false,
 *         'rewrite'           => array( 'slug' => 'product-role' ),
 *     ) );
 * }
 * add_action( 'init', 'resq_register_taxonomy_product_role' );
 */

/*
 * resq_compliance_zone — non-hierarchical product taxonomy.
 * CBD isolation, notice selection, cross-sell restrictions.
 * Intended terms: standard, cbd, baby, pet-health.
 *
 * Naming note: `baby` is the compliance-zone slug used in data storage,
 * option keys, and helper returns. `baby-infant-care` and
 * `Baby & Infant Care` are storefront route/display language only.
 *
 * function resq_register_taxonomy_compliance_zone(): void {
 *     register_taxonomy( 'resq_compliance_zone', 'product', array(
 *         'labels'            => array(
 *             'name'          => __( 'Compliance Zones', 'resq-core' ),
 *             'singular_name' => __( 'Compliance Zone', 'resq-core' ),
 *         ),
 *         'hierarchical'      => false,
 *         'public'            => false,
 *         'show_in_rest'      => true,
 *         'show_admin_column' => true,
 *         'rewrite'           => false,
 *     ) );
 * }
 * add_action( 'init', 'resq_register_taxonomy_compliance_zone' );
 */

/*
 * ══════════════════════════════════════════════════
 *  PHASE 3 — CUSTOM POST TYPE REGISTRATION
 *  Reference: docs/11-PLUGIN-DATA-SCHEMA.md § B
 * ══════════════════════════════════════════════════
 */

/*
 * resq_routine — ordered regimen definitions.
 * No front-end archive. Admin-editable. Owns step data.
 *
 * function resq_register_cpt_routine(): void {
 *     register_post_type( 'resq_routine', array(
 *         'labels'       => array(
 *             'name'          => __( 'Routines', 'resq-core' ),
 *             'singular_name' => __( 'Routine', 'resq-core' ),
 *         ),
 *         'public'       => false,
 *         'show_ui'      => true,
 *         'show_in_rest' => true,
 *         'supports'     => array( 'title', 'editor', 'custom-fields' ),
 *         'menu_icon'    => 'dashicons-list-view',
 *     ) );
 * }
 * add_action( 'init', 'resq_register_cpt_routine' );
 */

/*
 * ══════════════════════════════════════════════════
 *  PHASE 3 — PRODUCT META REGISTRATION
 *  Reference: docs/11-PLUGIN-DATA-SCHEMA.md § C
 * ══════════════════════════════════════════════════
 *
 * The following meta keys will be registered via register_post_meta()
 * on the 'product' post type in Phase 3. Listed here for traceability.
 *
 * Product meta keys:
 *   _resq_canonical_product_id   — int, absint(), default 0
 *   _resq_compliance_flags       — array of string slugs (cbd, medical-adjacent, pet-health, baby, proof, donation)
 *   _resq_compliance_zone        — string slug (standard, cbd, baby, pet-health), default 'standard'
 *   _resq_routine_ids            — array of int (resq_routine CPT post IDs)
 *   _resq_routine_step_order     — int, optional step position hint
 *   _resq_primary_routine_id     — int, PDP ladder default routine
 *   _resq_bundle_product_ids     — array of {product_id, qty}
 *   _resq_fbt_product_ids        — array of int
 *   _resq_ingredient_profile     — array of {term_slug, label, descriptor, claim_safe}
 *   _resq_short_benefit_tags     — array of strings, max 5
 *   _resq_product_card_subtitle  — string
 *   _resq_gateway_featured       — array of string slugs (human, pet, bundles, cbd, learn)
 *   _resq_learn_links            — array of {post_id, label}
 *   _resq_donation_eligible      — bool (stored yes/no)
 *   _resq_badge_label            — string (retained from Phase 1)
 *   _resq_badge_type             — string (retained from Phase 1)
 *
 * Routine CPT meta keys:
 *   _resq_routine_steps                  — serialized array of step objects
 *   _resq_routine_bundle_target          — int (optional bundle product ID)
 *   _resq_routine_audience               — string (primary audience slug hint)
 *   _resq_routine_compliance_restrictions — serialized array
 *
 * Term/route meta keys:
 *   _resq_category_hero_image    — attachment ID
 *   _resq_category_intro         — string
 *   _resq_audience_type          — string (planning flag)
 *   _resq_compliance_category    — string
 *   _resq_canonical_targets      — array of product IDs
 *
 * Superseded keys (do not register):
 *   _resq_audience_ids  — use resq_audience taxonomy instead
 *   _resq_concern_ids   — use resq_concern taxonomy instead
 *
 * Derived (not stored):
 *   _resq_cbd_product               — computed from _resq_compliance_flags + zone
 *   _resq_requires_compliance_notice — computed from flags + zone + context
 */
