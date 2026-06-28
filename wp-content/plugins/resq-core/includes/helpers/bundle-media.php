<?php
/**
 * Bundle product image and savings helpers (display + audit).
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_is_bundle_product' ) ) {
	/**
	 * Whether a product has bundle composition meta.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return bool
	 */
	function resq_is_bundle_product( int $product_id ): bool {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 ) {
			return false;
		}

		$composition = resq_get_product_meta( $product_id, '_resq_bundle_product_ids', array() );

		return is_array( $composition ) && ! empty( $composition );
	}
}

if ( ! function_exists( 'resq_is_wc_placeholder_attachment' ) ) {
	/**
	 * Whether an attachment is the WooCommerce placeholder image.
	 *
	 * @param int $attachment_id Attachment ID.
	 * @return bool
	 */
	function resq_is_wc_placeholder_attachment( int $attachment_id ): bool {
		if ( $attachment_id <= 0 ) {
			return true;
		}

		$placeholder_id = (int) get_option( 'woocommerce_placeholder_image', 0 );
		if ( $placeholder_id > 0 && $attachment_id === $placeholder_id ) {
			return true;
		}

		if ( ! function_exists( 'wc_placeholder_img_src' ) ) {
			return false;
		}

		$src             = (string) wp_get_attachment_url( $attachment_id );
		$placeholder_src = (string) wc_placeholder_img_src();

		if ( '' === $src || '' === $placeholder_src ) {
			return false;
		}

		return untrailingslashit( $src ) === untrailingslashit( $placeholder_src );
	}
}

if ( ! function_exists( 'resq_product_has_usable_image' ) ) {
	/**
	 * Whether a product has a real featured image (not missing / not WC placeholder).
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return bool
	 */
	function resq_product_has_usable_image( int $product_id ): bool {
		if ( ! resq_is_woocommerce_available() ) {
			return false;
		}

		$product = wc_get_product( resq_resolve_product_id( $product_id ) );
		if ( ! $product ) {
			return false;
		}

		$image_id = (int) $product->get_image_id();

		return $image_id > 0 && ! resq_is_wc_placeholder_attachment( $image_id );
	}
}

if ( ! function_exists( 'resq_get_bundle_component_image_items' ) ) {
	/**
	 * Usable component product images for a bundle collage.
	 *
	 * @param int    $bundle_id Bundle product ID.
	 * @param string $size      WordPress image size.
	 * @param int    $limit     Max images to return.
	 * @return array<int, array{product_id:int,url:string,alt:string}>
	 */
	function resq_get_bundle_component_image_items( int $bundle_id, string $size = 'woocommerce_thumbnail', int $limit = 4 ): array {
		if ( ! function_exists( 'resq_get_bundle_products' ) ) {
			return array();
		}

		$items  = array();
		$bundle = resq_get_bundle_products( $bundle_id );

		foreach ( $bundle as $component ) {
			$component_id = (int) ( $component['product_id'] ?? 0 );
			if ( $component_id <= 0 || ! resq_product_has_usable_image( $component_id ) ) {
				continue;
			}

			$product = wc_get_product( $component_id );
			if ( ! $product ) {
				continue;
			}

			$image_id = (int) $product->get_image_id();
			$src      = wp_get_attachment_image_src( $image_id, $size );
			if ( ! $src || empty( $src[0] ) ) {
				continue;
			}

			$items[] = array(
				'product_id' => $component_id,
				'url'        => (string) $src[0],
				'alt'        => (string) $product->get_name(),
			);

			if ( count( $items ) >= $limit ) {
				break;
			}
		}

		return $items;
	}
}

if ( ! function_exists( 'resq_get_product_image_display' ) ) {
	/**
	 * Resolve how a product image should render on the storefront.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $size       Image size slug.
	 * @return array{
	 *   mode: string,
	 *   title: string,
	 *   alt: string,
	 *   image_id: int,
	 *   image_url: string,
	 *   collage: array<int, array{product_id:int,url:string,alt:string}>
	 * }
	 */
	function resq_get_product_image_display( int $product_id, string $size = 'woocommerce_thumbnail' ): array {
		$product_id = resq_resolve_product_id( $product_id );
		$empty      = array(
			'mode'      => 'none',
			'title'     => '',
			'alt'       => '',
			'image_id'  => 0,
			'image_url' => '',
			'collage'   => array(),
		);

		if ( $product_id <= 0 || ! resq_is_woocommerce_available() ) {
			return $empty;
		}

		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return $empty;
		}

		$title = $product->get_name();
		$alt   = $title;

		if ( resq_product_has_usable_image( $product_id ) ) {
			$image_id = (int) $product->get_image_id();
			$src      = wp_get_attachment_image_src( $image_id, $size );

			return array(
				'mode'      => 'featured',
				'title'     => $title,
				'alt'       => $alt,
				'image_id'  => $image_id,
				'image_url' => $src[0] ?? (string) wp_get_attachment_url( $image_id ),
				'collage'   => array(),
			);
		}

		if ( resq_is_bundle_product( $product_id ) ) {
			$collage = resq_get_bundle_component_image_items( $product_id, $size );
			if ( count( $collage ) >= 2 ) {
				return array(
					'mode'      => 'collage',
					'title'     => $title,
					'alt'       => $alt,
					'image_id'  => 0,
					'image_url' => '',
					'collage'   => $collage,
				);
			}

			return array(
				'mode'      => 'branded',
				'title'     => $title,
				'alt'       => sprintf(
					/* translators: %s: bundle product title */
					__( '%s — ResQ Organics routine kit', 'resq-core' ),
					$title
				),
				'image_id'  => 0,
				'image_url' => '',
				'collage'   => $collage,
			);
		}

		return $empty;
	}
}

