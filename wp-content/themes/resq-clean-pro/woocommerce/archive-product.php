<?php
/**
 * Shop and category archive shell.
 *
 * Preserves all standard WooCommerce action hooks. Adds resq-* wrapper
 * classes and a plugin-guarded compliance notice slot after the archive
 * description.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>
<main id="primary-content" class="site-main resq-container resq-shop-archive" role="main">

	<?php do_action( 'woocommerce_before_main_content' ); ?>

	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title">
			<?php woocommerce_page_title(); ?>
		</h1>
	<?php endif; ?>

	<?php do_action( 'woocommerce_archive_description' ); ?>

	<?php resq_theme_render_compliance_notices( 'category' ); ?>

	<?php if ( woocommerce_product_loop() ) : ?>

		<?php do_action( 'woocommerce_before_shop_loop' ); ?>

		<?php woocommerce_product_loop_start(); ?>

		<?php if ( wc_get_loop_prop( 'total' ) ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				do_action( 'woocommerce_shop_loop' );
				wc_get_template_part( 'content', 'product' );
			endwhile;
			?>
		<?php endif; ?>

		<?php woocommerce_product_loop_end(); ?>

		<?php do_action( 'woocommerce_after_shop_loop' ); ?>

	<?php else : ?>

		<?php do_action( 'woocommerce_no_products_found' ); ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_main_content' ); ?>

	<?php do_action( 'woocommerce_sidebar' ); ?>

</main>
<?php
get_footer( 'shop' );
