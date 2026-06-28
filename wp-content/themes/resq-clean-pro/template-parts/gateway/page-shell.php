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

$landing  = function_exists( 'resq_theme_get_landing_content' ) ? resq_theme_get_landing_content( $gateway_slug ) : array();
$base_url = trailingslashit( (string) get_permalink() );
$learn_url = function_exists( 'resq_theme_get_gateway_page_url' )
	? resq_theme_get_gateway_page_url( 'page-learn-index.php' )
	: home_url( '/learn/' );
?>

<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php
	if ( $is_cbd ) {
		resq_theme_template_part( 'gateway/cbd-notice' );
	}

	resq_theme_template_part(
		'gateway/hero-lead',
		'',
		array(
			'content'  => $landing,
			'base_url' => $base_url,
			'is_cbd'   => $is_cbd,
		)
	);

	// Per-slug marketing layer (data-driven, empty-safe).
	if ( 'bundles' === $gateway_slug ) {
		resq_theme_template_part(
			'gateway/value-band',
			'',
			array(
				'value_points' => $landing['value_points'] ?? array(),
			)
		);
	} elseif ( $is_cbd ) {
		resq_theme_template_part(
			'gateway/purity-matrix',
			'',
			array(
				'purity' => $landing['purity'] ?? array(),
			)
		);
	} elseif ( ! empty( $landing['segments'] ) ) {
		resq_theme_template_part(
			'gateway/segment-grid',
			'',
			array(
				'segments' => $landing['segments'],
				'base_url' => $base_url,
			)
		);
	}

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

	// Pet rescue mission band closes the page.
	if ( ! empty( $landing['mission'] ) ) {
		resq_theme_template_part(
			'gateway/mission-band',
			'',
			array(
				'mission' => $landing['mission'],
				'cta_url' => $learn_url,
			)
		);
	}
	?>
</div>
