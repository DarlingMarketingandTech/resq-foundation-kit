<?php
/**
 * 404 — page not found recovery hub.
 *
 * Compliant copy (docs/05/24): no medical or CBD references. Routes shoppers
 * back to home and the two primary audience gateways.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

$human_url = function_exists( 'resq_theme_get_gateway_page_url' )
	? resq_theme_get_gateway_page_url( 'page-gateway-human.php' )
	: home_url( '/shop/human/' );

$pet_url = function_exists( 'resq_theme_get_gateway_page_url' )
	? resq_theme_get_gateway_page_url( 'page-gateway-pet.php' )
	: home_url( '/shop/pet/' );

get_header();
?>
<main id="primary-content" class="site-main resq-container" role="main">
	<section class="resq-404" aria-labelledby="resq-404-title">
		<span class="resq-404__eyebrow"><?php esc_html_e( 'Error 404', 'resq-clean-pro' ); ?></span>
		<h1 id="resq-404-title" class="resq-404__title"><?php esc_html_e( 'We can’t find that page.', 'resq-clean-pro' ); ?></h1>
		<p class="resq-404__copy">
			<?php esc_html_e( 'The link may have moved or no longer exists. Let’s get you back on track.', 'resq-clean-pro' ); ?>
		</p>

		<div class="resq-404__actions">
			<a class="resq-404__btn resq-404__btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Return to homepage', 'resq-clean-pro' ); ?>
			</a>
			<a class="resq-404__btn" href="<?php echo esc_url( $human_url ); ?>">
				<?php esc_html_e( 'Shop for Humans', 'resq-clean-pro' ); ?>
			</a>
			<a class="resq-404__btn" href="<?php echo esc_url( $pet_url ); ?>">
				<?php esc_html_e( 'Shop for Pets', 'resq-clean-pro' ); ?>
			</a>
		</div>
	</section>
</main>
<?php
get_footer();
