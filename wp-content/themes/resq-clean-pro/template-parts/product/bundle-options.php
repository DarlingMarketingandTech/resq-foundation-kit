<?php
/**
 * PDP bundle composition block.
 *
 * Lists included products from resq_get_bundle_products(). Shows line savings
 * when bundle price is lower than the sum of included items.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if (
	! function_exists( 'resq_core_is_active' )
	|| ! resq_core_is_active()
	|| ! function_exists( 'resq_get_bundle_products' )
) {
	return;
}

global $product;

if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
	return;
}

$bundle_id = (int) $product->get_id();
$items     = resq_get_bundle_products( $bundle_id );

if ( empty( $items ) || ! is_array( $items ) ) {
	return;
}

$parts_total = 0.0;
foreach ( $items as $item ) {
	$qty   = max( 1, (int) ( $item['qty'] ?? 1 ) );
	$price = (float) ( $item['price'] ?? 0 );
	$parts_total += $price * $qty;
}

$bundle_price = (float) $product->get_price();
$savings      = $parts_total > $bundle_price ? $parts_total - $bundle_price : 0.0;
?>

<section class="resq-bundle-options" aria-labelledby="resq-bundle-options-heading">
	<h3 id="resq-bundle-options-heading" class="resq-bundle-options__title">
		<?php esc_html_e( 'What\'s Included', 'resq-clean-pro' ); ?>
	</h3>

	<ul class="resq-bundle-options__list">
		<?php foreach ( $items as $item ) : ?>
			<?php
			$item_id   = (int) ( $item['product_id'] ?? 0 );
			$qty       = max( 1, (int) ( $item['qty'] ?? 1 ) );
			$in_stock  = ! empty( $item['in_stock'] );
			$item_url  = $item_id > 0 ? get_permalink( $item_id ) : '';
			$item_class = 'resq-bundle-options__item';
			if ( ! $in_stock ) {
				$item_class .= ' is-out-of-stock';
			}
			?>
			<li class="<?php echo esc_attr( $item_class ); ?>">
				<div class="resq-bundle-options__qty" aria-hidden="true">
					<?php echo esc_html( (string) $qty ); ?>×
				</div>
				<div class="resq-bundle-options__body">
					<?php if ( $item_url ) : ?>
						<a class="resq-bundle-options__link" href="<?php echo esc_url( $item_url ); ?>">
							<?php echo esc_html( (string) ( $item['title'] ?? '' ) ); ?>
						</a>
					<?php else : ?>
						<span class="resq-bundle-options__name">
							<?php echo esc_html( (string) ( $item['title'] ?? '' ) ); ?>
						</span>
					<?php endif; ?>

					<?php if ( ! $in_stock ) : ?>
						<span class="resq-bundle-options__stock">
							<?php esc_html_e( 'Out of Stock', 'resq-clean-pro' ); ?>
						</span>
					<?php endif; ?>

					<?php if ( isset( $item['price'] ) && '' !== (string) $item['price'] ) : ?>
						<span class="resq-bundle-options__price">
							<?php echo wp_kses_post( wc_price( (float) $item['price'] ) ); ?>
						</span>
					<?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php if ( $savings > 0 ) : ?>
		<p class="resq-bundle-options__savings">
			<?php
			printf(
				/* translators: %s: formatted savings amount */
				esc_html__( 'Bundle savings: %s vs buying separately', 'resq-clean-pro' ),
				wp_kses_post( wc_price( $savings ) )
			);
			?>
		</p>
	<?php endif; ?>
</section>
