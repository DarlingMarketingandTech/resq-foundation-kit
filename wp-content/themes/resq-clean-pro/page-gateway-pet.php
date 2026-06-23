<?php
/**
 * Template Name: ResQ Gateway: Pet
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<main id="primary-content" class="site-main resq-container" role="main">
			<?php
			resq_theme_template_part(
				'gateway/page-shell',
				'',
				array(
					'gateway_slug' => 'pet',
					'is_cbd'       => false,
				)
			);
			?>
		</main>
		<?php
	endwhile;
endif;

get_footer();
