<?php
/**
 * Homepage — Search by skin concern (symptom-led problem lanes).
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $args {
 *     @type string $human_base Human gateway base URL (trailing slash).
 *     @type string $pet_base   Pet gateway base URL (trailing slash).
 * }
 */

defined( 'ABSPATH' ) || exit;

$human_base = isset( $args['human_base'] ) ? trailingslashit( (string) $args['human_base'] ) : home_url( '/shop/human/' );
$pet_base   = isset( $args['pet_base'] ) ? trailingslashit( (string) $args['pet_base'] ) : home_url( '/shop/pet/' );

$groups = function_exists( 'resq_theme_get_home_concern_groups' )
	? resq_theme_get_home_concern_groups( $human_base, $pet_base )
	: array();
?>

<section class="resq-home-section resq-home-section--concern" aria-labelledby="resq-concerns-title">
	<div class="resq-concern-lanes__header resq-home-section__inner">
		<span class="resq-section-label resq-section-label--sage"><?php esc_html_e( 'Concern finder', 'resq-clean-pro' ); ?></span>
		<h2 id="resq-concerns-title" class="resq-concern-lanes__title">
			<?php esc_html_e( 'Search by skin concern', 'resq-clean-pro' ); ?>
		</h2>
		<p class="resq-concern-lanes__lede">
			<?php esc_html_e( 'Choose a comfort goal to jump straight into the routines and products that match.', 'resq-clean-pro' ); ?>
		</p>
	</div>

	<?php foreach ( $groups as $group ) : ?>
		<div class="resq-concern-lanes__group">
			<h3 class="resq-concern-lanes__group-title"><?php echo esc_html( (string) ( $group['label'] ?? '' ) ); ?></h3>

			<div class="resq-concern-lanes__grid">
				<?php foreach ( (array) ( $group['concerns'] ?? array() ) as $concern ) : ?>
					<a class="resq-concern-card" href="<?php echo esc_url( (string) ( $concern['url'] ?? '#' ) ); ?>">
						<?php
						resq_theme_render_image(
							(string) ( $concern['image_slug'] ?? '' ),
							array(
								'class'               => 'resq-concern-card__image',
								'image_class'         => 'resq-concern-card__img',
								'size'                => 'medium',
								'alt'                 => (string) ( $concern['alt'] ?? '' ),
								'label'               => (string) ( $concern['image'] ?? '' ),
								'placeholder_variant' => (string) ( $concern['variant'] ?? '' ),
							)
						);
						?>
						<div class="resq-concern-card__body">
							<span class="resq-concern-card__label"><?php echo esc_html( (string) ( $concern['label'] ?? '' ) ); ?></span>
							<span class="resq-concern-card__cta">
								<?php esc_html_e( 'Shop routines', 'resq-clean-pro' ); ?>
								<span class="resq-concern-card__arrow" aria-hidden="true">&rarr;</span>
							</span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endforeach; ?>
</section>
