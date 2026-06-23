<?php
/**
 * Catalog data helpers — category builders and shared defaults.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_catalog_cat' ) ) {
	/**
	 * @param string $slug Category slug.
	 * @param string $name Display name.
	 * @return array{slug: string, name: string}
	 */
	function resq_catalog_cat( string $slug, string $name ): array {
		return array(
			'slug' => $slug,
			'name' => $name,
		);
	}
}

if ( ! function_exists( 'resq_catalog_size_variations' ) ) {
	/**
	 * Build size-based variation rows.
	 *
	 * @param string               $parent_sku Parent SKU prefix.
	 * @param array<string, string> $sizes     Size slug => price.
	 * @return array<int, array<string, mixed>>
	 */
	function resq_catalog_size_variations( string $parent_sku, array $sizes ): array {
		$rows = array();
		foreach ( $sizes as $size => $price ) {
			$slug = strtoupper( str_replace( array( ' ', '.' ), '', $size ) );
			$rows[] = array(
				'sku'        => $parent_sku . '-' . $slug,
				'attributes' => array( 'size' => $size ),
				'price'      => $price,
			);
		}
		return $rows;
	}
}

if ( ! function_exists( 'resq_catalog_strength_variations' ) ) {
	/**
	 * @param string               $parent_sku Parent SKU.
	 * @param array<string, string> $strengths  Strength label => price.
	 * @return array<int, array<string, mixed>>
	 */
	function resq_catalog_strength_variations( string $parent_sku, array $strengths ): array {
		$rows = array();
		foreach ( $strengths as $strength => $price ) {
			$slug = preg_replace( '/[^0-9A-Z]/', '', strtoupper( $strength ) );
			$rows[] = array(
				'sku'        => $parent_sku . '-' . $slug,
				'attributes' => array( 'strength' => $strength ),
				'price'      => $price,
			);
		}
		return $rows;
	}
}

if ( ! function_exists( 'resq_catalog_manuka_profile' ) ) {
	/**
	 * @return array<int, array<string, mixed>>
	 */
	function resq_catalog_manuka_profile(): array {
		return array(
			array(
				'term_slug'  => 'manuka-honey',
				'label'      => 'Certified Organic Manuka Honey',
				'descriptor' => 'Supports hydration and skin comfort.',
				'claim_safe' => true,
			),
		);
	}
}
