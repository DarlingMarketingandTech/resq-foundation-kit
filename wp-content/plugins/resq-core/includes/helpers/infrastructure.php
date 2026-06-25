<?php
/**
 * Infrastructure helper functions.
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
	 * @param int $product_id WooCommerce product ID.
	 * @return array|null
	 */
	function resq_core_get_badge_data( int $product_id ): ?array {
		if ( $product_id <= 0 ) {
			return null;
		}

		$badges = resq_get_product_badges( $product_id );
		return ! empty( $badges ) ? $badges[0] : null;
	}
}

if ( ! function_exists( 'resq_core_get_cross_sells' ) ) {
	/**
	 * Return curated cross-sell product IDs.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return int[]
	 */
	function resq_core_get_cross_sells( int $product_id ): array {
		if ( $product_id <= 0 ) {
			return array();
		}

		return resq_get_frequently_bought_together( $product_id );
	}
}

if ( ! function_exists( 'resq_core_get_compliance_notices' ) ) {
	/**
	 * Return compliance notices for a given context.
	 *
	 * Two lookup modes:
	 *  - Product mode ($product_id > 0): resolves the product's compliance zone and
	 *    returns its notice only when the product actually requires one.
	 *  - Zone mode ($product_id <= 0 and $zone provided): returns the configured
	 *    notice for a zone with no specific product — used by context-level slots
	 *    such as the CBD gateway disclaimer strip, which is scoped by zone, not by
	 *    an individual product.
	 *
	 * Returns an empty array when no notice copy is configured for the resolved
	 * zone, so a slot stays silent until the owner sets reviewed copy.
	 *
	 * @param string $context    Display context.
	 * @param int    $product_id Optional product ID (product mode).
	 * @param string $zone       Optional compliance zone slug (zone mode).
	 * @return array[]
	 */
	function resq_core_get_compliance_notices( string $context, int $product_id = 0, string $zone = '' ): array {
		if ( $product_id <= 0 ) {
			$zone = sanitize_key( $zone );
			if ( '' === $zone ) {
				return array();
			}

			return resq_core_build_zone_notice( $zone, $context );
		}

		if ( ! resq_requires_compliance_notice( $product_id, $context ) ) {
			return array();
		}

		return resq_core_build_zone_notice( resq_get_compliance_zone( $product_id ), $context );
	}
}

if ( ! function_exists( 'resq_core_build_zone_notice' ) ) {
	/**
	 * Build the notice payload for a compliance zone from configured copy.
	 *
	 * @param string $zone    Compliance zone slug.
	 * @param string $context Display context.
	 * @return array[] Single-notice array, or empty when no copy is set.
	 */
	function resq_core_build_zone_notice( string $zone, string $context ): array {
		$notice_text = resq_core_get_option( 'resq_core_compliance.notice_text', array() );

		$text = is_array( $notice_text ) && ! empty( $notice_text[ $zone ] )
			? (string) $notice_text[ $zone ]
			: '';

		if ( '' === $text ) {
			return array();
		}

		return array(
			array(
				'zone'    => $zone,
				'context' => $context,
				'text'    => $text,
			),
		);
	}
}
