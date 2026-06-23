<?php
/**
 * Empty cart shell.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-cart-empty resq-cart-empty">

	<?php wc_print_notices(); ?>

	<?php do_action( 'woocommerce_cart_is_empty' ); ?>

	<?php resq_theme_render_compliance_notices( 'cart' ); ?>

	<?php
	echo wp_kses_post(
		apply_filters(
			'woocommerce_empty_cart_message',
			'<p class="cart-empty woocommerce-info">' . esc_html__( 'Your cart is currently empty.', 'woocommerce' ) . '</p>'
		)
	);
	?>

	<p class="return-to-shop">
		<a
			class="button wc-backward resq-btn"
			href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"
		><?php echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Return to shop', 'woocommerce' ) ) ); ?></a>
	</p>

</div>
