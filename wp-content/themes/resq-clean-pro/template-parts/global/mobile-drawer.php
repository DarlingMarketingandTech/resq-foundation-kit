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
			<button class="mobile-drawer__close" type="button" aria-label="<?php esc_attr_e( 'Close navigation', 'resq-clean-pro' ); ?>">
				<span aria-hidden="true">&#x2715;</span>
			</button>
		</div>
		<ul class="mobile-drawer__list">
			<?php foreach ( $nav_items as $index => $item ) : ?>
				<?php
				$isolated     = ! empty( $item['isolated'] );
				$children     = ( ! empty( $item['children'] ) && is_array( $item['children'] ) ) ? $item['children'] : array();
				$has_children = ! empty( $children );
				$slug         = (string) ( $item['slug'] ?? '' );
				$label        = (string) ( $item['label'] ?? '' );
				$url          = (string) ( $item['url'] ?? '#' );
				$item_class   = 'mobile-drawer__item';
				$link_class   = 'mobile-drawer__link';

				if ( $isolated ) {
					$item_class .= ' mobile-drawer__item--cbd';
					$link_class .= ' mobile-drawer__link--cbd';
				}

				$sublist_id = 'resq-drawer-' . sanitize_html_class( $slug ? $slug : (string) $index );
				?>
				<li class="<?php echo esc_attr( $item_class ); ?>">
					<?php if ( $has_children ) : ?>
						<button
							class="mobile-drawer__group-toggle"
							type="button"
							aria-expanded="false"
							aria-controls="<?php echo esc_attr( $sublist_id ); ?>"
						>
							<?php echo esc_html( $label ); ?>
							<span class="mobile-drawer__group-caret" aria-hidden="true"></span>
						</button>
						<ul id="<?php echo esc_attr( $sublist_id ); ?>" class="mobile-drawer__sublist">
							<li>
								<a class="mobile-drawer__sublink" href="<?php echo esc_url( $url ); ?>">
									<?php
									/* translators: %s: nav section label */
									echo esc_html( sprintf( __( 'All %s', 'resq-clean-pro' ), $label ) );
									?>
								</a>
							</li>
							<?php foreach ( $children as $child ) : ?>
								<li>
									<a class="mobile-drawer__sublink" href="<?php echo esc_url( (string) ( $child['url'] ?? '#' ) ); ?>">
										<?php echo esc_html( (string) ( $child['label'] ?? '' ) ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php else : ?>
						<a class="<?php echo esc_attr( $link_class ); ?>" href="<?php echo esc_url( $url ); ?>">
							<?php echo esc_html( $label ); ?>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