if ( ! function_exists( 'resq_get_bundle_savings_breakdown' ) ) {
	/**
	 * Canonical bundle savings from composition vs bundle price.
	 *
	 * @param int $bundle_id Bundle product ID.
	 * @return array{
	 *   parts_total: float,
	 *   bundle_price: float,
	 *   composition_savings: float,
	 *   ladder_steps_total: float|null,
	 *   ladder_savings: float|null,
	 *   has_mismatch: bool
	 * }
	 */
	function resq_get_bundle_savings_breakdown( int $bundle_id ): array {
		$bundle_id = resq_resolve_product_id( $bundle_id );
		$result    = array(
			'parts_total'         => 0.0,
			'bundle_price'        => 0.0,
			'composition_savings' => 0.0,
			'ladder_steps_total'  => null,
			'ladder_savings'      => null,
			'has_mismatch'        => false,
		);

		if ( $bundle_id <= 0 || ! resq_is_woocommerce_available() || ! resq_is_bundle_product( $bundle_id ) ) {
			return $result;
		}

		$product = wc_get_product( $bundle_id );
		if ( ! $product ) {
			return $result;
		}

		$parts_total = 0.0;
		foreach ( resq_get_bundle_products( $bundle_id ) as $item ) {
			$qty          = max( 1, (int) ( $item['qty'] ?? 1 ) );
			$parts_total += (float) ( $item['price'] ?? 0 ) * $qty;
		}

		$bundle_price = (float) $product->get_price();
		$composition  = max( 0.0, $parts_total - $bundle_price );

		$result['parts_total']         = $parts_total;
		$result['bundle_price']        = $bundle_price;
		$result['composition_savings'] = $composition;

		$steps_total = resq_get_bundle_ladder_steps_total( $bundle_id );
		if ( null !== $steps_total ) {
			$ladder_savings                   = max( 0.0, $steps_total - $bundle_price );
			$result['ladder_steps_total']     = $steps_total;
			$result['ladder_savings']         = $ladder_savings;
			$result['has_mismatch']           = abs( $composition - $ladder_savings ) > 0.01;
		}

		return $result;
	}
}

if ( ! function_exists( 'resq_get_bundle_ladder_steps_total' ) ) {
	/**
	 * Sum routine-ladder step prices for a bundle's primary routine.
	 *
	 * @param int $bundle_id Bundle product ID.
	 * @return float|null Null when no routine/steps exist.
	 */
	function resq_get_bundle_ladder_steps_total( int $bundle_id ): ?float {
		$bundle_id = resq_resolve_product_id( $bundle_id );
		if ( $bundle_id <= 0 || ! function_exists( 'resq_get_product_routines' ) || ! function_exists( 'resq_get_routine_steps' ) ) {
			return null;
		}

		$routines = resq_get_product_routines( $bundle_id );
		if ( empty( $routines ) ) {
			return null;
		}

		$primary = null;
		foreach ( $routines as $routine ) {
			if ( ! empty( $routine['is_primary'] ) ) {
				$primary = $routine;
				break;
			}
		}

		if ( ! $primary ) {
			$primary = $routines[0];
		}

		$steps = resq_get_routine_steps( (int) ( $primary['routine_id'] ?? 0 ), $bundle_id );
		if ( empty( $steps ) ) {
			return null;
		}

		$total = 0.0;
		foreach ( $steps as $step ) {
			$step_id = (int) ( $step['product_id'] ?? 0 );
			if ( $step_id <= 0 ) {
				continue;
			}

			$summary = resq_get_wc_product_summary( $step_id );
			if ( $summary ) {
				$total += (float) $summary['price'];
			}
		}

		return $total;
	}
}

if ( ! function_exists( 'resq_bundle_has_self_upgrade_issue' ) ) {
	/**
	 * Whether the routine ladder would prompt upgrading to the same bundle PDP.
	 *
	 * @param int $bundle_id Bundle product ID.
	 * @return bool
	 */
	function resq_bundle_has_self_upgrade_issue( int $bundle_id ): bool {
		$bundle_id = resq_resolve_product_id( $bundle_id );
		if ( $bundle_id <= 0 || ! function_exists( 'resq_get_product_routine_ladder' ) ) {
			return false;
		}

		$ladder = resq_get_product_routine_ladder( $bundle_id );

		return (int) ( $ladder['bundle_target'] ?? 0 ) > 0;
	}
}
