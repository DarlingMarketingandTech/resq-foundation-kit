<?php
/**
 * Commerce cart drawer.
 *
 * Injected once into wp_footer on all pages except checkout.
 * Suggestions are loaded via AJAX after add-to-cart and injected by
 * cart-drawer.js. The drawer is hidden until JS opens it.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="resq-cart-drawer" class="cart-drawer" aria-hidden="true" aria-label="<?php esc_attr_e( 'Added to cart', 'resq-clean-pro' ); ?>">
	<div class="cart-drawer__overlay" tabindex="-1"></div>
	<div class="cart-drawer__panel" role="dialog" aria-modal="true" aria-labelledby="resq-cart-drawer-title">
		<div class="cart-drawer__header">
			<p class="cart-drawer__title" id="resq-cart-drawer-title">
				<?php esc_html_e( 'Added to cart', 'resq-clean-pro' ); ?>
			</p>
			<button
				class="cart-drawer__close"
				aria-label="<?php esc_attr_e( 'Close', 'resq-clean-pro' ); ?>"
				aria-expanded="true"
				aria-controls="resq-cart-drawer"
			>
				<span aria-hidden="true">&#x2715;</span>
			</button>
		</div>

		<div class="cart-drawer__body">
			<div class="cart-drawer__suggestions" aria-live="polite" aria-atomic="true">
				<?php /* Suggestion cards injected here by cart-drawer.js */ ?>
			</div>

			<div class="cart-drawer__actions">
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-drawer__view-cart btn btn--secondary">
					<?php esc_html_e( 'View cart', 'resq-clean-pro' ); ?>
				</a>
				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="cart-drawer__checkout btn btn--primary">
					<?php esc_html_e( 'Checkout', 'resq-clean-pro' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
