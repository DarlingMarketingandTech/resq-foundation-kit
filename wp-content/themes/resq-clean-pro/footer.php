<?php
/**
 * Footer template.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;
?>

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="resq-container site-footer__inner">
		<div class="site-footer__brand"><?php esc_html_e( 'ResQ', 'resq-clean-pro' ); ?></div>

		<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'resq-clean-pro' ); ?>">
			<?php
			// Resolve gateway URLs from page templates so footer links match the
			// header nav and survive slug changes (see inc/navigation.php). Falls
			// back to the canonical gateway paths when the helper is unavailable.
			$resq_footer_human = function_exists( 'resq_theme_get_gateway_page_url' )
				? resq_theme_get_gateway_page_url( 'page-gateway-human.php', '/human/' )
				: home_url( '/human/' );
			$resq_footer_pet = function_exists( 'resq_theme_get_gateway_page_url' )
				? resq_theme_get_gateway_page_url( 'page-gateway-pet.php', '/pets/' )
				: home_url( '/pets/' );
			?>
			<a class="site-footer__link" href="<?php echo esc_url( $resq_footer_human ); ?>"><?php esc_html_e( 'Shop For Humans', 'resq-clean-pro' ); ?></a>
			<a class="site-footer__link" href="<?php echo esc_url( $resq_footer_pet ); ?>"><?php esc_html_e( 'Shop For Pets', 'resq-clean-pro' ); ?></a>
			<a class="site-footer__link" href="<?php echo esc_url( home_url( '/learn/' ) ); ?>"><?php esc_html_e( 'Learn', 'resq-clean-pro' ); ?></a>
		</nav>

		<?php if ( function_exists( 'resq_theme_render_compliance_notices' ) ) : ?>
			<?php resq_theme_render_compliance_notices( 'footer' ); ?>
		<?php endif; ?>

		<p class="site-footer__meta">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php esc_html_e( 'ResQ. All rights reserved.', 'resq-clean-pro' ); ?>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
