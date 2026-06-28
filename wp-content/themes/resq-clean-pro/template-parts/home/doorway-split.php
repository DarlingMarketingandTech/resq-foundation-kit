<?php
/**
 * Homepage — Shop pathway cards.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

$pathways = function_exists( 'resq_theme_get_home_pathway_cards' )
	? resq_theme_get_home_pathway_cards()
	: array();
?>

<section class="resq-home-section resq-home-section--cream" aria-labelledby="resq-pathways-title">
	<div class="resq-home-section__inner resq-pathway__header">
		<span class="resq-section-label"><?php esc_html_e( 'Shop by pathway', 'resq-clean-pro' ); ?></span>
		<h2 id="resq-pathways-title" class="resq-pathway__title">
			<?php esc_html_e( 'Start with the routine that fits your household.', 'resq-clean-pro' ); ?>
		</h2>
	</div>

	<div class="resq-pathway__grid">
		<?php foreach ( $pathways as $pathway ) : ?>
			<?php
			$is_compact = ! empty( $pathway['is_compact'] );
			$card_class = 'resq-pathway__card';
			if ( $is_compact ) {
				$card_class .= ' resq-pathway__card--compact';
			}
			?>
			<a
				class="<?php echo esc_attr( $card_class ); ?>"
				href="<?php echo esc_url( (string) ( $pathway['url'] ?? '#' ) ); ?>"
			>
				<?php
				resq_theme_render_image(
					(string) ( $pathway['image_slug'] ?? '' ),
					array(
						'class'               => 'resq-pathway__card-image',
						'image_class'         => 'resq-pathway__image',
						'size'                => 'medium_large',
						'alt'                 => (string) ( $pathway['image_alt'] ?? '' ),
						'label'               => (string) ( $pathway['image_label'] ?? '' ),
						'placeholder_variant' => (string) ( $pathway['variant'] ?? '' ),
					)
				);
				?>
				<div class="resq-pathway__card-body">
					<h3 class="resq-pathway__card-headline"><?php echo esc_html( (string) ( $pathway['title'] ?? '' ) ); ?></h3>
					<p class="resq-pathway__card-sub"><?php echo esc_html( (string) ( $pathway['description'] ?? '' ) ); ?></p>
					<span class="resq-pathway__card-cta">
						<?php echo esc_html( (string) ( $pathway['cta'] ?? __( 'Shop now', 'resq-clean-pro' ) ) ); ?>
						<span aria-hidden="true">&rarr;</span>
					</span>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</section>
