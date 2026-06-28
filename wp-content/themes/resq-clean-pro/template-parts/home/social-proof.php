<?php
/**
 * Homepage — Social proof, transparency & shelter mission.
 *
 * Two-column block: comfort-focused proof + the rescue pledge.
 * Compliance: comfort/cosmetic language only; mission copy uses "portion of
 * purchases" framing, no buy-1-give-1 mechanism or medical claims (docs/05).
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $args {
 *     @type string $stories_url  Customer stories / testimonials URL.
 *     @type string $partners_url Rescue partners documentation URL.
 * }
 */

defined( 'ABSPATH' ) || exit;

$stories_url  = isset( $args['stories_url'] ) ? (string) $args['stories_url'] : home_url( '/learn/' );
$partners_url = isset( $args['partners_url'] ) ? (string) $args['partners_url'] : home_url( '/learn/' );
?>

<section class="resq-home-section resq-home-section--cream-deep" aria-labelledby="resq-social-title">
	<div class="resq-social-proof__inner">
		<h2 id="resq-social-title" class="resq-social-proof__headline"><?php esc_html_e( 'Gentle care, loved every day.', 'resq-clean-pro' ); ?></h2>

		<div class="resq-social-proof__results">
			<div class="resq-before-after">
				<div class="resq-before-after__panel">
					<?php
					resq_theme_render_image(
						'dog-skin-care-healing-before-after',
						array(
							'class'       => 'resq-img-placeholder',
							'image_class' => 'resq-before-after__image',
							'size'        => 'medium',
							'alt'         => __( 'Before and after pet skin comfort comparison.', 'resq-clean-pro' ),
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
							'alt'                 => __( 'Before and after horse skin comfort comparison.', 'resq-clean-pro' ),
							'label'               => __( 'After', 'resq-clean-pro' ),
							'placeholder_variant' => 'resq-img-placeholder--pet',
						)
					);
					?>
				</div>
			</div>

			<div class="resq-social-proof__results-body">
				<p class="resq-social-proof__copy">
					<?php esc_html_e( 'Chosen daily by families and pet lovers alike. Our clean, fragrance-free formulations are made to support everyday comfort as part of a gentle care routine.', 'resq-clean-pro' ); ?>
				</p>

				<div class="resq-social-proof__guarantee">
					<span class="resq-social-proof__guarantee-icon" aria-hidden="true">&#10003;</span>
					<span class="resq-social-proof__guarantee-text">
						<strong><?php esc_html_e( 'Satisfaction-focused return policy', 'resq-clean-pro' ); ?></strong>
						<?php esc_html_e( 'If your routine doesn’t feel right for you, contact us — individual results vary.', 'resq-clean-pro' ); ?>
					</span>
				</div>

				<a class="resq-social-proof__cta" href="<?php echo esc_url( $stories_url ); ?>">
					<?php esc_html_e( 'Read our customer stories', 'resq-clean-pro' ); ?>
				</a>
			</div>
		</div>

		<aside class="resq-mission-pledge" aria-labelledby="resq-pledge-title">
			<span class="resq-mission-pledge__badge"><?php esc_html_e( 'Giving back', 'resq-clean-pro' ); ?></span>
			<h3 id="resq-pledge-title" class="resq-mission-pledge__headline"><?php esc_html_e( 'The ResQ rescue pledge.', 'resq-clean-pro' ); ?></h3>
			<p class="resq-mission-pledge__copy">
				<?php esc_html_e( 'A portion of every pet-care purchase supports animal shelters and rescue groups nationwide — helping rescued animals get the gentle care they need.', 'resq-clean-pro' ); ?>
			</p>

			<div class="resq-mission-pledge__stat">
				<span class="resq-mission-pledge__stat-number" aria-hidden="true">&#10084;</span>
				<span class="resq-mission-pledge__stat-label"><?php esc_html_e( 'Part of what you spend on pet care goes toward shelters and rescue partners across the country.', 'resq-clean-pro' ); ?></span>
			</div>

			<a class="resq-mission-pledge__cta" href="<?php echo esc_url( $partners_url ); ?>">
				<?php esc_html_e( 'See our rescue partners', 'resq-clean-pro' ); ?>
			</a>
		</aside>
	</div>
</section>
