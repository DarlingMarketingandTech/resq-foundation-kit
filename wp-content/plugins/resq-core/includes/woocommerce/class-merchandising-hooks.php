<?php
/**
 * WooCommerce merchandising hooks — Stream C and D.
 *
 * Stream C: filter Woo related products and cart cross-sells through
 *           resq_can_cross_sell_products() so CBD, audience, and baby-flag
 *           rules are enforced on every merchandising surface.
 *
 * Stream D: validate bundle composition on add-to-cart so a bundle cannot
 *           be added when any included product is out of stock or compliance-
 *           incompatible. Fails fast with a visible cart notice.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Merchandising_Hooks
 *
 * Wires plugin business rules into WooCommerce's hook surface.
 * Static-only: no instance needed; all hooks registered once via ::init().
 */
class ResQ_Core_Merchandising_Hooks {

	/**
	 * Register all hooks. Called from the plugin bootstrap after WC is confirmed active.
	 */
	public static function init(): void {
		// Stream C — related products and cart cross-sells.
		add_filter( 'woocommerce_related_products', array( __CLASS__, 'filter_related_products' ), 10, 3 );
		add_filter( 'woocommerce_cart_crosssell_ids', array( __CLASS__, 'filter_cart_crosssells' ), 10, 2 );

		// Stream D — bundle add-to-cart validation.
		add_filter( 'woocommerce_add_to_cart_validation', array( __CLASS__, 'validate_bundle_add_to_cart' ), 10, 3 );

		// Stream E — cart drawer AJAX suggestions endpoint.
		add_action( 'wp_ajax_resq_cart_drawer_suggestions', array( __CLASS__, 'ajax_cart_drawer_suggestions' ) );
		add_action( 'wp_ajax_nopriv_resq_cart_drawer_suggestions', array( __CLASS__, 'ajax_cart_drawer_suggestions' ) );
	}

	// -------------------------------------------------------------------------
	// Stream C — Cross-sell and related product restrictions
	// -------------------------------------------------------------------------

	/**
	 * Remove related products that fail the cross-sell gate for the current product.
	 *
	 * @param int[]   $related_ids   Product IDs Woo would show.
	 * @param int     $product_id    The product whose page is being viewed.
	 * @param mixed[] $args          Woo query args (unused here).
	 * @return int[]
	 */
	public static function filter_related_products( array $related_ids, int $product_id, array $args ): array {
		if ( empty( $related_ids ) || $product_id <= 0 ) {
			return $related_ids;
		}

		if ( ! function_exists( 'resq_can_cross_sell_products' ) ) {
			return $related_ids;
		}

		return array_values(
			array_filter(
				$related_ids,
				static fn( int $target_id ): bool => resq_can_cross_sell_products( $product_id, $target_id )
			)
		);
	}

	/**
	 * Remove cart cross-sell suggestions that fail the cross-sell gate.
	 *
	 * WooCommerce collects cross-sell IDs from all items in the cart and passes
	 * the merged list here. We check each suggestion against every cart item
	 * and keep it only when all cart items permit the cross-sell.
	 *
	 * @param int[]      $crosssell_ids IDs WooCommerce would show in the cart.
	 * @param WC_Cart    $cart          Current cart instance.
	 * @return int[]
	 */
	public static function filter_cart_crosssells( array $crosssell_ids, WC_Cart $cart ): array {
		if ( empty( $crosssell_ids ) ) {
			return $crosssell_ids;
		}

		if ( ! function_exists( 'resq_can_cross_sell_products' ) ) {
			return $crosssell_ids;
		}

		$cart_product_ids = array_map(
			static fn( array $item ): int => (int) $item['product_id'],
			$cart->get_cart()
		);

		if ( empty( $cart_product_ids ) ) {
			return $crosssell_ids;
		}

		return array_values(
			array_filter(
				$crosssell_ids,
				static function ( int $target_id ) use ( $cart_product_ids ): bool {
					foreach ( $cart_product_ids as $source_id ) {
						if ( ! resq_can_cross_sell_products( $source_id, $target_id ) ) {
							return false;
						}
					}
					return true;
				}
			)
		);
	}

	// -------------------------------------------------------------------------
	// Stream D — Bundle add-to-cart validation
	// -------------------------------------------------------------------------

