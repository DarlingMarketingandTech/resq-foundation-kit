<?php
/**
 * Homepage — Social proof, pledge, and clean-formula promise.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $args {
 *     @type string $stories_url  Customer stories URL.
 *     @type string $partners_url Rescue partners URL.
 * }
 */

defined( 'ABSPATH' ) || exit;

$stories_url  = isset( $args['stories_url'] ) ? (string) $args['stories_url'] : home_url( '/learn/' );
$partners_url = isset( $args['partners_url'] ) ? (string) $args['partners_url'] : home_url( '/learn/' );
?>

<section class="resq-home-section resq-home-section--cream-deep" aria-labelledby="resq-social-title">
	<div class="resq-social-proof__header resq-home-section__inner">
		<span class="resq-section-label"><?php esc_html_e( 'Why families choose ResQ', 'resq-clean-pro' ); ?></span>
		<h2 id="resq-social-title" class="resq-social-proof__headline">
			<?php esc_html_e( 'Gentle care, loved every day.', 'resq-clean-pro' ); ?>
		</h2>
	</div>

	<div class="resq-social-proof__cards">
		<article class="resq-story-card">
			<div class="resq-story-card__media">
				<div class="resq-before-after">
					<div class="resq-before-after__panel">
						<?php
						resq_theme_render_image(
							'dog-skin-care-healing-before-after',
							array(
								'class'       => 'resq-img-placeholder',
								'image_class' => 'resq-before-after__image',
								'size'        => 'medium',
								'alt'         => __( 'Before pet skin comfort comparison.', 'resq-clean-pro' ),
								'label'       => __( 'Before', 'resq-clean-pro' ),
							)
						);
						?>
					</div>
					<div class="resq-before-after__panel">
						<?php
						resq_theme_render_image(
							'horse-skin-care-vet-recommended-before-after',
							array(
								'class'               => 'resq-img-placeholder',
								'image_class'         => 'resq-before-after__image',
								'size'                => 'medium',
								'alt'                 => __( 'After pet skin comfort comparison.', 'resq-clean-pro' ),
								'label'               => __( 'After', 'resq-clean-pro' ),
								'placeholder_variant' => 'resq-img-placeholder--pet',
							)
						);
						?>
					</div>
				</div>
			</div>
			<div class="resq-story-card__body">
				<h3 class="resq-story-card__title"><?php esc_html_e( 'Customer stories', 'resq-clean-pro' ); ?></h3>
				<p class="resq-story-card__copy">
					<?php esc_html_e( 'Chosen daily by families and pet lovers alike. Our clean, fragrance-free formulations are made to support everyday comfort as part of a gentle care routine.', 'resq-clean-pro' ); ?>
				</p>
				<a class="resq-story-card__cta" href="<?php echo esc_url( $stories_url ); ?>">
					<?php esc_html_e( 'Read customer stories', 'resq-clean-pro' ); ?>
				</a>
			</div>
		</article>

		<article class="resq-mission-pledge resq-mission-pledge--card" aria-labelledby="resq-pledge-title">
			<span class="resq-mission-pledge__badge"><?php esc_html_e( 'Giving back', 'resq-clean-pro' ); ?></span>
			<h3 id="resq-pledge-title" class="resq-mission-pledge__headline"><?php esc_html_e( 'The ResQ rescue pledge', 'resq-clean-pro' ); ?></h3>
			<p class="resq-mission-pledge__copy">
				<?php esc_html_e( 'A portion of every pet-care purchase supports animal shelters and rescue groups nationwide — helping rescued animals get the gentle care they need.', 'resq-clean-pro' ); ?>
			</p>
			<a class="resq-mission-pledge__cta" href="<?php echo esc_url( $partners_url ); ?>">
				<?php esc_html_e( 'See our rescue partners', 'resq-clean-pro' ); ?>
			</a>
		</article>

		<article class="resq-promise-card">
			<div class="resq-promise-card__icon" aria-hidden="true">&#10003;</div>
			<h3 class="resq-promise-card__title"><?php esc_html_e( 'Clean-formula promise', 'resq-clean-pro' ); ?></h3>
			<p class="resq-promise-card__copy">
				<?php esc_html_e( 'Fragrance-free, pH-balanced formulas with a satisfaction-focused return policy. If your routine doesn’t feel right, contact us — individual results vary.', 'resq-clean-pro' ); ?>
			</p>
			<a class="resq-promise-card__cta" href="<?php echo esc_url( $stories_url ); ?>">
				<?php esc_html_e( 'Help & support', 'resq-clean-pro' ); ?>
			</a>
		</article>
	</div>
</section>
