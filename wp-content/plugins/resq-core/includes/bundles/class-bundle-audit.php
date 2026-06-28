<?php
/**
 * Bundle merchandising audit.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Bundle_Audit
 */
class ResQ_Core_Bundle_Audit {

	/**
	 * Audit all bundle/kit products.
	 *
	 * @return array{
	 *   rows: array<int, array<string, mixed>>,
	 *   summary: array<string, int>
	 * }
	 */
	public static function audit(): array {
		$rows    = array();
		$summary = array(
			'total'                    => 0,
			'missing_featured_images'  => 0,
			'component_image_fallback' => 0,
			'savings_mismatches'       => 0,
			'self_upgrade_issues'      => 0,
		);

		foreach ( self::get_bundle_product_ids() as $product_id ) {
			$row = self::audit_product( $product_id );
			$rows[] = $row;

			++$summary['total'];

			if ( empty( $row['has_featured_image'] ) || ! empty( $row['is_wc_placeholder'] ) ) {
				++$summary['missing_featured_images'];
			}

			if ( 'collage' === ( $row['fallback_mode'] ?? '' ) ) {
				++$summary['component_image_fallback'];
			}

			if ( ! empty( $row['savings_mismatch'] ) ) {
				++$summary['savings_mismatches'];
			}

			if ( ! empty( $row['self_upgrade_issue'] ) ) {
				++$summary['self_upgrade_issues'];
			}
		}

		return array(
			'rows'    => $rows,
			'summary' => $summary,
		);
	}

	/**
	 * @return int[]
	 */
	private static function get_bundle_product_ids(): array {
		$query = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => '_resq_bundle_product_ids',
						'compare' => 'EXISTS',
					),
				),
			)
		);

		$ids = array_map( 'intval', $query->posts );

		return array_values(
			array_filter(
				$ids,
				static function ( int $product_id ): bool {
					return resq_is_bundle_product( $product_id );
				}
			)
		);
	}

	/**
	 * @param int $product_id Bundle product ID.
	 * @return array<string, mixed>
	 */
	private static function audit_product( int $product_id ): array {
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return array(
				'product_id' => $product_id,
				'title'      => '',
				'sku'        => '',
			);
		}

		$image_id         = (int) $product->get_image_id();
		$has_featured     = $image_id > 0;
		$is_placeholder   = resq_is_wc_placeholder_attachment( $image_id );
		$display          = resq_get_product_image_display( $product_id, 'woocommerce_thumbnail' );
		$savings          = resq_get_bundle_savings_breakdown( $product_id );
		$components       = resq_get_bundle_products( $product_id );
		$component_skus   = array();
		$usable_images    = 0;

		foreach ( $components as $component ) {
			$component_id = (int) ( $component['product_id'] ?? 0 );
			if ( $component_id <= 0 ) {
				continue;
			}

			$component_product = wc_get_product( $component_id );
			if ( $component_product ) {
				$sku = (string) $component_product->get_sku();
				if ( '' !== $sku ) {
					$component_skus[] = $sku;
				}
			}

			if ( resq_product_has_usable_image( $component_id ) ) {
				++$usable_images;
			}
		}

		$fallback_mode = (string) ( $display['mode'] ?? 'none' );

		return array(
			'product_id'              => $product_id,
			'title'                   => $product->get_name(),
			'sku'                     => (string) $product->get_sku(),
			'price'                   => (string) $product->get_price(),
			'url'                     => (string) get_permalink( $product_id ),
			'has_featured_image'      => $has_featured,
			'is_wc_placeholder'       => $is_placeholder,
			'component_skus'          => $component_skus,
			'component_usable_images'   => $usable_images,
			'fallback_mode'           => $fallback_mode,
			'can_component_fallback'  => 'collage' === $fallback_mode,
			'listed_savings'          => $savings['composition_savings'],
			'parts_total'             => $savings['parts_total'],
			'ladder_steps_total'      => $savings['ladder_steps_total'],
			'ladder_savings'          => $savings['ladder_savings'],
			'computed_savings'        => $savings['composition_savings'],
			'savings_mismatch'        => ! empty( $savings['has_mismatch'] ),
			'self_upgrade_issue'      => resq_bundle_has_self_upgrade_issue( $product_id ),
		);
	}
}
