<?php
/**
 * Gateway product shelf — plugin-driven product cards.
 *
 * @package ResQ_Clean_Pro
 *
 * @var int[]  $product_ids Canonical product IDs for the shelf.
 * @var string $shelf_title Optional shelf heading override.
 */

defined( 'ABSPATH' ) || exit;

if (
	! function_exists( 'resq_core_is_active' )
	|| ! resq_core_is_active()
	|| ! function_exists( 'resq_get_product_card_data' )
) {
	return;
}

$product_ids = isset( $product_ids ) && is_array( $product_ids ) ? array_map( 'absint', $product_ids ) : array();
$product_ids = array_values( array_filter( $product_ids ) );
$shelf_title = isset( $shelf_title ) ? (string) $shelf_title : __( 'Featured Products', 'resq-clean-pro' );

if ( empty( $product_ids ) ) {
	return;
}

$cards = array();

foreach ( $product_ids as $product_id ) {
	$card = resq_get_product_card_data( $product_id );
	if ( ! empty( $card ) && ! empty( $card['title'] ) ) {
		$cards[] = $card;
	}
}

if ( empty( $cards ) ) {
	return;
}

$show_title = '' !== trim( $shelf_title );
?>

<section class="resq-gateway__section resq-gateway-shelf-section"<?php echo $show_title ? ' aria-labelledby="resq-gateway-shelf-heading"' : ' aria-label="' . esc_attr__( 'Product shelf', 'resq-clean-pro' ) . '"'; ?>>
	<?php if ( $show_title ) : ?>
	<h2 id="resq-gateway-shelf-heading" class="resq-gateway__section-title">
		<?php echo esc_html( $shelf_title ); ?>
	</h2>
	<?php endif; ?>

	<ul class="resq-gateway-shelf">
		<?php foreach ( $cards as $card ) : ?>
			<?php
			$is_cbd     = ( 'cbd' === ( $card['compliance_zone'] ?? 'standard' ) );
			$card_class = 'resq-gateway-shelf__card';

			if ( $is_cbd ) {
				$card_class .= ' resq-gateway-shelf__card--cbd';
			}
			if ( empty( $card['in_stock'] ) ) {
				$card_class .= ' is-out-of-stock';
			}
			?>
			<li class="<?php echo esc_attr( $card_class ); ?>">
				<a class="resq-gateway-shelf__link" href="<?php echo esc_url( (string) ( $card['url'] ?? '#' ) ); ?>">
					<div class="resq-gateway-shelf__media">
						<?php
						$product_id = (int) ( $card['product_id'] ?? 0 );
						if ( $product_id > 0 && function_exists( 'resq_theme_render_product_media' ) ) {
							resq_theme_render_product_media( $product_id, 'shelf' );
						} elseif ( ! empty( $card['image_url'] ) ) {
							?>
							<img
								class="resq-gateway-shelf__image"
								src="<?php echo esc_url( (string) $card['image_url'] ); ?>"
								alt="<?php echo esc_attr( (string) ( $card['title'] ?? '' ) ); ?>"
								loading="lazy"
								decoding="async"
							>
							<?php
						}
						?>
					</div>

					<div class="resq-gateway-shelf__body">
						<?php if ( ! empty( $card['badges'] ) && is_array( $card['badges'] ) ) : ?>
							<div class="resq-gateway-shelf__badges">
								<?php
								$badge = $card['badges'][0];
								printf(
									'<span class="resq-badge resq-badge--%1$s">%2$s</span>',
									esc_attr( sanitize_html_class( (string) ( $badge['type'] ?? 'default' ) ) ),
									esc_html( (string) ( $badge['label'] ?? '' ) )
								);
								?>
							</div>
						<?php endif; ?>

						<h3 class="resq-gateway-shelf__title"><?php echo esc_html( (string) ( $card['title'] ?? '' ) ); ?></h3>

						<?php if ( ! empty( $card['subtitle'] ) ) : ?>
							<p class="resq-gateway-shelf__subtitle"><?php echo esc_html( (string) $card['subtitle'] ); ?></p>
						<?php endif; ?>

						<?php if ( empty( $card['in_stock'] ) ) : ?>
							<span class="resq-gateway-shelf__stock"><?php esc_html_e( 'Out of Stock', 'resq-clean-pro' ); ?></span>
						<?php endif; ?>

						<?php if ( ! empty( $card['price_html'] ) ) : ?>
							<div class="resq-gateway-shelf__price">
								<?php echo wp_kses_post( (string) $card['price_html'] ); ?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $card['requires_notice'] ) ) : ?>
							<div class="resq-gateway-shelf__notice">
								<?php resq_theme_render_compliance_notices( 'card', (int) ( $card['product_id'] ?? 0 ) ); ?>
							</div>
						<?php endif; ?>
					</div>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
