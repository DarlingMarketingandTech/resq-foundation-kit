<?php
/**
 * Learn guide card for index grids.
 *
 * @package ResQ_Clean_Pro
 *
 * @var WP_Post $post Learn guide post object.
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $post ) || ! $post instanceof WP_Post ) {
	return;
}

$permalink = get_permalink( $post );
$excerpt   = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 24, '...' );
?>

<article class="resq-learn-guide-card">
	<a class="resq-learn-guide-card__link" href="<?php echo esc_url( $permalink ); ?>">
		<?php echo esc_html( get_the_title( $post ) ); ?>
	</a>

	<?php if ( $excerpt ) : ?>
		<p class="resq-learn-guide-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
	<?php endif; ?>

	<span class="resq-learn-guide-card__cta"><?php esc_html_e( 'Read guide', 'resq-clean-pro' ); ?></span>
</article>
