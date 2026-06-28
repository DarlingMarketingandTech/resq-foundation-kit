<?php
/**
 * Replace WooCommerce placeholder images for bundles with display fallbacks.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Bundle_Media_Hooks
 */
class ResQ_Core_Bundle_Media_Hooks {

	/**
	 * Bootstrap storefront hooks.
	 */
	public static function init(): void {
		add_filter( 'woocommerce_product_get_image', array( __CLASS__, 'filter_product_image_html' ), 20, 6 );
		add_action( 'woocommerce_before_single_product_summary', array( __CLASS__, 'maybe_replace_product_gallery' ), 19 );
	}

	/**
	 * Swap default gallery output for bundle fallbacks when needed.
	 */
	public static function maybe_replace_product_gallery(): void {
		if ( ! is_product() ) {
			return;
		}

		global $product;
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			return;
		}

		$product_id = (int) $product->get_id();
		if ( ! resq_is_bundle_product( $product_id ) || resq_product_has_usable_image( $product_id ) ) {
			return;
		}

		$display = resq_get_product_image_display( $product_id, 'woocommerce_single' );
		if ( 'featured' === ( $display['mode'] ?? '' ) ) {
			return;
		}

		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		add_action( 'woocommerce_before_single_product_summary', array( __CLASS__, 'render_bundle_gallery_fallback' ), 20 );
	}

	/**
	 * Render bundle PDP media fallback via theme template part.
	 */
	public static function render_bundle_gallery_fallback(): void {
		global $product;
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			return;
		}

		if ( function_exists( 'resq_theme_render_product_media' ) ) {
			resq_theme_render_product_media( (int) $product->get_id(), 'pdp' );
		}
	}

	/**
	 * Replace placeholder loop/card images for bundles.
	 *
	 * @param string       $image       Original HTML.
	 * @param WC_Product   $product     Product.
	 * @param string|int[] $size        Image size.
	 * @param array        $attr        Attributes.
	 * @param bool         $placeholder Whether WC is using a placeholder.
	 * @return string
	 */
	public static function filter_product_image_html( string $image, $product, $size, array $attr, bool $placeholder ): string {
		unset( $size, $attr );

		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			return $image;
		}

		$product_id = (int) $product->get_id();
		if ( ! resq_is_bundle_product( $product_id ) ) {
			return $image;
		}

		if ( resq_product_has_usable_image( $product_id ) ) {
			return $image;
		}

		if ( ! $placeholder && '' !== $image ) {
			return $image;
		}

		if ( ! function_exists( 'resq_theme_get_product_media_html' ) ) {
			return $image;
		}

		$html = resq_theme_get_product_media_html( $product_id, 'card' );

		return '' !== $html ? $html : $image;
	}
}
