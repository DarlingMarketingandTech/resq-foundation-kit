<?php
/**
 * Product taxonomy registration.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers ResQ product taxonomies on init.
 */
class ResQ_Core_Registrations_Taxonomies {

	/**
	 * Register all product taxonomies.
	 */
	public static function register(): void {
		self::register_audience();
		self::register_concern();
		self::register_ingredient();
		self::register_product_role();
		self::register_compliance_zone();
	}

	/**
	 * resq_audience — gateway routing, filters.
	 */
	private static function register_audience(): void {
		register_taxonomy(
			'resq_audience',
			'product',
			array(
				'labels'            => array(
					'name'          => __( 'Audiences', 'resq-core' ),
					'singular_name' => __( 'Audience', 'resq-core' ),
				),
				'hierarchical'      => false,
				'public'            => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'audience' ),
			)
		);
	}

	/**
	 * resq_concern — problem-led discovery, hierarchical.
	 */
	private static function register_concern(): void {
		register_taxonomy(
			'resq_concern',
			'product',
			array(
				'labels'            => array(
					'name'          => __( 'Concerns', 'resq-core' ),
					'singular_name' => __( 'Concern', 'resq-core' ),
				),
				'hierarchical'      => true,
				'public'            => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'concern' ),
			)
		);
	}

	/**
	 * resq_ingredient — ingredient filters, Learn bridges.
	 */
	private static function register_ingredient(): void {
		register_taxonomy(
			'resq_ingredient',
			'product',
			array(
				'labels'            => array(
					'name'          => __( 'Ingredients', 'resq-core' ),
					'singular_name' => __( 'Ingredient', 'resq-core' ),
				),
				'hierarchical'      => false,
				'public'            => true,
				'show_in_rest'      => true,
				'show_admin_column' => false,
				'rewrite'           => array( 'slug' => 'ingredient' ),
			)
		);
	}

	/**
	 * resq_product_role — routine ladder labels, shelf grouping.
	 */
	private static function register_product_role(): void {
		register_taxonomy(
			'resq_product_role',
			'product',
			array(
				'labels'            => array(
					'name'          => __( 'Product Roles', 'resq-core' ),
					'singular_name' => __( 'Product Role', 'resq-core' ),
				),
				'hierarchical'      => false,
				'public'            => true,
				'show_in_rest'      => true,
				'show_admin_column' => false,
				'rewrite'           => array( 'slug' => 'product-role' ),
			)
		);
	}

	/**
	 * resq_compliance_zone — CBD isolation, cross-sell restrictions.
	 */
	private static function register_compliance_zone(): void {
		register_taxonomy(
			'resq_compliance_zone',
			'product',
			array(
				'labels'            => array(
					'name'          => __( 'Compliance Zones', 'resq-core' ),
					'singular_name' => __( 'Compliance Zone', 'resq-core' ),
				),
				'hierarchical'      => false,
				'public'            => false,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => false,
			)
		);
	}
}
