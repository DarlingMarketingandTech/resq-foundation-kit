<?php
/**
 * Primary navigation route definitions.
 *
 * Planning routes from docs/07-INFORMATION-ARCHITECTURE.md.
 * Canonical fallback paths align with docs/24-LANDING-PAGES-CONTENT-MAP.md.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_theme_get_gateway_fallback_paths' ) ) {
	/**
	 * Canonical gateway fallback paths keyed by page template filename.
	 *
	 * Mirrors ResQ_Core_Gateway_Pages::get_fallback_paths() when the plugin is active.
	 *
	 * @return array<string, string>
	 */
	function resq_theme_get_gateway_fallback_paths(): array {
		if ( class_exists( 'ResQ_Core_Gateway_Pages' ) ) {
			return ResQ_Core_Gateway_Pages::get_fallback_paths();
		}

		return array(
			'page-gateway-human.php'   => '/shop/human/',
			'page-gateway-pet.php'     => '/shop/pet/',
			'page-gateway-bundles.php' => '/shop/bundles/',
			'page-gateway-cbd.php'     => '/shop/cbd/',
			'page-learn-index.php'     => '/learn/',
		);
	}
}

if ( ! function_exists( 'resq_theme_get_gateway_fallback_path' ) ) {
	/**
	 * Fallback path for a gateway page template.
	 *
	 * @param string $template Page template filename.
	 * @return string
	 */
	function resq_theme_get_gateway_fallback_path( string $template ): string {
		$paths = resq_theme_get_gateway_fallback_paths();

		return $paths[ $template ] ?? '/';
	}
}

if ( ! function_exists( 'resq_theme_get_lane_nav_url' ) ) {
	/**
	 * Resolve a lane category or problem URL for navigation.
	 *
	 * Lane routes always live under /shop/{audience}/{category}/ regardless of
	 * where the audience gateway WP page is published.
	 *
	 * @param string $audience Audience slug.
	 * @param string $category Category slug.
	 * @param string $problem  Optional problem slug.
	 * @return string
	 */
	function resq_theme_get_lane_nav_url( string $audience, string $category, string $problem = '' ): string {
		if ( function_exists( 'resq_resolve_lane_url' ) ) {
			return resq_resolve_lane_url( $audience, $category, $problem );
		}

		$audience = sanitize_key( $audience );
		$category = sanitize_key( $category );
		$problem  = sanitize_key( $problem );
		$path     = 'shop/' . $audience . '/' . $category;

		if ( '' !== $problem ) {
			$path .= '/' . $problem;
		}

		return trailingslashit( home_url( '/' . $path ) );
	}
}

if ( ! function_exists( 'resq_theme_get_gateway_page_url' ) ) {
	/**
	 * Resolve the permalink for a gateway page by its page template filename.
	 *
	 * Looks up the first published page using that template; falls back to the
	 * canonical path from resq_theme_get_gateway_fallback_path() when no page exists.
	 *
	 * @param string $template      Template filename, e.g. 'page-gateway-pet.php'.
	 * @param string $fallback_path Optional override; empty uses the canonical map.
	 * @return string
	 */
	function resq_theme_get_gateway_page_url( string $template, string $fallback_path = '' ): string {
		if ( '' === $fallback_path ) {
			$fallback_path = resq_theme_get_gateway_fallback_path( $template );
		}

		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'meta_key'       => '_wp_page_template',
				'meta_value'     => $template,
				'fields'         => 'ids',
			)
		);

		if ( ! empty( $pages ) ) {
			$url = get_permalink( $pages[0] );
			return $url ?: home_url( $fallback_path );
		}

		return home_url( $fallback_path );
	}
}

