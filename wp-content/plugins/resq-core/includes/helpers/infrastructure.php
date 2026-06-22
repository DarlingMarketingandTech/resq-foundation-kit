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
	 * Return compliance notices for a given context and product.
	 *
	 * @param string $context    Display context.
	 * @param int    $product_id Optional product ID.
	 * @return array[]
	 */
	function resq_core_get_compliance_notices( string $context, int $product_id = 0 ): array {
		if ( $product_id <= 0 ) {
			return array();
		}

		if ( ! resq_requires_compliance_notice( $product_id, $context ) ) {
			return array();
		}

		$zone        = resq_get_compliance_zone( $product_id );
		$notice_text = resq_core_get_option( 'resq_core_compliance.notice_text', array() );
		$text        = '';

		if ( is_array( $notice_text ) && ! empty( $notice_text[ $zone ] ) ) {
			$text = (string) $notice_text[ $zone ];
		}

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
