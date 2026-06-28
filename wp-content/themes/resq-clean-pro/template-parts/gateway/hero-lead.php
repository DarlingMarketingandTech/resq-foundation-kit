<?php
/**
 * Gateway marketing hero — eyebrow, headline, subcopy, primary CTA, image.
 *
 * Reads compliance-adapted copy from the landing content map (docs/24). Falls
 * back to the WP page title/content when no map entry exists, so the surface is
 * never empty. Comfort language only (docs/05).
 *
 * @package ResQ_Clean_Pro
 *
 * @var array  $content  Landing content array from resq_theme_get_landing_content().
 * @var string $base_url Gateway base URL for resolving the CTA path.
 * @var bool   $is_cbd   Whether this hero is on the isolated CBD surface.
 */

defined( 'ABSPATH' ) || exit;

$content  = isset( $content ) && is_array( $content ) ? $content : array();
$base_url = isset( $base_url ) ? (string) $base_url : '';
$is_cbd   = ! empty( $is_cbd );

$headline = (string) ( $content['headline'] ?? '' );
$subcopy  = (string) ( $content['subcopy'] ?? '' );
$eyebrow  = (string) ( $content['eyebrow'] ?? '' );

$cta       = isset( $content['primary_cta'] ) && is_array( $content['primary_cta'] ) ? $content['primary_cta'] : array();
$cta_label = (string) ( $cta['label'] ?? '' );
$cta_url   = isset( $cta['path'] ) ? resq_theme_resolve_landing_url( (string) $cta['path'], $base_url ) : '';

$image     = (string) ( $content['image'] ?? '' );
$image_alt = (string) ( $content['image_alt'] ?? '' );
$image_slug = (string) ( $content['image_slug'] ?? '' );
$image_var = (string) ( $content['image_variant'] ?? '' );

$has_map = ( '' !== $headline );
?>

<section class="resq-lead<?php echo $is_cbd ? ' resq-lead--cbd' : ''; ?>" aria-labelledby="resq-lead-title">
	<div class="resq-lead__inner">
		<div class="resq-lead__content">
			<?php if ( '' !== $eyebrow ) : ?>
				<span class="resq-lead__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<?php endif; ?>

			<h1 id="resq-lead-title" class="resq-lead__headline">
				<?php echo $has_map ? esc_html( $headline ) : esc_html( get_the_title() ); ?>
			</h1>

			<?php if ( '' !== $subcopy ) : ?>
				<p class="resq-lead__subcopy"><?php echo esc_html( $subcopy ); ?></p>
			<?php elseif ( get_the_content() ) : ?>
				<div class="resq-lead__subcopy entry-content"><?php the_content(); ?></div>
			<?php endif; ?>

			<?php if ( '' !== $cta_label && '' !== $cta_url ) : ?>
				<a class="resq-lead__cta" href="<?php echo esc_url( $cta_url ); ?>">
					<?php echo esc_html( $cta_label ); ?>
				</a>
			<?php endif; ?>
		</div>

		<?php
		if ( '' !== $image || '' !== $image_slug ) {
			resq_theme_render_image(
				$image_slug,
				array(
					'class'               => 'resq-lead__media',
					'image_class'         => 'resq-lead__image',
					'size'                => 'large',
					'alt'                 => $image_alt,
					'label'               => $image,
					'placeholder_variant' => $image_var,
					'loading'             => 'eager',
				)
			);
		}
		?>
	</div>
</section>
