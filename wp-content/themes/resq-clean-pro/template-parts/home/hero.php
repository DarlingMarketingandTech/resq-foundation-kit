<?php
/**
 * Homepage — Hero banner (above the fold).
 *
 * "One Cabinet, Not Two." Unified masterbrand entry with dual-audience CTAs.
 * Compliance: comfort language only, no CBD references (docs/05).
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $args {
 *     @type string $human_url Resolved Shop For Humans URL.
 *     @type string $pet_url   Resolved Shop For Pets URL.
 * }
 */

defined( 'ABSPATH' ) || exit;

$human_url = isset( $args['human_url'] ) ? (string) $args['human_url'] : home_url( '/shop/human/' );
$pet_url   = isset( $args['pet_url'] ) ? (string) $args['pet_url'] : home_url( '/shop/pet/' );
?>

<section class="resq-hero" aria-labelledby="resq-hero-title">
	<div class="resq-hero__inner">
		<div class="resq-hero__content">
			<span class="resq-section-label"><?php esc_html_e( 'One Cabinet, Not Two', 'resq-clean-pro' ); ?></span>

			<h1 id="resq-hero-title" class="resq-hero__headline">
				<?php esc_html_e( 'Pure botanical care for the entire household.', 'resq-clean-pro' ); ?>
			</h1>

			<p class="resq-hero__subheadline">
				<?php esc_html_e( 'Formulated with raw Manuka honey and pure aloe vera. We build 5.5 pH-balanced, fragrance-free formulas that support lasting moisture and everyday skin-barrier comfort for you, your children, and your pets.', 'resq-clean-pro' ); ?>
			</p>

			<div class="resq-hero__actions">
				<a class="resq-hero__cta--primary" href="<?php echo esc_url( $human_url ); ?>">
					<?php esc_html_e( 'Shop for Humans', 'resq-clean-pro' ); ?>
				</a>
				<a class="resq-hero__cta--secondary" href="<?php echo esc_url( $pet_url ); ?>">
					<?php esc_html_e( 'Shop for Pets', 'resq-clean-pro' ); ?>
				</a>
			</div>

			<ul class="resq-hero__trust-strip" aria-label="<?php esc_attr_e( 'Product promises', 'resq-clean-pro' ); ?>">
				<li class="resq-hero__trust-item">
					<span class="resq-hero__trust-icon" aria-hidden="true">&#127855;</span>
					<?php esc_html_e( 'Raw Manuka honey', 'resq-clean-pro' ); ?>
				</li>
				<li class="resq-hero__trust-item">
					<span class="resq-hero__trust-icon" aria-hidden="true">&#127807;</span>
					<?php esc_html_e( '5.5 pH-balanced & fragrance-free', 'resq-clean-pro' ); ?>
				</li>
				<li class="resq-hero__trust-item">
					<span class="resq-hero__trust-icon" aria-hidden="true">&#128062;</span>
					<?php esc_html_e( 'Lick-safe for pets', 'resq-clean-pro' ); ?>
				</li>
			</ul>
		</div>

		<?php
		resq_theme_render_image(
			'ingredients-cream-honey-aloe-triptych',
			array(
				'class'       => 'resq-hero__image',
				'image_class' => 'resq-hero__img',
				'size'        => 'large',
				'alt'         => __( 'Raw Manuka honey, aloe, and cream textures styled as the botanical foundation of a household routine.', 'resq-clean-pro' ),
				'label'       => __( 'The Shared Cabinet Lifestyle', 'resq-clean-pro' ),
				'loading'     => 'eager',
			)
		);
		?>
	</div>
</section>
