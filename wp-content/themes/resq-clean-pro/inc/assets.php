<?php
/**
 * Theme asset registration.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue theme styles and scripts.
 */
function resq_theme_enqueue_assets(): void {
	wp_enqueue_style(
		'resq-theme-tokens',
		resq_theme_get_asset_url( 'assets/css/tokens.css' ),
		array(),
		RESQ_THEME_VERSION
	);

	wp_enqueue_style(
		'resq-theme-base',
		resq_theme_get_asset_url( 'assets/css/base.css' ),
		array( 'resq-theme-tokens' ),
		RESQ_THEME_VERSION
	);

	wp_enqueue_style(
		'resq-theme-layout',
		resq_theme_get_asset_url( 'assets/css/layout.css' ),
		array( 'resq-theme-base' ),
		RESQ_THEME_VERSION
	);

	wp_enqueue_style(
		'resq-theme-components',
		resq_theme_get_asset_url( 'assets/css/components.css' ),
		array( 'resq-theme-layout' ),
		RESQ_THEME_VERSION
	);

	wp_enqueue_style(
		'resq-theme-style',
		get_stylesheet_uri(),
		array( 'resq-theme-components' ),
		RESQ_THEME_VERSION
	);

	wp_enqueue_script(
		'resq-theme-navigation',
		resq_theme_get_asset_url( 'assets/js/navigation.js' ),
		array(),
		RESQ_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'resq_theme_enqueue_assets' );
