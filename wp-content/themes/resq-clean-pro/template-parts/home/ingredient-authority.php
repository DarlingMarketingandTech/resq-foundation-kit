<?php
/**
 * Homepage — Flagship ingredient authority / Manuka proof block.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $args {
 *     @type string $learn_url Learn hub URL.
 * }
 */

defined( 'ABSPATH' ) || exit;

$learn_url    = isset( $args['learn_url'] ) ? (string) $args['learn_url'] : home_url( '/learn/' );
$proof_cards  = function_exists( 'resq_theme_get_home_proof_cards' ) ? resq_theme_get_home_proof_cards() : array();
$manuka_url   = function_exists( 'resq_theme_get_home_manuka_product_url' ) ? resq_theme_get_home_manuka_product_url() : $learn_url;
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

			<div class="resq-proof-cards" role="list">
				<?php foreach ( $proof_cards as $card ) : ?>
					<article class="resq-proof-card" role="listitem">
						<span class="resq-proof-card__icon" aria-hidden="true"><?php echo wp_kses_post( (string) ( $card['icon'] ?? '' ) ); ?></span>
						<h3 class="resq-proof-card__title"><?php echo esc_html( (string) ( $card['title'] ?? '' ) ); ?></h3>
						<p class="resq-proof-card__body"><?php echo esc_html( (string) ( $card['body'] ?? '' ) ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>

			<div class="resq-ingredient-authority__actions">
				<a class="resq-ingredient-authority__cta" href="<?php echo esc_url( $learn_url ); ?>">
					<?php esc_html_e( 'Learn about our science', 'resq-clean-pro' ); ?>
				</a>
				<a class="resq-ingredient-authority__cta resq-ingredient-authority__cta--secondary" href="<?php echo esc_url( $manuka_url ); ?>">
					<?php esc_html_e( 'Shop UMF Manuka honey', 'resq-clean-pro' ); ?>
				</a>
			</div>
		</div>

		<div class="resq-ingredient-authority__media">
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
	</div>
</section>
