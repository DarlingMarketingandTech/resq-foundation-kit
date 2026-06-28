<?php
/**
 * Lane shelf audit — category landing smoke data for WP-CLI.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Lane_Audit
 */
class ResQ_Core_Lane_Audit {

	/**
	 * Category lanes included in the main storefront audit gate.
	 *
	 * @return array<int, array{audience:string,category:string}>
	 */
	public static function get_routes(): array {
		return array(
			array( 'audience' => 'human', 'category' => 'womens-skincare' ),
			array( 'audience' => 'human', 'category' => 'mens-grooming' ),
			array( 'audience' => 'human', 'category' => 'therapeutic-skin-care' ),
			array( 'audience' => 'human', 'category' => 'hair-scalp-care' ),
			array( 'audience' => 'human', 'category' => 'baby-infant-care' ),
			array( 'audience' => 'pet', 'category' => 'topical-skin-care' ),
			array( 'audience' => 'pet', 'category' => 'coat-grooming' ),
			array( 'audience' => 'pet', 'category' => 'treats-diabetic-care' ),
		);
	}

	/**
	 * Run the lane audit and return per-lane rows plus gate totals.
	 *
	 * @return array{
	 *     rows: array<int, array<string, mixed>>,
	 *     gates: array<string, int|bool>
	 * }
	 */
	public static function audit(): array {
		$rows              = array();
		$unresolved        = 0;
		$empty_shelves     = 0;
		$total_cbd_leaks   = 0;
		$total_aud_leaks   = 0;
		$total_missing_img = 0;
		$total_hero_miss   = 0;

		foreach ( self::get_routes() as $route ) {
			$audience = (string) $route['audience'];
			$category = (string) $route['category'];
			$lane_key = $audience . '/' . $category;

			$row = array(
				'lane_key'              => $lane_key,
				'audience'              => $audience,
				'category'              => $category,
				'resolved'              => false,
				'url'                   => '',
				'is_draft'              => false,
				'product_count'         => 0,
				'skus'                  => array(),
				'cbd_leaks'             => 0,
				'audience_leaks'        => 0,
				'missing_product_images' => 0,
				'hero_image_missing'    => false,
				'hero_image_slug'     => '',
			);

			if ( ! function_exists( 'resq_get_lane' ) ) {
				++$unresolved;
				$rows[] = $row;
				continue;
			}

			$lane = resq_get_lane(
				array(
					'audience' => $audience,
					'category' => $category,
				)
			);

			if ( null === $lane ) {
				++$unresolved;
				$rows[] = $row;
				continue;
			}

			$row['resolved']  = true;
			$row['url']       = (string) ( $lane['url'] ?? '' );
			$row['is_draft']  = ! empty( $lane['is_draft'] );
			$product_ids      = array_map( 'absint', (array) ( $lane['product_ids'] ?? array() ) );
			$row['product_count'] = count( $product_ids );

			if ( 0 === $row['product_count'] ) {
				++$empty_shelves;
			}

			foreach ( $product_ids as $product_id ) {
				if ( $product_id <= 0 ) {
					continue;
				}

				$sku = (string) get_post_meta( $product_id, '_sku', true );
				if ( '' !== $sku ) {
					$row['skus'][] = $sku;
				}

				if ( function_exists( 'resq_is_cbd_product' ) && resq_is_cbd_product( $product_id ) ) {
					++$row['cbd_leaks'];
				}

				if ( function_exists( 'resq_get_product_audiences' ) ) {
					$aud_slugs = array_column( resq_get_product_audiences( $product_id ), 'slug' );
					if ( ! in_array( $audience, $aud_slugs, true ) ) {
						++$row['audience_leaks'];
					}
				}

				if ( function_exists( 'wc_get_product' ) ) {
					$product = wc_get_product( $product_id );
					if ( ! $product || ! $product->get_image_id() ) {
						++$row['missing_product_images'];
					}
				}
			}

			$hero = self::check_hero_image( (string) ( $lane['copy_key'] ?? '' ) );
			$row['hero_image_slug']  = $hero['slug'];
			$row['hero_image_missing'] = $hero['missing'];

			$total_cbd_leaks   += $row['cbd_leaks'];
			$total_aud_leaks   += $row['audience_leaks'];
			$total_missing_img += $row['missing_product_images'];
			if ( $row['hero_image_missing'] ) {
				++$total_hero_miss;
			}

			$rows[] = $row;
		}

		$gates = array(
			'all_resolved'       => 0 === $unresolved,
			'all_have_products'  => 0 === $empty_shelves,
			'cbd_leaks'          => $total_cbd_leaks,
			'audience_leaks'     => $total_aud_leaks,
			'missing_prod_images' => $total_missing_img,
			'missing_hero_images' => $total_hero_miss,
			'pass'               => 0 === $unresolved
				&& 0 === $empty_shelves
				&& 0 === $total_cbd_leaks
				&& 0 === $total_aud_leaks,
		);

		return array(
			'rows'  => $rows,
			'gates' => $gates,
		);
	}

	/**
	 * Resolve hero image slug from theme lane copy and check Media Library.
	 *
	 * @param string $copy_key Lane copy_key from registry.
	 * @return array{slug: string, missing: bool}
	 */
	private static function check_hero_image( string $copy_key ): array {
		$result = array(
			'slug'    => '',
			'missing' => false,
		);

		if ( '' === $copy_key || ! function_exists( 'resq_theme_get_lane_content' ) ) {
			$result['missing'] = true;
			return $result;
		}

		$content = resq_theme_get_lane_content( $copy_key );
		$slug    = (string) ( $content['image_slug'] ?? '' );
		$result['slug'] = $slug;

		if ( '' === $slug ) {
			$result['missing'] = true;
			return $result;
		}

		if ( ! function_exists( 'resq_core_get_attachment_id_by_slug' ) ) {
			$result['missing'] = true;
			return $result;
		}

		$result['missing'] = resq_core_get_attachment_id_by_slug( $slug ) <= 0;

		return $result;
	}
}
