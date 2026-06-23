<?php
/**
 * WooCommerce integration — gallery support and PDP slot hooks.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! resq_theme_wc_active() ) {
	return;
}

add_action( 'after_setup_theme', 'resq_theme_woocommerce_gallery_support' );

/**
 * Declare WooCommerce gallery theme supports.
 */
function resq_theme_woocommerce_gallery_support(): void {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 450,
			'single_image_width'    => 600,
			'product_grid'          => array(
				'default_rows'    => 4,
				'min_rows'        => 2,
				'default_columns' => 3,
				'min_columns'     => 2,
				'max_columns'     => 4,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

// PDP compliance notice — between excerpt and add-to-cart (priority 25).
add_action( 'woocommerce_single_product_summary', 'resq_theme_pdp_compliance_notices', 25 );

/**
 * Render compliance notice slot on the PDP summary.
 */
function resq_theme_pdp_compliance_notices(): void {
	global $product;
	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		return;
	}
	resq_theme_render_compliance_notices( 'pdp', (int) $product->get_id() );
}

// PDP extended slots — rendered below the product summary tabs area.
add_action( 'woocommerce_after_single_product_summary', 'resq_theme_pdp_routine_ladder_slot', 5 );
add_action( 'woocommerce_after_single_product_summary', 'resq_theme_pdp_ingredient_profile_slot', 10 );
add_action( 'woocommerce_after_single_product_summary', 'resq_theme_pdp_fbt_slot', 15 );

/**
 * Load the routine ladder template part on the PDP.
 */
function resq_theme_pdp_routine_ladder_slot(): void {
	get_template_part( 'template-parts/product/routine', 'ladder' );
}

/**
 * Load the ingredient profile template part on the PDP.
 */
function resq_theme_pdp_ingredient_profile_slot(): void {
	get_template_part( 'template-parts/product/ingredient', 'profile' );
}

/**
 * Load the frequently-bought-together template part on the PDP.
 */
function resq_theme_pdp_fbt_slot(): void {
	get_template_part( 'template-parts/product/frequently-bought', 'together' );
}
