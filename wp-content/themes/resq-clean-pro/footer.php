<?php
/**
 * Footer template.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

$resq_footer_human = function_exists( 'resq_theme_get_gateway_page_url' )
	? resq_theme_get_gateway_page_url( 'page-gateway-human.php' )
	: home_url( '/shop/human/' );
$resq_footer_pet = function_exists( 'resq_theme_get_gateway_page_url' )
	? resq_theme_get_gateway_page_url( 'page-gateway-pet.php' )
	: home_url( '/shop/pet/' );
$resq_footer_bundles = function_exists( 'resq_theme_get_gateway_page_url' )
	? resq_theme_get_gateway_page_url( 'page-gateway-bundles.php' )
	: home_url( '/shop/bundles/' );
$resq_footer_learn = function_exists( 'resq_theme_get_gateway_page_url' )
	? resq_theme_get_gateway_page_url( 'page-learn-index.php' )
	: home_url( '/learn/' );
?>

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="resq-container site-footer__inner">
		<div class="site-footer__brand-block">
			<div class="site-footer__brand"><?php esc_html_e( 'ResQ Organics', 'resq-clean-pro' ); ?></div>
			<p class="site-footer__tagline">
				<?php esc_html_e( 'Pure botanical care for humans and pets — one cabinet, not two.', 'resq-clean-pro' ); ?>
			</p>
		</div>

		<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'resq-clean-pro' ); ?>">
			<div class="site-footer__nav-group">
				<p class="site-footer__nav-label"><?php esc_html_e( 'Shop', 'resq-clean-pro' ); ?></p>
				<a class="site-footer__link" href="<?php echo esc_url( $resq_footer_human ); ?>"><?php esc_html_e( 'Shop Human Care', 'resq-clean-pro' ); ?></a>
				<a class="site-footer__link" href="<?php echo esc_url( $resq_footer_pet ); ?>"><?php esc_html_e( 'Shop Pet Care', 'resq-clean-pro' ); ?></a>
				<a class="site-footer__link" href="<?php echo esc_url( $resq_footer_bundles ); ?>"><?php esc_html_e( 'Bundles & Savings', 'resq-clean-pro' ); ?></a>
			</div>
			<div class="site-footer__nav-group">
				<p class="site-footer__nav-label"><?php esc_html_e( 'Resources', 'resq-clean-pro' ); ?></p>
				<a class="site-footer__link" href="<?php echo esc_url( $resq_footer_learn ); ?>"><?php esc_html_e( 'Learn', 'resq-clean-pro' ); ?></a>
				<a class="site-footer__link" href="<?php echo esc_url( $resq_footer_learn ); ?>"><?php esc_html_e( 'Help & Support', 'resq-clean-pro' ); ?></a>
			</div>
		</nav>

		<?php if ( function_exists( 'resq_theme_render_compliance_notices' ) ) : ?>
			<?php resq_theme_render_compliance_notices( 'footer' ); ?>
		<?php endif; ?>

		<p class="site-footer__meta">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php esc_html_e( 'ResQ Organics. All rights reserved.', 'resq-clean-pro' ); ?>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
