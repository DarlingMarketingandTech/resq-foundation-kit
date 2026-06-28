<?php
/**
 * Product media — featured image, bundle collage, or branded fallback card.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array  $display Product image display payload from resq_get_product_image_display().
 * @var string $context Display context: card, pdp, shelf.
 */

defined( 'ABSPATH' ) || exit;

$display = isset( $display ) && is_array( $display ) ? $display : array();
$context = isset( $context ) ? sanitize_key( (string) $context ) : 'card';
$mode    = (string) ( $display['mode'] ?? 'none' );
$title   = (string) ( $display['title'] ?? '' );
$alt     = (string) ( $display['alt'] ?? $title );

if ( 'none' === $mode ) {
	return;
}

$wrapper_class = 'resq-product-media resq-product-media--' . sanitize_html_class( $mode );
if ( 'pdp' === $context ) {
	$wrapper_class .= ' resq-product-media--pdp';
}
?>

<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php if ( 'featured' === $mode && ! empty( $display['image_url'] ) ) : ?>
		<img
			class="resq-product-media__image"
			src="<?php echo esc_url( (string) $display['image_url'] ); ?>"
			alt="<?php echo esc_attr( $alt ); ?>"
			loading="<?php echo 'pdp' === $context ? 'eager' : 'lazy'; ?>"
			decoding="async"
		>
	<?php elseif ( 'collage' === $mode && ! empty( $display['collage'] ) && is_array( $display['collage'] ) ) : ?>
		<div class="resq-product-media__collage" role="img" aria-label="<?php echo esc_attr( $alt ); ?>">
			<?php foreach ( $display['collage'] as $item ) : ?>
				<?php if ( empty( $item['url'] ) ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<div class="resq-product-media__collage-item">
					<img
						class="resq-product-media__collage-image"
						src="<?php echo esc_url( (string) $item['url'] ); ?>"
						alt="<?php echo esc_attr( (string) ( $item['alt'] ?? '' ) ); ?>"
						loading="lazy"
						decoding="async"
					>
				</div>
			<?php endforeach; ?>
		</div>
	<?php elseif ( 'branded' === $mode ) : ?>
		<div class="resq-product-media__branded" role="img" aria-label="<?php echo esc_attr( $alt ); ?>">
			<span class="resq-product-media__branded-eyebrow">
				<?php echo esc_html( function_exists( 'resq_core_get_brand_name' ) ? resq_core_get_brand_name() : 'ResQ Organics' ); ?>
			</span>
			<strong class="resq-product-media__branded-title"><?php echo esc_html( $title ); ?></strong>
			<span class="resq-product-media__branded-label"><?php esc_html_e( 'Routine kit', 'resq-clean-pro' ); ?></span>
		</div>
	<?php endif; ?>
</div>
