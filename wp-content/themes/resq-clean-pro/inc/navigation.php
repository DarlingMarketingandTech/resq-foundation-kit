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
		$items = array(
			array(
				'label'    => __( 'Shop For Humans', 'resq-clean-pro' ),
				'url'      => resq_theme_get_gateway_page_url( 'page-gateway-human.php', '/human/' ),
				'slug'     => 'human',
				'isolated' => false,
			),
			array(
				'label'    => __( 'Shop For Pets', 'resq-clean-pro' ),
				'url'      => resq_theme_get_gateway_page_url( 'page-gateway-pet.php', '/pets/' ),
				'slug'     => 'pet',
				'isolated' => false,
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
