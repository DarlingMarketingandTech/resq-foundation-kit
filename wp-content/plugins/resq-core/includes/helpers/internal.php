<?php
/**
 * Internal helper utilities shared by storefront helpers.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_is_woocommerce_available' ) ) {
	/**
	 * Whether WooCommerce is active and usable.
	 *
	 * @return bool
	 */
	function resq_is_woocommerce_available(): bool {
		return class_exists( 'ResQ_Core_Woocommerce_Compat' )
			&& ResQ_Core_Woocommerce_Compat::is_woocommerce_active()
			&& function_exists( 'wc_get_product' );
	}
}

if ( ! function_exists( 'resq_resolve_product_id' ) ) {
	/**
	 * Resolve a variation ID to its parent product ID.
	 *
	 * @param int $product_id WooCommerce product or variation ID.
	 * @return int Parent/simple product ID, or 0 when invalid.
	 */
	function resq_resolve_product_id( int $product_id ): int {
		if ( $product_id <= 0 ) {
			return 0;
		}

		if ( ! resq_is_woocommerce_available() ) {
			return $product_id;
		}

		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return 0;
		}

		if ( $product->is_type( 'variation' ) ) {
			return (int) $product->get_parent_id();
		}

		return $product_id;
	}
}

if ( ! function_exists( 'resq_get_product_meta' ) ) {
	/**
	 * Read typed product meta with a default fallback.
	 *
	 * @param int    $product_id Resolved product ID.
	 * @param string $meta_key   Meta key.
	 * @param mixed  $default    Default when missing.
	 * @return mixed
	 */
	function resq_get_product_meta( int $product_id, string $meta_key, mixed $default = null ): mixed {
		if ( $product_id <= 0 ) {
			return $default;
		}

		$value = get_post_meta( $product_id, $meta_key, true );

		if ( '' === $value || false === $value ) {
			return $default;
		}

		return $value;
	}
}

if ( ! function_exists( 'resq_get_routine_meta' ) ) {
	/**
	 * Read typed routine CPT meta with a default fallback.
	 *
	 * @param int    $routine_id Routine CPT post ID.
	 * @param string $meta_key   Meta key.
	 * @param mixed  $default    Default when missing.
	 * @return mixed
	 */
	function resq_get_routine_meta( int $routine_id, string $meta_key, mixed $default = null ): mixed {
		if ( $routine_id <= 0 ) {
			return $default;
		}

		$value = get_post_meta( $routine_id, $meta_key, true );

		if ( '' === $value || false === $value ) {
			return $default;
		}

		return $value;
	}
}

if ( ! function_exists( 'resq_map_term_to_audience' ) ) {
	/**
	 * Map a WP_Term to the audience contract shape.
	 *
	 * @param WP_Term $term Term object.
	 * @return array<string, mixed>
	 */
	function resq_map_term_to_audience( WP_Term $term ): array {
		return array(
			'term_id' => (int) $term->term_id,
			'slug'    => $term->slug,
			'name'    => $term->name,
			'label'   => $term->name,
		);
	}
}

if ( ! function_exists( 'resq_map_term_to_concern' ) ) {
	/**
	 * Map a WP_Term to the concern contract shape.
	 *
	 * @param WP_Term $term Term object.
	 * @return array<string, mixed>
	 */
	function resq_map_term_to_concern( WP_Term $term ): array {
		$parent_id   = (int) $term->parent;
		$parent_slug = '';

		if ( $parent_id > 0 ) {
			$parent = get_term( $parent_id, $term->taxonomy );
			if ( $parent && ! is_wp_error( $parent ) ) {
				$parent_slug = $parent->slug;
			}
		}

		return array(
			'term_id'     => (int) $term->term_id,
			'slug'        => $term->slug,
			'name'        => $term->name,
			'label'       => $term->name,
			'parent_id'   => $parent_id,
			'parent_slug' => $parent_slug,
		);
	}
}

if ( ! function_exists( 'resq_get_compliance_flags' ) ) {
	/**
	 * Return sanitized compliance flags for a product.
	 *
	 * @param int $product_id Resolved product ID.
	 * @return string[]
	 */
	function resq_get_compliance_flags( int $product_id ): array {
		$flags = resq_get_product_meta( $product_id, '_resq_compliance_flags', array() );

		if ( ! is_array( $flags ) ) {
			return array();
		}

		return ResQ_Core_Registrations_Post_Meta::sanitize_compliance_flags( $flags );
	}
}

if ( ! function_exists( 'resq_get_route_canonical_map' ) ) {
	/**
	 * Minimal route slug map for canonical resolver.
	 *
	 * @return array<string, int|null>
	 */
	function resq_get_route_canonical_map(): array {
		/**
		 * Filter the route-to-canonical-product mapping table.
		 *
		 * @param array<string, int|null> $map Route slug => product ID or null.
		 */
		return apply_filters(
			'resq_route_canonical_map',
			array(
				'human'   => null,
				'pet'     => null,
				'cbd'     => null,
				'bundles' => null,
				'learn'   => null,
			)
		);
	}
}

if ( ! function_exists( 'resq_product_exists' ) ) {
	/**
	 * Whether a WooCommerce product exists and is published.
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	function resq_product_exists( int $product_id ): bool {
		if ( $product_id <= 0 || ! resq_is_woocommerce_available() ) {
			return false;
		}

		$product = wc_get_product( $product_id );

		return $product && 'publish' === $product->get_status();
	}
}

if ( ! function_exists( 'resq_get_wc_product_summary' ) ) {
	/**
	 * Return basic Woo product summary for helper enrichment.
	 *
	 * @param int $product_id Product ID.
	 * @return array{title: string, url: string, in_stock: bool, price: string}|null
	 */
	function resq_get_wc_product_summary( int $product_id ): ?array {
		if ( ! resq_product_exists( $product_id ) ) {
			return null;
		}

		$product = wc_get_product( $product_id );

		return array(
			'title'    => $product->get_name(),
			'url'      => get_permalink( $product_id ),
			'in_stock' => $product->is_in_stock(),
			'price'    => $product->get_price(),
		);
	}
}
