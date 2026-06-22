<?php
/**
 * Infrastructure helper functions.
 *
 * These are the plugin-internal helpers prefixed resq_core_*.
 * Contracts: docs/01-THEME-PLUGIN-CONTRACT.md, docs/12-PLUGIN-HELPER-CONTRACTS.md.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_core' ) ) {
	/**
	 * Return the plugin singleton instance.
	 *
	 * @return ResQ_Core_Plugin
	 */
	function resq_core(): ResQ_Core_Plugin {
		return ResQ_Core_Plugin::instance();
	}
}

if ( ! function_exists( 'resq_core_get_option' ) ) {
	/**
	 * Read a plugin option with an optional default.
	 *
	 * Supports dot-notation for nested arrays, e.g.
	 * `resq_core_compliance.cbd_isolation_enabled`.
	 *
	 * @param string $key     Option key.
	 * @param mixed  $default Fallback value.
	 * @return mixed
	 */
	function resq_core_get_option( string $key, mixed $default = null ): mixed {
		return ResQ_Core_Options::get( $key, $default );
	}
}

if ( ! function_exists( 'resq_core_feature_enabled' ) ) {
	/**
	 * Check whether a feature flag is enabled.
	 *
	 * @param string $feature Feature key from resq_core_features.
	 * @return bool
	 */
	function resq_core_feature_enabled( string $feature ): bool {
		return ResQ_Core_Options::feature_enabled( $feature );
	}
}

if ( ! function_exists( 'resq_core_is_active' ) ) {
	/**
	 * Whether the ResQ Core plugin has fully initialized.
	 *
	 * Theme templates should use this or function_exists() before
	 * calling any resq_* helper.
	 *
	 * @return bool
	 */
	function resq_core_is_active(): bool {
		if ( ! class_exists( 'ResQ_Core_Plugin' ) ) {
			return false;
		}
		return ResQ_Core_Plugin::instance()->is_initialized();
	}
}

if ( ! function_exists( 'resq_core_get_badge_data' ) ) {
	/**
	 * Return badge data for a product.
	 *
	 * Phase 2B stub — returns null. Real implementation in Phase 3.
	 * Theme templates should prefer resq_get_product_badges().
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return array|null
	 */
	function resq_core_get_badge_data( int $product_id ): ?array {
		if ( $product_id <= 0 ) {
			return null;
		}
		return null;
	}
}

if ( ! function_exists( 'resq_core_get_cross_sells' ) ) {
	/**
	 * Return curated cross-sell product IDs.
	 *
	 * Phase 2B stub — returns empty array. Real implementation in Phase 3.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return int[]
	 */
	function resq_core_get_cross_sells( int $product_id ): array {
		if ( $product_id <= 0 ) {
			return array();
		}
		return array();
	}
}

if ( ! function_exists( 'resq_core_get_compliance_notices' ) ) {
	/**
	 * Return compliance notices for a given context and product.
	 *
	 * Phase 2B stub — returns empty array. Real implementation in Phase 3.
	 *
	 * @param string $context    Display context: pdp, category, cart, checkout, card.
	 * @param int    $product_id Optional product ID.
	 * @return array[]
	 */
	function resq_core_get_compliance_notices( string $context, int $product_id = 0 ): array {
		return array();
	}
}
