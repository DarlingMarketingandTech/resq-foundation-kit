<?php
/**
 * Template Name: ResQ Learn Index
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		$learn_context = array();

		if ( function_exists( 'resq_resolve_product_context' ) ) {
			$learn_context = resq_resolve_product_context( get_the_ID(), 'learn' );
		}

		$bridge_product_ids = array();

		if ( ! empty( $learn_context['canonical_targets'] ) && is_array( $learn_context['canonical_targets'] ) ) {
			$bridge_product_ids = $learn_context['canonical_targets'];
		} elseif ( function_exists( 'resq_get_gateway_featured_products' ) ) {
			$bridge_product_ids = resq_get_gateway_featured_products( 'learn' );
		}

		$learn_post_types = post_type_exists( 'resq_learn_guide' ) ? array( 'resq_learn_guide' ) : array( 'post', 'page' );

		$guide_query = new WP_Query(
			array(
				'post_type'      => $learn_post_types,
				'post_status'    => 'publish',
				'post_parent'    => get_the_ID(),
				'posts_per_page' => 50,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'post__not_in'   => array( get_the_ID() ),
			)
		);
		?>
		<main id="primary-content" class="site-main resq-container" role="main">
			<div class="resq-learn-index">
				<header class="resq-learn-index__hero">
					<h1 class="resq-learn-index__title"><?php the_title(); ?></h1>

					<?php if ( get_the_content() ) : ?>
						<div class="resq-gateway__hero-intro entry-content">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>
				</header>

				<?php if ( $guide_query->have_posts() ) : ?>
					<section class="resq-gateway__section" aria-labelledby="resq-learn-guide-grid-heading">
						<h2 id="resq-learn-guide-grid-heading" class="resq-gateway__section-title">
							<?php esc_html_e( 'Guides', 'resq-clean-pro' ); ?>
						</h2>

						<div class="resq-learn-guide-grid">
							<?php
							while ( $guide_query->have_posts() ) :
								$guide_query->the_post();
								resq_theme_template_part(
									'learn/guide-card',
									'',
									array(
										'post' => get_post(),
									)
								);
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</section>
				<?php endif; ?>

				<?php
				resq_theme_template_part(
					'learn/product-bridge',
					'',
					array(
						'product_ids'    => $bridge_product_ids,
						'source_post_id' => get_the_ID(),
					)
				);
				?>
			</div>
		</main>
		<?php
	endwhile;
endif;

get_footer();
