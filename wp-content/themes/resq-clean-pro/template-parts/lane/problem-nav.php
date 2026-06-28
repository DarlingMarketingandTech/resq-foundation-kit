<?php
/**
 * Category landing problem-lane navigation grid.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $links Problem lane links from resq_get_lane_problem_links().
 */

defined( 'ABSPATH' ) || exit;

$links = isset( $links ) && is_array( $links ) ? $links : array();

if ( empty( $links ) ) {
	return;
}
?>

<section class="resq-lane-problem-nav" aria-labelledby="resq-lane-problem-nav-title">
	<h2 id="resq-lane-problem-nav-title" class="resq-lane-problem-nav__title">
		<?php esc_html_e( 'Shop by concern', 'resq-clean-pro' ); ?>
	</h2>

	<ul class="resq-lane-problem-nav__grid">
		<?php foreach ( $links as $link ) : ?>
			<?php
			if ( ! is_array( $link ) ) {
				continue;
			}
			$label  = (string) ( $link['label'] ?? '' );
			$url    = (string) ( $link['url'] ?? '' );
			$status = (string) ( $link['status'] ?? 'approved' );
			if ( '' === $label || '' === $url ) {
				continue;
			}
			?>
			<li class="resq-lane-problem-nav__item">
				<a class="resq-lane-problem-nav__link" href="<?php echo esc_url( $url ); ?>">
					<span class="resq-lane-problem-nav__label"><?php echo esc_html( $label ); ?></span>
					<?php if ( 'draft' === $status ) : ?>
						<span class="resq-lane-problem-nav__draft"><?php esc_html_e( 'Draft', 'resq-clean-pro' ); ?></span>
					<?php endif; ?>
					<span class="resq-lane-problem-nav__arrow" aria-hidden="true">&rarr;</span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
