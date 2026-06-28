<?php
/**
 * Lane hero — symptom-aware comfort hero with canonical CTA.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $lane    Enriched lane data.
 * @var array $content Lane copy from lane-content map.
 */

defined( 'ABSPATH' ) || exit;

$lane    = isset( $lane ) && is_array( $lane ) ? $lane : array();
$content = isset( $content ) && is_array( $content ) ? $content : array();

$eyebrow  = (string) ( $content['eyebrow'] ?? '' );
$headline = (string) ( $content['headline'] ?? '' );
$subcopy  = (string) ( $content['subcopy'] ?? '' );
$image      = (string) ( $content['image'] ?? '' );
$image_slug = (string) ( $content['image_slug'] ?? '' );
$image_alt  = (string) ( $content['image_alt'] ?? $headline );
$variant    = (string) ( $content['image_variant'] ?? '' );
$cta_label = (string) ( $content['cta_label'] ?? __( 'Shop now', 'resq-clean-pro' ) );

$canonical_id = (int) ( $lane['canonical_id'] ?? 0 );
$product_url  = '';
$product_title = '';

if ( $canonical_id > 0 && function_exists( 'resq_get_wc_product_summary' ) ) {
	$summary = resq_get_wc_product_summary( $canonical_id );
	if ( $summary ) {
		$product_url   = (string) ( $summary['url'] ?? '' );
		$product_title = (string) ( $summary['title'] ?? '' );
	}
}

if ( '' === $headline ) {
	return;
}
?>

<section class="resq-lane-hero" aria-labelledby="resq-lane-hero-title">
	<div class="resq-lane-hero__content">
		<?php if ( '' !== $eyebrow ) : ?>
			<span class="resq-section-label resq-section-label--sage"><?php echo esc_html( $eyebrow ); ?></span>
		<?php endif; ?>

		<h1 id="resq-lane-hero-title" class="resq-lane-hero__headline"><?php echo esc_html( $headline ); ?></h1>

		<?php if ( '' !== $subcopy ) : ?>
			<p class="resq-lane-hero__subcopy"><?php echo esc_html( $subcopy ); ?></p>
		<?php endif; ?>

		<?php if ( $canonical_id > 0 && '' !== $product_url ) : ?>
			<div class="resq-lane-hero__cta-row">
				<a class="resq-lane-hero__cta btn btn--primary" href="<?php echo esc_url( $product_url ); ?>">
					<?php echo esc_html( $cta_label ); ?>
				</a>
				<?php if ( '' !== $product_title ) : ?>
					<span class="resq-lane-hero__product-name">
						<?php
						printf(
							/* translators: %s: canonical WooCommerce product title */
							esc_html__( 'Canonical product: %s', 'resq-clean-pro' ),
							esc_html( $product_title )
						);
						?>
					</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php
	if ( '' !== $image || '' !== $image_slug ) {
		resq_theme_render_image(
			$image_slug,
			array(
				'class'               => 'resq-lane-hero__media',
				'image_class'         => 'resq-lane-hero__image',
				'size'                => 'large',
				'alt'                 => $image_alt,
				'label'               => $image,
				'placeholder_variant' => $variant,
			)
		);
	}
	?>
</section>
