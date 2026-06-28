<?php
/**
 * Homepage — Flagship ingredient authority block.
 *
 * Editorial widescreen section establishing Manuka honey purity + 5.5 pH story.
 * Compliance: comfort language only, links to /learn education hub (docs/05).
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $args {
 *     @type string $learn_url Learn hub URL.
 * }
 */

defined( 'ABSPATH' ) || exit;

$learn_url = isset( $args['learn_url'] ) ? (string) $args['learn_url'] : home_url( '/learn/' );
?>

<section class="resq-home-section resq-home-section--sage" aria-labelledby="resq-ingredient-title">
	<div class="resq-ingredient-authority__inner">
		<div class="resq-ingredient-authority__content">
			<span class="resq-section-label resq-section-label--sage"><?php esc_html_e( 'The botanical foundation', 'resq-clean-pro' ); ?></span>

			<h2 id="resq-ingredient-title" class="resq-ingredient-authority__headline">
				<?php
				printf(
					/* translators: %s: emphasized ingredient phrase */
					esc_html__( 'Formulated with %s.', 'resq-clean-pro' ),
					'<em>' . esc_html__( 'raw Manuka honey', 'resq-clean-pro' ) . '</em>'
				);
				?>
			</h2>

			<p class="resq-ingredient-authority__body">
				<?php esc_html_e( 'The foundation of every ResQ formula is raw Manuka honey. Unlike standard cosmetics built around water and synthetic fillers, our products are engineered to mirror the skin’s natural 5.5 pH balance — delivering clean, lasting moisture that supports the outer skin barrier with a gentle-use intent.', 'resq-clean-pro' ); ?>
			</p>

			<ul class="resq-ingredient-authority__badges" aria-label="<?php esc_attr_e( 'Formulation standards', 'resq-clean-pro' ); ?>">
				<li class="resq-ingredient-badge"><?php esc_html_e( 'UMF Certified', 'resq-clean-pro' ); ?></li>
				<li class="resq-ingredient-badge"><?php esc_html_e( '5.5 pH Balanced', 'resq-clean-pro' ); ?></li>
				<li class="resq-ingredient-badge"><?php esc_html_e( 'Fragrance-Free', 'resq-clean-pro' ); ?></li>
				<li class="resq-ingredient-badge"><?php esc_html_e( 'Gentle-use intent', 'resq-clean-pro' ); ?></li>
			</ul>

			<a class="resq-ingredient-authority__cta" href="<?php echo esc_url( $learn_url ); ?>">
				<?php esc_html_e( 'Learn about our science', 'resq-clean-pro' ); ?>
			</a>
		</div>

		<?php
		resq_theme_render_image(
			'ingredients-cream-honey-aloe-triptych',
			array(
				'class'       => 'resq-ingredient-authority__image',
				'image_class' => 'resq-ingredient-authority__img',
				'size'        => 'large',
				'alt'         => __( 'Studio photograph of raw Manuka honey, aloe vera, and cream textures.', 'resq-clean-pro' ),
				'label'       => __( 'The Botanical Wellness Ritual', 'resq-clean-pro' ),
			)
		);
		?>
	</div>
</section>