	/**
	 * Validate that a bundle's composition is purchasable before allowing add-to-cart.
	 *
	 * Checks:
	 *  1. All included products exist as published WC products.
	 *  2. Every included product is in stock.
	 *  3. Cross-sell gate passes between the bundle and each included product
	 *     (catches CBD / audience / baby-flag mismatches in catalog data).
	 *
	 * @param bool $passed    Whether validation has passed so far.
	 * @param int  $product_id Product being added.
	 * @param int  $quantity   Quantity (unused here; composition qty used instead).
	 * @return bool
	 */
	public static function validate_bundle_add_to_cart( bool $passed, int $product_id, int $quantity ): bool {
		if ( ! $passed ) {
			return false;
		}

		if ( ! function_exists( 'resq_get_bundle_products' ) || ! function_exists( 'resq_get_product_meta' ) ) {
			return $passed;
		}

		// Only act on products that have bundle composition meta.
		$raw_composition = resq_get_product_meta( $product_id, '_resq_bundle_product_ids', array() );
		if ( ! is_array( $raw_composition ) || empty( $raw_composition ) ) {
			return $passed;
		}

		$bundle_items = resq_get_bundle_products( $product_id );
		if ( empty( $bundle_items ) ) {
			// Meta exists but no valid items resolved — do not silently allow.
			wc_add_notice(
				__( 'This bundle is not currently available. Please contact us if you need assistance.', 'resq-core' ),
				'error'
			);
			return false;
		}

		$errors = array();

		foreach ( $bundle_items as $item ) {
			$item_id    = (int) ( $item['product_id'] ?? 0 );
			$item_title = (string) ( $item['title'] ?? '' );

			if ( $item_id <= 0 ) {
				continue;
			}

			// Out of stock check.
			if ( isset( $item['in_stock'] ) && ! $item['in_stock'] ) {
				$errors[] = sprintf(
					/* translators: %s: product title */
					__( '&ldquo;%s&rdquo; included in this bundle is currently out of stock.', 'resq-core' ),
					esc_html( $item_title )
				);
				continue;
			}

			// Compliance gate (CBD zone, audience, baby flag).
			if ( function_exists( 'resq_can_cross_sell_products' ) && ! resq_can_cross_sell_products( $product_id, $item_id ) ) {
				$errors[] = sprintf(
					/* translators: %s: product title */
					__( '&ldquo;%s&rdquo; cannot be included in this bundle due to product restrictions.', 'resq-core' ),
					esc_html( $item_title )
				);
			}
		}

		if ( ! empty( $errors ) ) {
			foreach ( $errors as $message ) {
				wc_add_notice( $message, 'error' );
			}
			return false;
		}

		return $passed;
	}

	// -------------------------------------------------------------------------
	// Stream E — Cart drawer AJAX handler
	// -------------------------------------------------------------------------

	/**
	 * Return rendered suggestion card HTML for the cart drawer.
	 *
	 * Accepts: POST product_id (int), nonce.
	 * Returns: JSON { html: string }
	 *
	 * An empty html string is a valid response (no suggestions to show).
	 */
	public static function ajax_cart_drawer_suggestions(): void {
		check_ajax_referer( 'resq_cart_drawer', 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

		if ( $product_id <= 0 || ! function_exists( 'resq_get_recommended_routine_addons' ) ) {
			wp_send_json_success( array( 'html' => '' ) );
		}

		$suggestions = resq_get_recommended_routine_addons( $product_id );

		if ( empty( $suggestions ) ) {
			wp_send_json_success( array( 'html' => '' ) );
		}

		ob_start();

		echo '<p class="cart-drawer__suggestion-heading">';
		esc_html_e( 'Complete your routine', 'resq-core' );
		echo '</p>';

		foreach ( $suggestions as $suggestion ) {
			$item_id = (int) ( $suggestion['product_id'] ?? 0 );
			$title   = (string) ( $suggestion['title'] ?? '' );
			$reason  = (string) ( $suggestion['reason'] ?? '' );

			if ( $item_id <= 0 || ! function_exists( 'wc_get_product' ) ) {
				continue;
			}

			$product = wc_get_product( $item_id );
			if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
				continue;
			}

			$price_html   = $product->get_price_html();
			$add_to_cart  = esc_url( $product->add_to_cart_url() );
			$reason_label = 'upgrade_to_kit' === $reason
				? __( 'Upgrade to kit', 'resq-core' )
				: __( 'Next routine step', 'resq-core' );

			?>
			<div class="cart-drawer__suggestion-card">
				<p class="cart-drawer__suggestion-title">
					<?php echo esc_html( $title ?: $product->get_name() ); ?>
					<span class="cart-drawer__suggestion-reason"><?php echo esc_html( $reason_label ); ?></span>
				</p>
				<?php if ( $price_html ) : ?>
					<p class="cart-drawer__suggestion-price"><?php echo wp_kses_post( $price_html ); ?></p>
				<?php endif; ?>
				<a href="<?php echo $add_to_cart; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above ?>"
					class="cart-drawer__suggestion-add btn btn--small btn--secondary add_to_cart_button"
					data-product_id="<?php echo esc_attr( (string) $item_id ); ?>"
					aria-label="<?php echo esc_attr( sprintf( /* translators: %s: product title */ __( 'Add %s to cart', 'resq-core' ), $title ?: $product->get_name() ) ); ?>"
				>
					<?php esc_html_e( 'Add to cart', 'resq-core' ); ?>
				</a>
			</div>
			<?php
		}

		$html = (string) ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}
}
