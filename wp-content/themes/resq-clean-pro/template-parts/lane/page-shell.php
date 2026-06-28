<?php
/**
 * Lane page shell — composes category and problem lane sections.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $lane Enriched lane data from resq_get_lane().
 */

defined( 'ABSPATH' ) || exit;

$lane = isset( $lane ) && is_array( $lane ) ? $lane : array();

if ( empty( $lane ) ) {
	return;
}

$copy_key = (string) ( $lane['copy_key'] ?? '' );
$content  = function_exists( 'resq_theme_get_lane_content' ) ? resq_theme_get_lane_content( $copy_key ) : array();
$is_cbd   = ! empty( $lane['is_cbd'] );
$is_problem = ! empty( $lane['is_problem'] );

$wrapper_class = 'resq-lane';
if ( $is_cbd ) {
	$wrapper_class .= ' resq-lane--cbd';
}
if ( $is_problem ) {
	$wrapper_class .= ' resq-lane--problem';
}
?>

<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php
	if ( ! empty( $lane['is_draft'] ) ) {
		resq_theme_template_part( 'lane/draft-banner', '', array( 'lane' => $lane ) );
	}

	if ( $is_cbd ) {
		resq_theme_template_part( 'gateway/cbd-notice' );
	}

	resq_theme_template_part(
		'lane/hero',
		'',
		array(
			'lane'    => $lane,
			'content' => $content,
		)
	);

	if ( $is_problem ) {
		resq_theme_template_part(
			'lane/symptom-matrix',
			'',
			array(
				'content' => $content,
			)
		);

		resq_theme_template_part(
			'lane/routine-ladder',
			'',
			array(
				'lane' => $lane,
			)
		);

		resq_theme_template_part(
			'lane/proof',
			'',
			array(
				'content' => $content,
				'lane'    => $lane,
			)
		);
	} else {
		$problem_links = function_exists( 'resq_get_lane_problem_links' )
			? resq_get_lane_problem_links( $lane )
			: array();

		if ( ! empty( $problem_links ) ) {
			resq_theme_template_part(
				'lane/problem-nav',
				'',
				array(
					'links' => $problem_links,
				)
			);
		}

		// Lane filter shell hidden until shelf queries honor GET filter params.
		// filter-shell works on shop/archive views via ResQ_Core_Product_Filters;
		// category lane shelves are static from resq_get_lane_product_ids().
	}

	$product_ids = $lane['product_ids'] ?? array();
	if ( ! empty( $product_ids ) ) {
		resq_theme_template_part(
			'gateway/product-shelf',
			'',
			array(
				'product_ids' => $product_ids,
				'shelf_title' => $is_problem
					? __( 'Canonical product', 'resq-clean-pro' )
					: __( 'Shop this collection', 'resq-clean-pro' ),
			)
		);
	}
	?>
</div>
