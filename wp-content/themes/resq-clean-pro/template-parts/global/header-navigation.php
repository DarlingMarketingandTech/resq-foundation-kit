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
				<?php esc_html_e( 'ResQ', 'resq-clean-pro' ); ?>
			</a>
		</div>

		<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'resq-clean-pro' ); ?>">
			<ul class="main-navigation__list">
				<?php foreach ( $nav_items as $item ) : ?>
					<?php
					$slug     = (string) ( $item['slug'] ?? '' );
					$isolated = ! empty( $item['isolated'] );
					$item_class = 'main-navigation__item';

					if ( 'learn' === $slug ) {
						$item_class .= ' main-navigation__item--learn';
					}
					if ( $isolated ) {
						$item_class .= ' main-navigation__item--cbd';
					}

					$link_class = 'main-navigation__link';
					if ( $isolated ) {
						$link_class .= ' main-navigation__link--cbd';
					}
					?>
					<li class="<?php echo esc_attr( $item_class ); ?>">
						<a class="<?php echo esc_attr( $link_class ); ?>" href="<?php echo esc_url( (string) ( $item['url'] ?? '#' ) ); ?>">
							<?php echo esc_html( (string) ( $item['label'] ?? '' ) ); ?>
						</a>
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
