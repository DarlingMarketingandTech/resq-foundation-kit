<?php
/**
 * Product card shell.
 *
 * Fires all standard WooCommerce shop-loop action hooks. Adds the resq-product-card
 * wrapper class, renders the badge slot before the WC item link opens, and appends
 * a plugin-guarded compliance notice slot after the WC hooks complete.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! is_a( $product, 'WC_Product' ) ) {
	return;
}

$product_id  = (int) $product->get_id();
$is_cbd      = function_exists( 'resq_is_cbd_product' ) && resq_is_cbd_product( $product_id );
$extra_class = $is_cbd ? 'resq-product-card--cbd' : '';
?>
<li <?php wc_product_class( array_filter( array( 'resq-product-card', $extra_class ) ), $product ); ?>>

	<?php resq_theme_render_badge( $product_id ); ?>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>

	<?php do_action( 'woocommerce_shop_loop_item_title' ); ?>

	<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>

	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

	<?php resq_theme_render_compliance_notices( 'card', $product_id ); ?>

</li>
