<?php
/**
 * Gateway segment grid — 3-column image-led routing cards.
 *
 * Routes audience traffic into sub-collections. Data from the landing content
 * map (docs/24); comfort language only (docs/05).
 *
 * @package ResQ_Clean_Pro
 *
 * @var array  $segments Segment definitions from the landing content map.
 * @var string $base_url Gateway base URL for resolving each card path.
 * @var string $title    Optional section heading.
 */

defined( 'ABSPATH' ) || exit;

$segments = isset( $segments ) && is_array( $segments ) ? $segments : array();
$base_url = isset( $base_url ) ? (string) $base_url : '';
$title    = isset( $title ) ? (string) $title : '';

if ( empty( $segments ) ) {
	return;
}
?>

<section class="resq-segment-grid-section" aria-labelledby="resq-segment-grid-heading">
	<h2 id="resq-segment-grid-heading" class="resq-segment-grid__heading">
		<?php echo '' !== $title ? esc_html( $title ) : esc_html__( 'Targeted household collections', 'resq-clean-pro' ); ?>
	</h2>

	<ul class="resq-segment-grid">
		<?php foreach ( $segments as $segment ) : ?>
			<?php
			if ( ! is_array( $segment ) ) {
				continue;
			}
			$label   = (string) ( $segment['label'] ?? '' );
			$desc    = (string) ( $segment['description'] ?? '' );
			$image   = (string) ( $segment['image'] ?? '' );
			$image_slug = (string) ( $segment['image_slug'] ?? '' );
			$variant = (string) ( $segment['image_variant'] ?? '' );
			$label_cta = (string) ( $segment['cta_label'] ?? __( 'Explore', 'resq-clean-pro' ) );
			$url     = resq_theme_resolve_landing_url( (string) ( $segment['path'] ?? '' ), $base_url );

			if ( '' === $label ) {
				continue;
			}
			?>
			<li class="resq-segment-card">
				<a class="resq-segment-card__link" href="<?php echo esc_url( $url ); ?>">
					<?php
					resq_theme_render_image(
						$image_slug,
						array(
							'class'               => 'resq-segment-card__media',
							'image_class'         => 'resq-segment-card__image',
							'size'                => 'medium_large',
							'alt'                 => $label,
							'label'               => $image,
							'placeholder_variant' => $variant,
						)
					);
					?>
					<div class="resq-segment-card__body">
						<h3 class="resq-segment-card__title"><?php echo esc_html( $label ); ?></h3>
						<?php if ( '' !== $desc ) : ?>
							<p class="resq-segment-card__desc"><?php echo esc_html( $desc ); ?></p>
						<?php endif; ?>
						<span class="resq-segment-card__cta">
							<?php echo esc_html( $label_cta ); ?>
							<span aria-hidden="true">&rarr;</span>
						</span>
					</div>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
