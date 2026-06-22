<?php
/**
 * Storefront helper function stubs.
 *
 * All 19 public helpers from docs/12-PLUGIN-HELPER-CONTRACTS.md.
 * Phase 2B: return empty-safe defaults only — no real data reads.
 * Phase 3 will replace stubs with live taxonomy/CPT/meta queries.
 *
 * Theme calls must be guarded with function_exists() or resq_core_is_active().
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/*
 * ──────────────────────────────────────────────────
 *  Product Intelligence
 * ──────────────────────────────────────────────────
 */

if ( ! function_exists( 'resq_get_product_audiences' ) ) {
	/**
	 * Return audience terms assigned to a product.
	 *
	 * @param int $product_id WooCommerce product or variation ID.
	 * @return array[] List of audience objects.
	 */
	function resq_get_product_audiences( int $product_id ): array {
		if ( $product_id <= 0 ) {
			return array();
		}
		return array();
	}
}

if ( ! function_exists( 'resq_get_product_concerns' ) ) {
	/**
	 * Return concern/problem terms for discovery and filters.
	 *
	 * @param int $product_id WooCommerce product or variation ID.
	 * @return array[] List of concern objects with parent context.
	 */
	function resq_get_product_concerns( int $product_id ): array {
		if ( $product_id <= 0 ) {
			return array();
		}
		return array();
	}
}

if ( ! function_exists( 'resq_get_product_routines' ) ) {
	/**
	 * Return routines that include this product.
	 *
	 * @param int $product_id WooCommerce product or variation ID.
	 * @return array[] List of routine summary objects.
	 */
	function resq_get_product_routines( int $product_id ): array {
		if ( $product_id <= 0 ) {
			return array();
		}
		return array();
	}
}

if ( ! function_exists( 'resq_get_routine_steps' ) ) {
	/**
	 * Return ordered steps for a routine.
	 *
	 * @param int $routine_id         resq_routine CPT post ID.
	 * @param int $current_product_id Optional current PDP product marker.
	 * @return array[] Ordered step objects.
	 */
	function resq_get_routine_steps( int $routine_id, int $current_product_id = 0 ): array {
		if ( $routine_id <= 0 ) {
			return array();
		}
		return array();
	}
}

if ( ! function_exists( 'resq_get_product_ingredient_profile' ) ) {
	/**
	 * Return claim-safe ingredient profile for PDP and Learn modules.
	 *
	 * @param int $product_id WooCommerce product or variation ID.
	 * @return array[] List of ingredient descriptor objects.
	 */
	function resq_get_product_ingredient_profile( int $product_id ): array {
		if ( $product_id <= 0 ) {
			return array();
		}
		return array();
	}
}

/*
 * ──────────────────────────────────────────────────
 *  Canonical Mapping
 * ──────────────────────────────────────────────────
 */

if ( ! function_exists( 'resq_get_canonical_product_id' ) ) {
	/**
	 * Resolve any source to a canonical WooCommerce product ID.
	 *
	 * Phase 2B: returns the source_id unchanged when source_type is
	 * 'product' and the value is a positive integer (self-canonical).
	 * All other cases return null until Phase 3 wiring.
	 *
	 * @param int|string $source_id   ID or route slug.
	 * @param string     $source_type Resolver context: product, variation,
	 *                                term, page, route, routine, bundle, learn.
	 * @return int|null Canonical product ID or null.
	 */
	function resq_get_canonical_product_id( int|string $source_id, string $source_type = 'product' ): ?int {
		if ( 'product' === $source_type && is_numeric( $source_id ) && (int) $source_id > 0 ) {
			return (int) $source_id;
		}
		return null;
	}
}

if ( ! function_exists( 'resq_resolve_product_context' ) ) {
	/**
	 * Resolve a page, term, route, or post into shopping context.
	 *
	 * Phase 2B: returns the empty-safe shape from docs/12.
	 *
	 * @param int|string $context_id   WP post ID, term ID, or route slug.
	 * @param string     $context_type page, term, route, learn, gateway.
	 * @return array Context object.
	 */
	function resq_resolve_product_context( int|string $context_id, string $context_type ): array {
		return array(
			'context_type'      => $context_type,
			'context_id'        => $context_id,
			'audience'          => '',
			'concerns'          => array(),
			'featured_products' => array(),
			'canonical_targets' => array(),
			'compliance_zone'   => 'standard',
			'filters'           => array(),
		);
	}
}

/*
 * ──────────────────────────────────────────────────
 *  Compliance
 * ──────────────────────────────────────────────────
 */

if ( ! function_exists( 'resq_is_cbd_product' ) ) {
	/**
	 * Whether a product is in the CBD-regulated lane.
	 *
	 * Phase 2B stub — always false. Derived from compliance flags/zone in Phase 3.
	 *
	 * @param int $product_id WooCommerce product or variation ID.
	 * @return bool
	 */
	function resq_is_cbd_product( int $product_id ): bool {
		if ( $product_id <= 0 ) {
			return false;
		}
		return false;
	}
}

