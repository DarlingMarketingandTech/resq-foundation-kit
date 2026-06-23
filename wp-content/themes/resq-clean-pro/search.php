<?php
/**
 * Search results template.
 *
 * Renders WooCommerce product results using the product card template part,
 * then falls back to standard post excerpts for non-product content.
 * Handles empty results without PHP notices.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="primary-content" class="site-main resq-container resq-search" role="main">

	<header class="resq-search__header">
		<h1 class="resq-search__title">
			<?php
			printf(
				/* translators: %s: search query */
				esc_html__( 'Search results for: %s', 'resq-clean-pro' ),
				'<span class="resq-search__query">' . esc_html( get_search_query() ) . '</span>'
			);
			?>
		</h1>
	</header>

	<?php if ( have_posts() ) : ?>

		<div class="resq-search__results">

			<?php
			while ( have_posts() ) :
				the_post();

				if ( resq_theme_wc_active() && 'product' === get_post_type() ) :
					?>
					<div class="resq-search__result resq-search__result--product">
						<?php
						global $product;
						$product = wc_get_product( get_the_ID() );
						if ( $product && is_a( $product, 'WC_Product' ) ) {
							wc_get_template_part( 'content', 'product' );
						}
						?>
					</div>
					<?php
				else :
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'resq-search__result resq-search__result--content' ); ?>>
						<header class="entry-header">
							<h2 class="entry-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
						</header>
						<div class="entry-excerpt">
							<?php the_excerpt(); ?>
						</div>
					</article>
					<?php
				endif;

			endwhile;
			?>

		</div>

		<?php the_posts_pagination(); ?>

	<?php else : ?>

		<p class="resq-search__empty"><?php esc_html_e( 'No results found. Try a different search term.', 'resq-clean-pro' ); ?></p>

		<?php get_search_form(); ?>

	<?php endif; ?>

</main>
<?php
get_footer();
