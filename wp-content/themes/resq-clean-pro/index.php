<?php
/**
 * Main template file.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

get_header();
if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
endif;
get_footer();
