<?php
/**
 * Gateway page layout shell — shared by audience/bundle/CBD templates.
 *
 * @package ResQ_Clean_Pro
 *
 * @var string $gateway_slug Gateway slug: human, pet, bundles, cbd.
 * @var bool   $is_cbd       Whether to apply CBD visual isolation.
 */

defined( 'ABSPATH' ) || exit;

$gateway_slug = isset( $gateway_slug ) ? sanitize_key( (string) $gateway_slug ) : '';
$is_cbd       = ! empty( $is_cbd );

if ( '' === $gateway_slug ) {
	return;
}

$context = array();

if ( function_exists( 'resq_resolve_product_context' ) ) {
	$context = resq_resolve_product_context( $gateway_slug, 'gateway' );
}

$product_ids = array();

if ( ! empty( $context['featured_products'] ) && is_array( $context['featured_products'] ) ) {
	$product_ids = $context['featured_products'];
} elseif ( function_exists( 'resq_get_gateway_featured_products' ) ) {
	$product_ids = resq_get_gateway_featured_products( $gateway_slug );
}

$wrapper_class = 'resq-gateway';

if ( $is_cbd ) {
	$wrapper_class .= ' resq-gateway--cbd';
}
?>

<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php
	if ( $is_cbd ) {
		resq_theme_template_part( 'gateway/cbd-notice' );
	}

	resq_theme_template_part(
		'gateway/hero',
		'',
		array(
			'gateway_slug' => $gateway_slug,
			'context'      => $context,
		)
	);

	resq_theme_template_part(
		'gateway/concern-cards',
		'',
		array(
			'concerns' => $context['concerns'] ?? array(),
		)
	);

	resq_theme_template_part(
		'gateway/filter-shell',
		'',
		array(
			'context' => $context,
		)
	);

	resq_theme_template_part(
		'gateway/product-shelf',
		'',
		array(
			'product_ids' => $product_ids,
		)
	);
	?>
</div>
