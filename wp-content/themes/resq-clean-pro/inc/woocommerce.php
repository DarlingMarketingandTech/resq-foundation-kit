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

// PDP CBD disclosure (COA link + THC value) — right after the compliance notice.
add_action( 'woocommerce_single_product_summary', 'resq_theme_pdp_cbd_disclosure', 26 );

/**
 * Render the CBD COA / THC disclosure slot on the PDP summary.
 */
function resq_theme_pdp_cbd_disclosure(): void {
	get_template_part( 'template-parts/product/compliance', 'coa' );
}

// PDP extended slots — rendered below the product summary tabs area.
add_action( 'woocommerce_after_single_product_summary', 'resq_theme_pdp_bundle_options_slot', 4 );
add_action( 'woocommerce_after_single_product_summary', 'resq_theme_pdp_routine_ladder_slot', 5 );
add_action( 'woocommerce_after_single_product_summary', 'resq_theme_pdp_ingredient_profile_slot', 10 );
add_action( 'woocommerce_after_single_product_summary', 'resq_theme_pdp_fbt_slot', 15 );

/**
 * Load the bundle composition block on bundle PDPs.
 */
function resq_theme_pdp_bundle_options_slot(): void {
	get_template_part( 'template-parts/product/bundle', 'options' );
}

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

// Cart drawer — inject shell + enqueue script on all non-checkout pages.
add_action( 'wp_footer', 'resq_theme_cart_drawer_footer' );
add_action( 'wp_enqueue_scripts', 'resq_theme_enqueue_cart_drawer' );

/**
 * Inject the cart drawer shell into the page footer.
 *
 * Skipped on the checkout page to avoid interfering with the payment flow.
 */
function resq_theme_cart_drawer_footer(): void {
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		return;
	}
	get_template_part( 'template-parts/cart/drawer' );
}

/**
 * Enqueue the cart drawer script and pass config via wp_localize_script.
 */
function resq_theme_enqueue_cart_drawer(): void {
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		return;
	}

	wp_enqueue_script(
		'resq-theme-cart-drawer',
		resq_theme_get_asset_url( 'assets/js/cart-drawer.js' ),
		array( 'jquery' ),
		RESQ_THEME_VERSION,
		true
	);

	wp_localize_script(
		'resq-theme-cart-drawer',
		'resqCartDrawer',
		array(
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'nonce'      => wp_create_nonce( 'resq_cart_drawer' ),
			'isCheckout' => function_exists( 'is_checkout' ) ? is_checkout() : false,
			'isCart'     => function_exists( 'is_cart' ) ? is_cart() : false,
		)
	);
}
