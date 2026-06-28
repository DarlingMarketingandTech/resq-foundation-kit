<?php
/**
 * Homepage — Doorway split (dual audience entry).
 *
 * High-contrast 2-column layout that filters traffic by shopping intent.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $args {
 *     @type string $human_url Women's skincare landing URL.
 *     @type string $pet_url   Pet topical skin care landing URL.
 * }
 */

defined( 'ABSPATH' ) || exit;

$human_url = isset( $args['human_url'] ) ? (string) $args['human_url'] : home_url( '/shop/human/womens-skincare/' );
$pet_url   = isset( $args['pet_url'] ) ? (string) $args['pet_url'] : home_url( '/shop/pet/topical-skin-care/' );
?>

<section class="resq-home-section resq-home-section--cream" aria-label="<?php esc_attr_e( 'Choose your routine', 'resq-clean-pro' ); ?>">
	<div class="resq-doorway__inner">

		<article class="resq-doorway__card">
			<?php
			resq_theme_render_image(
				'womens-skincare-bathroom-lifestyle',
				array(
					'class'       => 'resq-doorway__card-image',
					'image_class' => 'resq-doorway__image',
					'size'        => 'medium_large',
					'alt'         => __( 'Women’s skincare products styled in a clean bathroom routine setting.', 'resq-clean-pro' ),
					'label'       => __( 'The Daily Radiance Routine', 'resq-clean-pro' ),
				)
			);
			?>
			<div class="resq-doorway__card-body">
				<h2 class="resq-doorway__card-headline"><?php esc_html_e( 'Advanced human skincare.', 'resq-clean-pro' ); ?></h2>
				<p class="resq-doorway__card-sub">
					<?php esc_html_e( 'Cleanse, hydrate, and support your skin barrier with pure botanical ingredients.', 'resq-clean-pro' ); ?>
				</p>
				<a class="resq-doorway__card-cta" href="<?php echo esc_url( $human_url ); ?>">
					<?php esc_html_e( 'Explore human routines', 'resq-clean-pro' ); ?>
				</a>
			</div>
		</article>

		<article class="resq-doorway__card">
			<?php
			resq_theme_render_image(
				'dog-skin-care-gentle-effective-lifestyle',
				array(
					'class'               => 'resq-doorway__card-image',
					'image_class'         => 'resq-doorway__image',
					'size'                => 'medium_large',
					'alt'                 => __( 'A calm dog resting outdoors in a gentle topical care lifestyle scene.', 'resq-clean-pro' ),
					'label'               => __( 'Soothed & Calm Companion', 'resq-clean-pro' ),
					'placeholder_variant' => 'resq-img-placeholder--pet',
				)
			);
			?>
			<div class="resq-doorway__card-body">
				<h2 class="resq-doorway__card-headline"><?php esc_html_e( 'Lick-safe pet topical care.', 'resq-clean-pro' ); ?></h2>
				<p class="resq-doorway__card-sub">
					<?php esc_html_e( 'Gentle, non-toxic comfort for hot spots, dry noses, and sensitive-feeling skin as part of a care routine.', 'resq-clean-pro' ); ?>
				</p>
				<a class="resq-doorway__card-cta" href="<?php echo esc_url( $pet_url ); ?>">
					<?php esc_html_e( 'Explore pet care routines', 'resq-clean-pro' ); ?>
				</a>
			</div>
		</article>

	</div>
</section>
