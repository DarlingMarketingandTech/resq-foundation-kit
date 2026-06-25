<?php
/**
 * Compliance gates — checkout-time enforcement (Phase 10).
 *
 * A3: block checkout to a restricted state when the cart contains CBD products.
 * The restricted-state list and notice copy are owner/legal-supplied via the
 * `resq_core_compliance` option; with the default empty list this gate is inert
 * (blocks nothing) until configured, so it is safe to ship enabled on the dev
 * site for review.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Compliance_Gates
 *
 * Static-only; all hooks registered once via ::init() after WC is confirmed active.
 */
class ResQ_Core_Compliance_Gates {

	/**
	 * Register hooks.
	 */
	public static function init(): void {
		add_action( 'woocommerce_after_checkout_validation', array( __CLASS__, 'validate_state_restriction' ), 10, 2 );
	}

	/**
	 * Reject checkout to a restricted state when the cart contains CBD products.
	 *
	 * @param array<string, mixed> $data   Posted checkout fields.
	 * @param \WP_Error            $errors Checkout error accumulator.
	 */
	public static function validate_state_restriction( array $data, $errors ): void {
		if ( ! is_wp_error( $errors ) ) {
			return;
		}

		if ( ! function_exists( 'resq_core_feature_enabled' ) || ! resq_core_feature_enabled( 'state_restriction' ) ) {
			return;
		}

		$restricted = (array) resq_core_get_option( 'resq_core_compliance.restricted_states', array() );
		$restricted = array_filter( array_map( static fn( $s ): string => strtoupper( sanitize_text_field( (string) $s ) ), $restricted ) );

		if ( empty( $restricted ) ) {
			// No owner-supplied list yet — gate is inert.
			return;
		}

		if ( ! self::cart_has_cbd() ) {
			return;
		}

		$ship_different = ! empty( $data['ship_to_different_address'] );
		$state          = $ship_different
			? (string) ( $data['shipping_state'] ?? '' )
			: (string) ( $data['billing_state'] ?? '' );

		// Fall back to billing when shipping state is blank.
		if ( '' === $state ) {
			$state = (string) ( $data['billing_state'] ?? '' );
		}

		$state = strtoupper( sanitize_text_field( $state ) );

		if ( '' === $state || ! in_array( $state, $restricted, true ) ) {
			return;
		}

		$notice = (string) resq_core_get_option( 'resq_core_compliance.state_restriction_notice', '' );

		if ( '' === $notice ) {
			$notice = __( 'We are unable to ship CBD products to your selected state. Please remove CBD items from your cart, or choose a different shipping destination, to continue.', 'resq-core' );
		}

		$errors->add( 'resq_state_restriction', $notice );
	}

	/**
	 * Whether the current cart contains at least one CBD-lane product.
	 *
	 * @return bool
	 */
	private static function cart_has_cbd(): bool {
		if ( ! function_exists( 'WC' ) || ! function_exists( 'resq_is_cbd_product' ) ) {
			return false;
		}

		$cart = WC()->cart;
		if ( ! $cart ) {
			return false;
		}

		foreach ( $cart->get_cart() as $cart_item ) {
			$product_id = (int) ( $cart_item['product_id'] ?? 0 );
			if ( $product_id > 0 && resq_is_cbd_product( $product_id ) ) {
				return true;
			}
		}

		return false;
	}
}
