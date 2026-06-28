<?php
/**
 * Target Problem Lane & category landing template.
 *
 * Loaded via ResQ_Core_Lane_Routing::load_lane_template() for
 * /shop/{audience}/{category}/{problem}/ routes.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

get_header();

$lane = function_exists( 'resq_get_lane_from_request' ) ? resq_get_lane_from_request() : null;

if ( null === $lane ) {
	get_template_part( '404' );
	get_footer();
	return;
}
?>
<main id="primary-content" class="site-main resq-container" role="main">
	<?php
	resq_theme_template_part(
		'lane/page-shell',
		'',
		array(
			'lane' => $lane,
		)
	);
	?>
</main>
<?php
get_footer();
