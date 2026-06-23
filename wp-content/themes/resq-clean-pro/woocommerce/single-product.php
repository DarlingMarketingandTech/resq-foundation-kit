<?php
/**
 * Single product page shell.
 *
 * Wraps the WooCommerce product loop in the theme layout. PDP slot template
 * parts (routine ladder, ingredient profile, FBT, compliance) are hooked via
 * inc/woocommerce.php rather than embedded here.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>
<main id="primary-content" class="site-main resq-container resq-single-product" role="main">

	<?php do_action( 'woocommerce_before_main_content' ); ?>

	<?php
	while ( have_posts() ) :
		the_post();
		wc_get_template_part( 'content', 'single-product' );
	endwhile;
	?>

	<?php do_action( 'woocommerce_after_main_content' ); ?>

	<?php do_action( 'woocommerce_sidebar' ); ?>

</main>
<?php
get_footer( 'shop' );