if ( ! function_exists( 'resq_theme_get_primary_nav_items' ) ) {
	/**
	 * Return primary nav items for header and mobile drawer.
	 *
	 * URLs are resolved dynamically from page templates so slug changes in
	 * WP Admin are picked up automatically. Lane child links use resq_resolve_lane_url().
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function resq_theme_get_primary_nav_items(): array {
		$human_url = resq_theme_get_gateway_page_url( 'page-gateway-human.php' );
		$pet_url   = resq_theme_get_gateway_page_url( 'page-gateway-pet.php' );

		$items = array(
			array(
				'label'    => __( 'Shop For Humans', 'resq-clean-pro' ),
				'url'      => untrailingslashit( $human_url ),
				'slug'     => 'human',
				'isolated' => false,
				'children' => array(
					array(
						'label'       => __( 'Women’s Skincare', 'resq-clean-pro' ),
						'description' => __( 'Routines, sensitive care, anti-aging sets', 'resq-clean-pro' ),
						'url'         => resq_theme_get_lane_nav_url( 'human', 'womens-skincare' ),
					),
					array(
						'label'       => __( 'Men’s Grooming', 'resq-clean-pro' ),
						'description' => __( 'Wash, moisture, shaving, hair', 'resq-clean-pro' ),
						'url'         => resq_theme_get_lane_nav_url( 'human', 'mens-grooming' ),
					),
					array(
						'label'       => __( 'Intensive Skin Care', 'resq-clean-pro' ),
						'description' => __( 'Dry-skin comfort and everyday moisture support', 'resq-clean-pro' ),
						'url'         => resq_theme_get_lane_nav_url( 'human', 'therapeutic-skin-care' ),
					),
					array(
						'label'       => __( 'Hair & Scalp Care', 'resq-clean-pro' ),
						'description' => __( 'Manuka shampoo & conditioner duos', 'resq-clean-pro' ),
						'url'         => resq_theme_get_lane_nav_url( 'human', 'hair-scalp-care' ),
					),
					array(
						'label'       => __( 'Baby & Infant Care', 'resq-clean-pro' ),
						'description' => __( 'Gentle bath and skin-comfort items', 'resq-clean-pro' ),
						'url'         => resq_theme_get_lane_nav_url( 'human', 'baby-infant-care' ),
					),
				),
			),
			array(
				'label'    => __( 'Shop For Pets', 'resq-clean-pro' ),
				'url'      => untrailingslashit( $pet_url ),
				'slug'     => 'pet',
				'isolated' => false,
				'children' => array(
					array(
						'label'       => __( 'Topical Skin Care', 'resq-clean-pro' ),
						'description' => __( 'Hot spots, paws, equine skin', 'resq-clean-pro' ),
						'url'         => resq_theme_get_lane_nav_url( 'pet', 'topical-skin-care' ),
					),
					array(
						'label'       => __( 'Coat & Grooming', 'resq-clean-pro' ),
						'description' => __( 'Maintenance shampoo and conditioner duos', 'resq-clean-pro' ),
						'url'         => resq_theme_get_lane_nav_url( 'pet', 'coat-grooming' ),
					),
					array(
						'label'       => __( 'Treats & Diabetic Care', 'resq-clean-pro' ),
						'description' => __( 'Low-glycemic dietary options', 'resq-clean-pro' ),
						'url'         => resq_theme_get_lane_nav_url( 'pet', 'treats-diabetic-care' ),
					),
				),
			),
			array(
				'label'    => __( 'Bundles & Savings', 'resq-clean-pro' ),
				'url'      => resq_theme_get_gateway_page_url( 'page-gateway-bundles.php' ),
				'slug'     => 'bundles',
				'isolated' => false,
			),
		);

		$cbd_isolation = function_exists( 'resq_core_feature_enabled' )
			? resq_core_feature_enabled( 'cbd_isolation' )
			: true;

		if ( $cbd_isolation ) {
			$items[] = array(
				'label'    => __( 'CBD & Wellness', 'resq-clean-pro' ),
				'url'      => resq_theme_get_gateway_page_url( 'page-gateway-cbd.php' ),
				'slug'     => 'cbd',
				'isolated' => true,
			);
		}

		$items[] = array(
			'label'    => __( 'Learn', 'resq-clean-pro' ),
			'url'      => resq_theme_get_gateway_page_url( 'page-learn-index.php' ),
			'slug'     => 'learn',
			'isolated' => false,
		);

		/**
		 * Filter primary navigation items.
		 *
		 * @param array<int, array<string, mixed>> $items Nav item definitions.
		 */
		return apply_filters( 'resq_theme_primary_nav_items', $items );
	}
}
