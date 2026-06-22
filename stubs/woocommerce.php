<?php
/**
 * WooCommerce function/class stubs for IDE static analysis (Intelephense).
 *
 * Not loaded at runtime — WordPress/WooCommerce provide the real implementations.
 *
 * @package ResQ_Foundation_Kit
 */

/**
 * @param int|string|WC_Product|null $product Product ID, post, or product object.
 * @return WC_Product|false|null
 */
function wc_get_product( $product = false ) {}

/**
 * @return string
 */
function wc_get_cart_url() {
	return '';
}

/**
 * @return WooCommerce
 */
function WC() {}

/**
 * WooCommerce cart (minimal surface).
 */
class WC_Cart {

	/**
	 * @return int
	 */
	public function get_cart_contents_count() {}
}

/**
 * WooCommerce main class (minimal surface).
 */
class WooCommerce {

	/**
	 * @var WC_Cart|null
	 */
	public $cart;
}

/**
 * WooCommerce product (minimal surface used by resq-core and theme).
 */
class WC_Product {

	/**
	 * @return int
	 */
	public function get_id() {}

	/**
	 * @param string $type Product type slug.
	 * @return bool
	 */
	public function is_type( $type ) {}

	/**
	 * @return int
	 */
	public function get_parent_id() {}

	/**
	 * @return string
	 */
	public function get_status() {}

	/**
	 * @return string
	 */
	public function get_name() {}

	/**
	 * @return string
	 */
	public function get_price() {}

	/**
	 * @return string
	 */
	public function get_price_html() {}

	/**
	 * @return int
	 */
	public function get_image_id() {}

	/**
	 * @return bool
	 */
	public function is_in_stock() {}

	/**
	 * @return bool
	 */
	public function is_on_sale() {}

	/**
	 * @return int[]
	 */
	public function get_cross_sell_ids() {}
}
