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

if ( ! function_exists( 'resq_theme_get_primary_nav_items' ) ) {
	/**
	 * Return primary nav items for header and mobile drawer.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function resq_theme_get_primary_nav_items(): array {
		$items = array(
			array(
				'label'    => __( 'Shop For Humans', 'resq-clean-pro' ),
				'url'      => home_url( '/shop/human/' ),
				'slug'     => 'human',
				'isolated' => false,
			),
			array(
				'label'    => __( 'Shop For Pets', 'resq-clean-pro' ),
				'url'      => home_url( '/shop/pet/' ),
				'slug'     => 'pet',
				'isolated' => false,
			),
			array(
				'label'    => __( 'Bundles & Savings', 'resq-clean-pro' ),
				'url'      => home_url( '/shop/bundles/' ),
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
				'url'      => home_url( '/shop/human/cbd-wellness/' ),
				'slug'     => 'cbd',
				'isolated' => true,
			);
		}

		$items[] = array(
			'label'    => __( 'Learn', 'resq-clean-pro' ),
			'url'      => home_url( '/learn/' ),
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
