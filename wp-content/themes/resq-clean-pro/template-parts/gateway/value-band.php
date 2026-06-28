<?php
/**
 * Bundles gateway value band — "how bundles work" (3 points).
 *
 * Presentational only. No hardcoded prices or savings percentages: real
 * purchasable bundles and their live savings render in the plugin-driven
 * product shelf below (docs/05 — bundle savings must match WooCommerce).
 *
 * @package ResQ_Clean_Pro
 *
 * @var array  $value_points Value point definitions from the landing content map.
 * @var string $title        Optional section heading.
 */

defined( 'ABSPATH' ) || exit;

$value_points = isset( $value_points ) && is_array( $value_points ) ? $value_points : array();
$title        = isset( $title ) ? (string) $title : '';

if ( empty( $value_points ) ) {
	return;
}
?>

<section class="resq-value-band" aria-labelledby="resq-value-band-heading">
	<h2 id="resq-value-band-heading" class="resq-value-band__heading">
		<?php echo '' !== $title ? esc_html( $title ) : esc_html__( 'How routine kits work', 'resq-clean-pro' ); ?>
	</h2>

	<ul class="resq-value-band__list">
		<?php
		$step = 0;
		foreach ( $value_points as $point ) :
			if ( ! is_array( $point ) ) {
				continue;
			}
			$step++;
			$point_title = (string) ( $point['title'] ?? '' );
			$point_body  = (string) ( $point['body'] ?? '' );

			if ( '' === $point_title ) {
				continue;
			}
			?>
			<li class="resq-value-band__item">
				<span class="resq-value-band__step" aria-hidden="true"><?php echo esc_html( (string) $step ); ?></span>
				<h3 class="resq-value-band__item-title"><?php echo esc_html( $point_title ); ?></h3>
				<?php if ( '' !== $point_body ) : ?>
					<p class="resq-value-band__item-body"><?php echo esc_html( $point_body ); ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
