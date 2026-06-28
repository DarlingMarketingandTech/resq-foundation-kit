<?php
/**
 * Primary navigation route definitions.
 *
 * Planning routes from docs/07-INFORMATION-ARCHITECTURE.md.
 * Pages may 404 until Phase 6 gateway surfaces are built.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_theme_get_gateway_page_url' ) ) {
	/**
	 * Resolve the permalink for a gateway page by its page template filename.
	 *
	 * Looks up the first published page using that template; falls back to
	 * $fallback_path relative to home URL when no page is found.
	 *
	 * @param string $template      Template filename, e.g. 'page-gateway-pet.php'.
	 * @param string $fallback_path Fallback path appended to home_url().
	 * @return string
	 */
	function resq_theme_get_gateway_page_url( string $template, string $fallback_path ): string {
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
	 * WP Admin are picked up automatically.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function resq_theme_get_primary_nav_items(): array {
		$human_base = trailingslashit( resq_theme_get_gateway_page_url( 'page-gateway-human.php', '/shop/human/' ) );
		$pet_base   = trailingslashit( resq_theme_get_gateway_page_url( 'page-gateway-pet.php', '/shop/pet/' ) );

		$items = array(
			array(
				'label'    => __( 'Shop For Humans', 'resq-clean-pro' ),
				'url'      => untrailingslashit( $human_base ),
				'slug'     => 'human',
				'isolated' => false,
				'children' => array(
					array(
						'label'       => __( 'Women’s Skincare', 'resq-clean-pro' ),
						'description' => __( 'Routines, sensitive care, anti-aging sets', 'resq-clean-pro' ),
						'url'         => $human_base . 'womens-skincare/',
					),
					array(
						'label'       => __( 'Men’s Grooming', 'resq-clean-pro' ),
						'description' => __( 'Wash, moisture, shaving, hair', 'resq-clean-pro' ),
						'url'         => $human_base . 'mens-grooming/',
					),
					array(
						'label'       => __( 'Intensive Skin Care', 'resq-clean-pro' ),
						'description' => __( 'Dry-skin comfort and everyday moisture support', 'resq-clean-pro' ),
						'url'         => $human_base . 'therapeutic-skin-care/',
					),
					array(
						'label'       => __( 'Hair & Scalp Care', 'resq-clean-pro' ),
						'description' => __( 'Manuka shampoo & conditioner duos', 'resq-clean-pro' ),
						'url'         => $human_base . 'hair-scalp-care/',
					),
					array(
						'label'       => __( 'Baby & Infant Care', 'resq-clean-pro' ),
						'description' => __( 'Gentle bath and skin-comfort items', 'resq-clean-pro' ),
						'url'         => $human_base . 'baby-infant-care/',
					),
				),
			),
			array(
				'label'    => __( 'Shop For Pets', 'resq-clean-pro' ),
				'url'      => untrailingslashit( $pet_base ),
				'slug'     => 'pet',
				'isolated' => false,
				'children' => array(
					array(
						'label'       => __( 'Topical Skin Care', 'resq-clean-pro' ),
						'description' => __( 'Hot spots, paws, equine skin', 'resq-clean-pro' ),
						'url'         => $pet_base . 'topical-skin-care/',
					),
					array(
						'label'       => __( 'Coat & Grooming', 'resq-clean-pro' ),
						'description' => __( 'Maintenance shampoo and conditioner duos', 'resq-clean-pro' ),
						'url'         => $pet_base . 'coat-grooming/',
					),
					array(
						'label'       => __( 'Treats & Diabetic Care', 'resq-clean-pro' ),
						'description' => __( 'Low-glycemic dietary options', 'resq-clean-pro' ),
						'url'         => $pet_base . 'treats-diabetic-care/',
					),
				),
			),
			array(
				'label'    => __( 'Bundles & Savings', 'resq-clean-pro' ),
				'url'      => resq_theme_get_gateway_page_url( 'page-gateway-bundles.php', '/bundles/' ),
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
				'url'      => resq_theme_get_gateway_page_url( 'page-gateway-cbd.php', '/cbd/' ),
				'slug'     => 'cbd',
				'isolated' => true,
			);
		}

		$items[] = array(
			'label'    => __( 'Learn', 'resq-clean-pro' ),
			'url'      => resq_theme_get_gateway_page_url( 'page-learn-index.php', '/learn/' ),
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
