<?php
/**
 * Learn-to-shop product bridge shelf.
 *
 * @package ResQ_Clean_Pro
 *
 * @var int[] $product_ids    Canonical product IDs linked from Learn content.
 * @var int   $source_post_id Current Learn page/post ID.
 */

defined( 'ABSPATH' ) || exit;

$product_ids = isset( $product_ids ) && is_array( $product_ids ) ? array_map( 'absint', $product_ids ) : array();
$product_ids = array_values( array_filter( $product_ids ) );
$source_post_id = isset( $source_post_id ) ? absint( $source_post_id ) : 0;

if ( empty( $product_ids ) ) {
	return;
}

$learn_links_by_product = array();
$has_verified_links     = false;

if ( function_exists( 'resq_get_learn_links_for_product' ) ) {
	foreach ( $product_ids as $product_id ) {
		$learn_links_by_product[ $product_id ] = resq_get_learn_links_for_product( $product_id );
		foreach ( $learn_links_by_product[ $product_id ] as $learn_link ) {
			if ( $source_post_id > 0 && $source_post_id === (int) ( $learn_link['post_id'] ?? 0 ) ) {
				$has_verified_links = true;
				break 2;
			}
		}
	}
}
?>

<section class="resq-learn-product-bridge" aria-labelledby="resq-learn-product-bridge-heading">
	<h2 id="resq-learn-product-bridge-heading" class="resq-learn-product-bridge__title">
		<?php esc_html_e( 'Shop Related Products', 'resq-clean-pro' ); ?>
	</h2>
	<p class="resq-learn-product-bridge__intro">
		<?php
		echo esc_html(
			$has_verified_links
				? __( 'Products linked from this guide are resolved through plugin Learn mappings.', 'resq-clean-pro' )
				: __( 'Products linked from this guide appear when plugin mappings are configured.', 'resq-clean-pro' )
		);
		?>
	</p>

	<?php
	resq_theme_template_part(
		'gateway/product-shelf',
		'',
		array(
			'product_ids' => $product_ids,
		)
	);
	?>
</section>
