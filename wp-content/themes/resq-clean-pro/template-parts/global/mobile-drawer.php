<?php
/**
 * Mobile navigation drawer.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

$nav_items = function_exists( 'resq_theme_get_primary_nav_items' )
	? resq_theme_get_primary_nav_items()
	: array();
?>

<div id="resq-mobile-drawer" class="mobile-drawer" aria-hidden="true">
	<div class="mobile-drawer__overlay" tabindex="-1"></div>
	<div class="mobile-drawer__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Mobile navigation', 'resq-clean-pro' ); ?>">
		<div class="mobile-drawer__header">
			<p class="mobile-drawer__brand"><?php esc_html_e( 'ResQ', 'resq-clean-pro' ); ?></p>
			<button class="mobile-drawer__close" aria-label="<?php esc_attr_e( 'Close navigation', 'resq-clean-pro' ); ?>" aria-expanded="true" aria-controls="resq-mobile-drawer">
				<span aria-hidden="true">&#x2715;</span>
			</button>
		</div>
		<ul class="mobile-drawer__list">
			<?php foreach ( $nav_items as $item ) : ?>
				<?php
				$isolated   = ! empty( $item['isolated'] );
				$item_class = 'mobile-drawer__item';
				$link_class = 'mobile-drawer__link';

				if ( $isolated ) {
					$item_class .= ' mobile-drawer__item--cbd';
					$link_class .= ' mobile-drawer__link--cbd';
				}
				?>
				<li class="<?php echo esc_attr( $item_class ); ?>">
					<a class="<?php echo esc_attr( $link_class ); ?>" href="<?php echo esc_url( (string) ( $item['url'] ?? '#' ) ); ?>">
						<?php echo esc_html( (string) ( $item['label'] ?? '' ) ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
