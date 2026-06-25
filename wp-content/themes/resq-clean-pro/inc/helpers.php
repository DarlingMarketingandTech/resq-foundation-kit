<?php
/**
 * Theme helper functions.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_theme_get_asset_url' ) ) {
	/**
	 * Return a versioned theme asset URL.
	 *
	 * @param string $path Relative path from theme root, e.g. assets/css/tokens.css.
	 * @return string
	 */
	function resq_theme_get_asset_url( string $path ): string {
		$path = ltrim( $path, '/' );
		return add_query_arg( 'ver', RESQ_THEME_VERSION, RESQ_THEME_URI . '/' . $path );
	}
}

if ( ! function_exists( 'resq_theme_template_part' ) ) {
	/**
	 * Load a template part with optional args.
	 *
	 * @param string $slug Template slug under template-parts/.
	 * @param string $name Optional name suffix.
	 * @param array  $args Variables exposed in the part scope.
	 */
	function resq_theme_template_part( string $slug, string $name = '', array $args = array() ): void {
		if ( ! empty( $args ) ) {
			// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
			extract( $args, EXTR_SKIP );
		}

		$templates = array();
		if ( '' !== $name ) {
			$templates[] = "template-parts/{$slug}-{$name}.php";
		}
		$templates[] = "template-parts/{$slug}.php";

		$located = locate_template( $templates, false, false );
		if ( '' !== $located ) {
			require $located;
		}
	}
}

if ( ! function_exists( 'resq_theme_class' ) ) {
	/**
	 * Build a BEM-style class string.
	 *
	 * @param string $base       Base class name.
	 * @param array  $modifiers  Modifier suffixes appended as base--modifier.
	 * @return string
	 */
	function resq_theme_class( string $base, array $modifiers = array() ): string {
		$classes = array( $base );

		foreach ( $modifiers as $modifier ) {
			$modifier = sanitize_html_class( (string) $modifier );
			if ( '' !== $modifier ) {
				$classes[] = $base . '--' . $modifier;
			}
		}

		return implode( ' ', $classes );
	}
}

if ( ! function_exists( 'resq_theme_wc_active' ) ) {
	/**
	 * Whether WooCommerce is active.
	 *
	 * @return bool
	 */
	function resq_theme_wc_active(): bool {
		return class_exists( 'WooCommerce' ) && function_exists( 'WC' );
	}
}

if ( ! function_exists( 'resq_theme_get_cart_count' ) ) {
	/**
	 * Return cart item count for header display.
	 *
	 * @return int
	 */
	function resq_theme_get_cart_count(): int {
		if ( ! resq_theme_wc_active() || ! function_exists( 'WC' ) ) {
			return 0;
		}

		$woocommerce = WC();
		if ( ! $woocommerce || ! isset( $woocommerce->cart ) || ! $woocommerce->cart ) {
			return 0;
		}

		return (int) $woocommerce->cart->get_cart_contents_count();
	}
}

if ( ! function_exists( 'resq_theme_render_compliance_notices' ) ) {
	/**
	 * Render compliance notice slot via plugin helper when available.
	 *
	 * Pass $zone (with $product_id left at 0) for context-level slots that are
	 * scoped by compliance zone rather than a specific product — e.g. the CBD
	 * gateway disclaimer strip.
	 *
	 * @param string $context    Display context.
	 * @param int    $product_id Optional product ID (product-scoped slots).
	 * @param string $zone       Optional compliance zone slug (zone-scoped slots).
	 */
	function resq_theme_render_compliance_notices( string $context, int $product_id = 0, string $zone = '' ): void {
		if ( ! function_exists( 'resq_core_get_compliance_notices' ) ) {
			return;
		}

		$notices = resq_core_get_compliance_notices( $context, $product_id, $zone );
		if ( empty( $notices ) ) {
			return;
		}

		resq_theme_template_part(
			'compliance/notices',
			'',
			array(
				'notices' => $notices,
				'context' => $context,
			)
		);
	}
}

if ( ! function_exists( 'resq_theme_render_badge' ) ) {
	/**
	 * Render product badge markup from plugin data.
	 *
	 * @param int $product_id Product ID.
	 */
	function resq_theme_render_badge( int $product_id ): void {
		if ( $product_id <= 0 || ! function_exists( 'resq_get_product_badges' ) ) {
			return;
		}

		$badges = resq_get_product_badges( $product_id );
		if ( empty( $badges ) ) {
			return;
		}

		$badge = $badges[0];
		printf(
			'<span class="resq-badge resq-badge--%1$s">%2$s</span>',
			esc_attr( sanitize_html_class( (string) ( $badge['type'] ?? 'default' ) ) ),
			esc_html( (string) ( $badge['label'] ?? '' ) )
		);
	}
}
