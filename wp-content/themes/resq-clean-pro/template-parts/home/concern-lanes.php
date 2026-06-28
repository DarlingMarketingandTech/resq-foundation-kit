<?php

/**

 * Homepage — Search by skin concern (symptom-led problem lanes).

 *

 * 4-column grid of clickable concern cards routing to Target Problem Lanes.

 * Compliance: comfort/condition language only, no disease claims (docs/05).

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



/**

 * Resolve a lane URL when the plugin helper is available.

 *

 * @param string $audience Audience slug.

 * @param string $category Category slug.

 * @param string $problem  Optional problem slug.

 * @param string $fallback Fallback URL.

 * @return string

 */

$resolve_lane = static function ( string $audience, string $category, string $problem, string $fallback ): string {

	if ( function_exists( 'resq_resolve_lane_url' ) ) {

		return resq_resolve_lane_url( $audience, $category, $problem );

	}

	return $fallback;

};



$concerns = array(

	array(

		'label'   => __( 'Dry, sensitive-feeling skin', 'resq-clean-pro' ),

		'url'     => $resolve_lane( 'human', 'therapeutic-skin-care', '', $human_base . 'therapeutic-skin-care/' ),

		'image'   => __( 'Comfort Care Grid', 'resq-clean-pro' ),

		'image_slug' => 'ingredients-cream-honey-aloe-triptych',

		'alt'     => __( 'Macro texture of white cream smoothing over dry, rough skin.', 'resq-clean-pro' ),

		'variant' => '',

	),

	array(

		'label'   => __( 'Post-shave razor burn', 'resq-clean-pro' ),

		'url'     => $resolve_lane( 'human', 'mens-grooming', 'razor-burn', $human_base . 'mens-grooming/' ),

		'image'   => __( 'Post-Shave Comfort Care', 'resq-clean-pro' ),

		'image_slug' => 'mens-skin-care-cream-hero-splash',

		'alt'     => __( 'Gray and amber grooming bottles on dark, wet slate.', 'resq-clean-pro' ),

		'variant' => 'resq-img-placeholder--dark',

	),

	array(

		'label'   => __( 'Baby diaper rash & chafing', 'resq-clean-pro' ),

		'url'     => $resolve_lane( 'human', 'baby-infant-care', 'diaper-rash', $human_base . 'baby-infant-care/' ),

		'image'   => __( 'Gentle Barrier Care', 'resq-clean-pro' ),

		'image_slug' => 'baby-skin-treatment-lifestyle',

		'alt'     => __( 'A parent’s hands gently applying protective cream to delicate infant skin.', 'resq-clean-pro' ),

		'variant' => '',

	),

	array(

		'label'   => __( 'Pet dry paws & cracks', 'resq-clean-pro' ),

		'url'     => $resolve_lane( 'pet', 'topical-skin-care', 'hyperkeratosis', $pet_base . 'topical-skin-care/' ),

		'image'   => __( 'The Snout & Paw Ritual', 'resq-clean-pro' ),

		'image_slug' => 'pet-topical-care-hyperkeratosis-lifestyle',

		'alt'     => __( 'A dog resting peacefully with a hydrated nose and comfortable-looking paw pads.', 'resq-clean-pro' ),

		'variant' => 'resq-img-placeholder--pet',

	),

);

?>



<section class="resq-home-section" aria-labelledby="resq-concerns-title">

	<div class="resq-concern-lanes__header resq-home-section__inner">

		<span class="resq-section-label resq-section-label--sage"><?php esc_html_e( 'Concern finder', 'resq-clean-pro' ); ?></span>

		<h2 id="resq-concerns-title" class="resq-concern-lanes__title"><?php esc_html_e( 'Search by skin concern.', 'resq-clean-pro' ); ?></h2>

	</div>



	<div class="resq-concern-lanes__grid">

		<?php foreach ( $concerns as $concern ) : ?>

			<a class="resq-concern-card" href="<?php echo esc_url( (string) $concern['url'] ); ?>">

				<?php
				resq_theme_render_image(
					(string) ( $concern['image_slug'] ?? '' ),
					array(
						'class'               => 'resq-concern-card__image',
						'image_class'         => 'resq-concern-card__img',
						'size'                => 'medium',
						'alt'                 => (string) $concern['alt'],
						'label'               => (string) $concern['image'],
						'placeholder_variant' => (string) $concern['variant'],
					)
				);
				?>

				<div class="resq-concern-card__body">

					<span class="resq-concern-card__label"><?php echo esc_html( (string) $concern['label'] ); ?></span>

					<span class="resq-concern-card__arrow" aria-hidden="true">&rarr;</span>

				</div>

			</a>

		<?php endforeach; ?>

	</div>

</section>


