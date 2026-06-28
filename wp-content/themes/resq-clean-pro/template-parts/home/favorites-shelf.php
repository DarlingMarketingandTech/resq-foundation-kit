<?php
/**
 * Homepage — Most-loved routines / favorites product shelf.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

$product_ids = function_exists( 'resq_theme_get_home_favorite_product_ids' )
	? resq_theme_get_home_favorite_product_ids()
	: array();

if ( empty( $product_ids ) ) {
	return;
}
?>

<section class="resq-home-section resq-home-section--shelf" aria-labelledby="resq-home-shelf-title">
	<div class="resq-home-shelf__header resq-home-section__inner">
		<span class="resq-section-label"><?php esc_html_e( 'Start here', 'resq-clean-pro' ); ?></span>
		<h2 id="resq-home-shelf-title" class="resq-home-shelf__title">
			<?php esc_html_e( 'Most-loved ResQ routines', 'resq-clean-pro' ); ?>
		</h2>
		<p class="resq-home-shelf__lede">
			<?php esc_html_e( 'Household favorites for everyday moisture, pet topical comfort, and curated bundle value.', 'resq-clean-pro' ); ?>
		</p>
	</div>

	<div class="resq-home-shelf__grid">
		<?php
		resq_theme_template_part(
			'gateway/product-shelf',
			'',
			array(
				'product_ids' => $product_ids,
				'shelf_title' => '',
			)
		);
		?>
	</div>
</section>
