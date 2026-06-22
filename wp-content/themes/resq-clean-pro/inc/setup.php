<?php
/**
 * Theme setup and supports.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports and nav menu locations.
 */
function resq_theme_setup(): void {
	load_theme_textdomain( 'resq-clean-pro', RESQ_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'woocommerce' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'resq-clean-pro' ),
			'footer'  => __( 'Footer Navigation', 'resq-clean-pro' ),
		)
	);
}
add_action( 'after_setup_theme', 'resq_theme_setup' );