if ( ! function_exists( 'resq_requires_compliance_notice' ) ) {
	/**
	 * Whether a notice slot must render for this product and context.
	 *
	 * Phase 2B stub — always false.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $context    pdp, category, cart, checkout, card.
	 * @return bool
	 */
	function resq_requires_compliance_notice( int $product_id, string $context = 'pdp' ): bool {
		if ( $product_id <= 0 ) {
			return false;
		}
		return false;
	}
}

if ( ! function_exists( 'resq_get_compliance_zone' ) ) {
	/**
	 * Return the compliance zone slug for structural isolation.
	 *
	 * Phase 2B stub — always 'standard'.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return string standard, cbd, baby, or pet-health.
	 */
	function resq_get_compliance_zone( int $product_id ): string {
		if ( $product_id <= 0 ) {
			return 'standard';
		}
		return 'standard';
	}
}

if ( ! function_exists( 'resq_can_cross_sell_products' ) ) {
	/**
	 * Whether target may be cross-sold from source product context.
	 *
	 * Phase 2B stub — returns false for safety (default deny).
	 * Phase 3 will implement zone/audience/flag checks.
	 *
	 * @param int $source_product_id Source product ID.
	 * @param int $target_product_id Target product ID.
	 * @return bool
	 */
	function resq_can_cross_sell_products( int $source_product_id, int $target_product_id ): bool {
		if ( $source_product_id <= 0 || $target_product_id <= 0 ) {
			return false;
		}
		return false;
	}
}

/*
 * ──────────────────────────────────────────────────
 *  Routine Commerce
 * ──────────────────────────────────────────────────
 */

if ( ! function_exists( 'resq_get_product_routine_ladder' ) ) {
	/**
	 * Return the primary routine ladder payload for PDP display.
	 *
	 * Phase 2B stub — returns empty array.
	 *
	 * @param int $product_id Current PDP product ID.
	 * @return array Ladder object or empty array.
	 */
	function resq_get_product_routine_ladder( int $product_id ): array {
		if ( $product_id <= 0 ) {
			return array();
		}
		return array();
	}
}

if ( ! function_exists( 'resq_get_recommended_routine_addons' ) ) {
	/**
	 * Return next-step or complete-kit suggestions for cart drawer.
	 *
	 * Phase 2B stub — returns empty array.
	 *
	 * @param int $product_id Product just added or in cart.
	 * @return array[] Suggestion objects.
	 */
	function resq_get_recommended_routine_addons( int $product_id ): array {
		if ( $product_id <= 0 ) {
			return array();
		}
		return array();
	}
}

if ( ! function_exists( 'resq_get_bundle_products' ) ) {
	/**
	 * Return products and quantities in a bundle/kit.
	 *
	 * Phase 2B stub — returns empty array.
	 *
	 * @param int $bundle_id WooCommerce bundle product ID.
	 * @return array[] Included product objects.
	 */
	function resq_get_bundle_products( int $bundle_id ): array {
		if ( $bundle_id <= 0 ) {
			return array();
		}
		return array();
	}
}

if ( ! function_exists( 'resq_get_frequently_bought_together' ) ) {
	/**
	 * Return FBT product IDs for PDP and cart surfaces.
	 *
	 * Phase 2B stub — returns empty array.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return int[] Canonical product IDs.
	 */
	function resq_get_frequently_bought_together( int $product_id ): array {
		if ( $product_id <= 0 ) {
			return array();
		}
		return array();
	}
}

/*
 * ──────────────────────────────────────────────────
 *  Presentation Helpers
 * ──────────────────────────────────────────────────
 */

if ( ! function_exists( 'resq_get_product_card_data' ) ) {
	/**
	 * Aggregate scannable product card payload for PLP/gateway shelves.
	 *
	 * Phase 2B stub — returns empty array. Phase 3 will merge Woo
	 * product data with plugin meta/taxonomy context.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return array Card data object or empty array.
	 */
	function resq_get_product_card_data( int $product_id ): array {
		if ( $product_id <= 0 ) {
			return array();
		}
		return array();
	}
}

if ( ! function_exists( 'resq_get_gateway_featured_products' ) ) {
	/**
	 * Return product IDs featured on a gateway page shelf.
	 *
	 * Phase 2B stub — returns empty array.
	 *
	 * @param string $gateway Slug: human, pet, bundles, cbd, learn.
	 * @return int[] Canonical product IDs, ordered.
	 */
	function resq_get_gateway_featured_products( string $gateway ): array {
		return array();
	}
}

if ( ! function_exists( 'resq_get_learn_links_for_product' ) ) {
	/**
	 * Return Learn guide links related to a product.
	 *
	 * Phase 2B stub — returns empty array.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return array[] Link objects.
	 */
	function resq_get_learn_links_for_product( int $product_id ): array {
		if ( $product_id <= 0 ) {
			return array();
		}
		return array();
	}
}

if ( ! function_exists( 'resq_get_product_badges' ) ) {
	/**
	 * Return badge objects for product card and PDP.
	 *
	 * Phase 2B stub — returns empty array. Phase 3 will combine
	 * product meta with default badge config rules.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return array[] Badge objects sorted by priority.
	 */
	function resq_get_product_badges( int $product_id ): array {
		if ( $product_id <= 0 ) {
			return array();
		}
		return array();
	}
}
