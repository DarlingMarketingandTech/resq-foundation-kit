<?php
/**
 * Compliance surfaces — age gate (A4) and cookie consent (H3).
 *
 * The plugin owns the feature flags and CBD-lane data; the theme owns these
 * surfaces and the routing decision of which pages count as CBD context. All
 * chrome copy here is neutral (no health/CBD claims); legal disclaimer copy
 * stays in plugin options for owner sign-off.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_theme_feature_on' ) ) {
	/**
	 * Whether a plugin compliance feature flag is enabled (safe when plugin off).
	 *
	 * @param string $feature Feature key.
	 * @param bool   $default Fallback when the plugin is unavailable.
	 * @return bool
	 */
	function resq_theme_feature_on( string $feature, bool $default = false ): bool {
		if ( ! function_exists( 'resq_core_feature_enabled' ) ) {
			return $default;
		}
		return resq_core_feature_enabled( $feature );
	}
}

if ( ! function_exists( 'resq_theme_is_cbd_context' ) ) {
	/**
	 * Whether the current request is a CBD surface (gateway, PDP, or category).
	 *
	 * @return bool
	 */
	function resq_theme_is_cbd_context(): bool {
		if ( is_page_template( 'page-gateway-cbd.php' ) ) {
			return true;
		}

		if ( function_exists( 'is_product' ) && is_product() ) {
			$product_id = get_queried_object_id();
			if ( $product_id && function_exists( 'resq_is_cbd_product' ) && resq_is_cbd_product( (int) $product_id ) ) {
				return true;
			}
		}

		if ( function_exists( 'is_product_category' ) && is_product_category() ) {
			$term = get_queried_object();
			if ( $term instanceof WP_Term && false !== strpos( $term->slug, 'cbd' ) ) {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'resq_theme_has_analytics_consent' ) ) {
	/**
	 * Whether the visitor has granted analytics/cookie consent.
	 *
	 * Analytics tags (added in Phase 11 F1) must check this before firing when
	 * consent is required. Returns true when the consent feature is disabled so
	 * non-consent regions are not blocked.
	 *
	 * @return bool
	 */
	function resq_theme_has_analytics_consent(): bool {
		if ( function_exists( 'resq_core_is_local_sandbox' ) && resq_core_is_local_sandbox() ) {
			return true;
		}

		if ( ! resq_theme_feature_on( 'cookie_consent', false ) ) {
			return true;
		}
		return isset( $_COOKIE['resq_cookie_consent'] ) && 'accepted' === sanitize_key( wp_unslash( $_COOKIE['resq_cookie_consent'] ) );
	}
}

if ( ! function_exists( 'resq_theme_compliance_surfaces_enabled' ) ) {
	/**
	 * Whether age gate / cookie consent surfaces should render.
	 *
	 * Suppressed on local sandbox so CBD and other pages are immediately browsable
	 * during development; production keeps full gating for owner review.
	 *
	 * @return bool
	 */
	function resq_theme_compliance_surfaces_enabled(): bool {
		if ( function_exists( 'resq_core_is_local_sandbox' ) && resq_core_is_local_sandbox() ) {
			return false;
		}

		return true;
	}
}

add_action( 'wp_enqueue_scripts', 'resq_theme_enqueue_compliance_assets' );
add_action( 'wp_footer', 'resq_theme_render_compliance_surfaces' );

/**
 * Enqueue age-gate and cookie-consent scripts when their features are active.
 */
function resq_theme_enqueue_compliance_assets(): void {
	if ( ! resq_theme_compliance_surfaces_enabled() ) {
		return;
	}

	$age_gate_active = resq_theme_feature_on( 'age_gate', false )
		&& resq_theme_is_cbd_context()
		&& ! isset( $_COOKIE['resq_age_confirmed'] );

	if ( $age_gate_active ) {
		wp_enqueue_script(
			'resq-theme-age-gate',
			resq_theme_get_asset_url( 'assets/js/age-gate.js' ),
			array(),
			RESQ_THEME_VERSION,
			true
		);
		wp_localize_script(
			'resq-theme-age-gate',
			'resqAgeGate',
			array(
				'cookieName' => 'resq_age_confirmed',
				'cookieDays' => 30,
				'exitUrl'    => esc_url( home_url( '/' ) ),
			)
		);
	}

	if ( resq_theme_feature_on( 'cookie_consent', false ) && ! isset( $_COOKIE['resq_cookie_consent'] ) ) {
		wp_enqueue_script(
			'resq-theme-cookie-consent',
			resq_theme_get_asset_url( 'assets/js/cookie-consent.js' ),
			array(),
			RESQ_THEME_VERSION,
			true
		);
		wp_localize_script(
			'resq-theme-cookie-consent',
			'resqCookieConsent',
			array(
				'cookieName' => 'resq_cookie_consent',
				'cookieDays' => 180,
			)
		);
	}
}

/**
 * Inject the age gate and cookie consent surfaces into the footer.
 */
function resq_theme_render_compliance_surfaces(): void {
	if ( ! resq_theme_compliance_surfaces_enabled() ) {
		return;
	}

	if (
		resq_theme_feature_on( 'age_gate', false )
		&& resq_theme_is_cbd_context()
		&& ! isset( $_COOKIE['resq_age_confirmed'] )
	) {
		$min_age = function_exists( 'resq_core_get_option' )
			? (int) resq_core_get_option( 'resq_core_compliance.age_gate_min_age', 21 )
			: 21;

		resq_theme_template_part(
			'compliance/age-gate-modal',
			'',
			array( 'min_age' => $min_age > 0 ? $min_age : 21 )
		);
	}

	if ( resq_theme_feature_on( 'cookie_consent', false ) && ! isset( $_COOKIE['resq_cookie_consent'] ) ) {
		resq_theme_template_part( 'compliance/cookie-consent' );
	}
}
