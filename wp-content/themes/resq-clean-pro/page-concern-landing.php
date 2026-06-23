<?php
/**
 * Template Name: ResQ Concern Landing
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		$context = array();

		if ( function_exists( 'resq_resolve_product_context' ) ) {
			$context = resq_resolve_product_context( get_the_ID(), 'page' );
		}

		$product_ids = array();

		if ( ! empty( $context['canonical_targets'] ) && is_array( $context['canonical_targets'] ) ) {
			$product_ids = $context['canonical_targets'];
		} elseif ( ! empty( $context['featured_products'] ) && is_array( $context['featured_products'] ) ) {
			$product_ids = $context['featured_products'];
		}
		?>
		<main id="primary-content" class="site-main resq-container" role="main">
			<div class="resq-gateway">
				<?php
				resq_theme_template_part(
					'gateway/hero',
					'',
					array(
						'gateway_slug' => '',
						'context'      => $context,
					)
				);

				resq_theme_template_part(
					'gateway/filter-shell',
					'',
					array(
						'context' => $context,
					)
				);

				resq_theme_template_part(
					'gateway/product-shelf',
					'',
					array(
						'product_ids' => $product_ids,
					)
				);
				?>
			</div>
		</main>
		<?php
	endwhile;
endif;

get_footer();
