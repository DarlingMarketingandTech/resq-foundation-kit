<?php
/**
 * Global header navigation shell.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

$nav_items = function_exists( 'resq_theme_get_primary_nav_items' )
	? resq_theme_get_primary_nav_items()
	: array();
?>

<header id="masthead" class="site-header" role="banner">
	<div class="resq-container site-header__inner">

		<div class="site-branding">
			<a class="site-branding__link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php resq_theme_render_site_logo(); ?>
			</a>
		</div>

		<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'resq-clean-pro' ); ?>">
			<ul class="main-navigation__list">
				<?php foreach ( $nav_items as $index => $item ) : ?>
					<?php
					$slug       = (string) ( $item['slug'] ?? '' );
					$isolated   = ! empty( $item['isolated'] );
					$children   = ( ! empty( $item['children'] ) && is_array( $item['children'] ) ) ? $item['children'] : array();
					$has_children = ! empty( $children );
					$item_class = 'main-navigation__item';

					if ( 'learn' === $slug ) {
						$item_class .= ' main-navigation__item--learn';
					}
					if ( $isolated ) {
						$item_class .= ' main-navigation__item--cbd';
					}
					if ( $has_children ) {
						$item_class .= ' main-navigation__item--has-children';
					}

					$link_class = 'main-navigation__link';
					if ( $isolated ) {
						$link_class .= ' main-navigation__link--cbd';
					}

					$dropdown_id = 'resq-menu-' . sanitize_html_class( $slug ? $slug : (string) $index );
					$label       = (string) ( $item['label'] ?? '' );
					$url         = (string) ( $item['url'] ?? '#' );
					?>
					<li class="<?php echo esc_attr( $item_class ); ?>"<?php echo $has_children ? ' data-open="false"' : ''; ?>>
						<?php if ( $has_children ) : ?>
							<a
								class="<?php echo esc_attr( $link_class ); ?> main-navigation__trigger"
								href="<?php echo esc_url( $url ); ?>"
								aria-haspopup="true"
								aria-expanded="false"
								aria-controls="<?php echo esc_attr( $dropdown_id ); ?>"
							>
								<?php echo esc_html( $label ); ?>
								<span class="main-navigation__caret" aria-hidden="true"></span>
							</a>
							<ul id="<?php echo esc_attr( $dropdown_id ); ?>" class="main-navigation__dropdown">
								<?php foreach ( $children as $child ) : ?>
									<?php
									$child_label = (string) ( $child['label'] ?? '' );
									$child_url   = (string) ( $child['url'] ?? '#' );
									$child_desc  = (string) ( $child['description'] ?? '' );
									?>
									<li class="main-navigation__dropdown-item">
										<a class="main-navigation__dropdown-link" href="<?php echo esc_url( $child_url ); ?>">
											<?php echo esc_html( $child_label ); ?>
											<?php if ( $child_desc ) : ?>
												<span><?php echo esc_html( $child_desc ); ?></span>
											<?php endif; ?>
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
		</nav>

		<div class="header-actions">
			<?php if ( resq_theme_wc_active() && function_exists( 'wc_get_cart_url' ) ) : ?>
				<a
					id="resq-mini-cart-trigger"
					class="header-actions__cart"
					href="<?php echo esc_url( wc_get_cart_url() ); ?>"
					aria-label="<?php esc_attr_e( 'View your shopping cart', 'resq-clean-pro' ); ?>"
				>
					<span><?php esc_html_e( 'Cart', 'resq-clean-pro' ); ?></span>
					<span class="header-actions__cart-count" aria-hidden="true"><?php echo esc_html( (string) resq_theme_get_cart_count() ); ?></span>
				</a>
			<?php endif; ?>

			<button
				id="resq-menu-toggle"
				class="header-actions__menu-toggle"
				type="button"
				aria-controls="resq-mobile-drawer"
				aria-expanded="false"
				aria-label="<?php esc_attr_e( 'Open menu', 'resq-clean-pro' ); ?>"
			>
				<span aria-hidden="true">☰</span>
			</button>
		</div>

	</div>
</header>

<?php get_template_part( 'template-parts/global/mobile', 'drawer' ); ?>
