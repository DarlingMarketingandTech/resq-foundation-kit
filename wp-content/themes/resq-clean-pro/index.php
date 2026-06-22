<?php
/**
 * Main template file.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="primary-content" class="site-main resq-container" role="main">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;
	endif;
	?>
</main>
<?php
get_footer();
