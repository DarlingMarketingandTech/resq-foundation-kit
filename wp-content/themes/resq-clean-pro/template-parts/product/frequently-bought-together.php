<?php
/**
 * PDP frequently-bought-together shell.
 *
 * Renders companion product cards from resq_get_frequently_bought_together().
 * All returned IDs have already passed resq_can_cross_sell_products() in the
 * plugin layer, so no additional CBD/audience filtering is needed here.
 * Returns silently when plugin is inactive or no FBT data exists.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if (
	! function_exists( 'resq_core_is_active' )
	|| ! resq_core_is_active()
	|| ! function_exists( 'resq_get_frequently_bought_together' )
) {
	return;
}

global $product;

if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
	return;
}

$fbt_ids = resq_get_frequently_bought_together( (int) $product->get_id() );

if ( empty( $fbt_ids ) || ! is_array( $fbt_ids ) ) {
	return;
}
?>
<section class="resq-fbt" aria-labelledby="resq-fbt-heading">

	<h3 id="resq-fbt-heading" class="resq-fbt__title">
		<?php esc_html_e( 'Frequently Bought Together', 'resq-clean-pro' ); ?>
	</h3>

	<ul class="resq-fbt__list">
		<?php foreach ( $fbt_ids as $fbt_id ) : ?>
			<?php
			$fbt_id      = absint( $fbt_id );
			$fbt_product = $fbt_id > 0 ? wc_get_product( $fbt_id ) : null;
			if ( ! $fbt_product || ! is_a( $fbt_product, 'WC_Product' ) ) {
				continue;
			}
			?>
			<li class="resq-fbt__item">
				<a class="resq-fbt__link" href="<?php echo esc_url( $fbt_product->get_permalink() ); ?>">
					<?php echo wp_kses_post( $fbt_product->get_image( 'thumbnail' ) ); ?>
					<span class="resq-fbt__name"><?php echo esc_html( $fbt_product->get_name() ); ?></span>
					<span class="resq-fbt__price"><?php echo wp_kses_post( $fbt_product->get_price_html() ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

</section>
