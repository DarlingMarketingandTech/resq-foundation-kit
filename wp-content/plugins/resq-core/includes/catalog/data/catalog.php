<?php
/**
 * Real ResQ catalog data — products, routines, bundles, taxonomy seeds.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/products.php';
require_once __DIR__ . '/routines.php';
require_once __DIR__ . '/bundles.php';

if ( ! function_exists( 'resq_catalog_get_data' ) ) {
	/**
	 * @return array{
	 *   seed_taxonomies: array<string, mixed>,
	 *   products: array<string, array<string, mixed>>,
	 *   routines: array<string, array<string, mixed>>,
	 *   bundles: array<string, array<string, mixed>>
	 * }
	 */
	function resq_catalog_get_data(): array {
		return array(
			'seed_taxonomies' => array(
				'resq_audience' => array(
					'human' => 'Human',
					'pet'   => 'Pet',
				),
				'resq_concern'  => array(
					'human-skincare' => array(
						'name'     => 'Human Skincare',
						'children' => array(
							'dry-skin'       => 'Dry Skin',
							'sensitive-skin' => 'Sensitive Skin',
							'scalp-care'     => 'Scalp Care',
							'anti-aging'     => 'Anti-Aging',
						),
					),
					'pet-topical'    => array(
						'name'     => 'Pet Topical',
						'children' => array(
							'hot-spots'        => 'Hot Spots',
							'itchy-skin'       => 'Itchy Skin',
							'coat-care'        => 'Coat Care',
							'wounds-abrasions' => 'Wounds & Abrasions',
						),
					),
				),
				'resq_ingredient' => array(
					'manuka-honey' => 'Manuka Honey',
					'aloe-vera'    => 'Aloe Vera',
					'coconut-oil'  => 'Coconut Oil',
					'vitamin-e'    => 'Vitamin E',
				),
				'resq_product_role' => array(
					'cleanser'      => 'Cleanser',
					'treatment'     => 'Treatment',
					'moisturizer'   => 'Moisturizer',
					'restorer'      => 'Restorer',
					'add-on'        => 'Add-On',
					'replenishment' => 'Replenishment',
				),
				'resq_compliance_zone' => array(
					'standard'   => 'Standard',
					'cbd'        => 'CBD',
					'baby'       => 'Baby',
					'pet-health' => 'Pet Health',
				),
				'product_cat'   => array(
					'womens-skincare'      => array( 'name' => 'Women\'s Skincare', 'parent' => 'shop-for-humans' ),
					'therapeutic-skin-care' => array( 'name' => 'Therapeutic Skin Care', 'parent' => 'shop-for-humans' ),
					'anti-aging-serums'    => array( 'name' => 'Anti-Aging Serums', 'parent' => 'shop-for-humans' ),
					'hair-scalp-care'      => array( 'name' => 'Hair & Scalp Care', 'parent' => 'shop-for-humans' ),
					'mens-grooming'        => array( 'name' => 'Men\'s Grooming', 'parent' => 'shop-for-humans' ),
					'baby-infant-care'     => array( 'name' => 'Baby & Infant Care', 'parent' => 'shop-for-humans' ),
					'cbd-wellness'         => array( 'name' => 'CBD & Wellness', 'parent' => 'shop-for-humans' ),
					'shop-for-humans'      => array( 'name' => 'Shop For Humans', 'parent' => '' ),
					'topical-skin-care'    => array( 'name' => 'Topical Skin Care', 'parent' => 'shop-for-pets' ),
					'coat-grooming'        => array( 'name' => 'Coat & Grooming', 'parent' => 'shop-for-pets' ),
					'cbd-calming-oils'     => array( 'name' => 'CBD & Calming Oils', 'parent' => 'shop-for-pets' ),
					'treats-diabetic-care' => array( 'name' => 'Pet Treats & Diabetic Care', 'parent' => 'shop-for-pets' ),
					'horse-care'           => array( 'name' => 'Horse & Large Animal Care', 'parent' => 'shop-for-pets' ),
					'shop-for-pets'        => array( 'name' => 'Shop For Pets', 'parent' => '' ),
					'bundles-human'        => array( 'name' => 'Human Bundles', 'parent' => 'bundles-savings' ),
					'bundles-pet'          => array( 'name' => 'Pet Bundles', 'parent' => 'bundles-savings' ),
					'bundles-savings'      => array( 'name' => 'Bundles & Savings', 'parent' => '' ),
				),
			),
			'products'        => resq_catalog_get_products(),
			'routines'        => resq_catalog_get_routines(),
			'bundles'         => resq_catalog_get_bundles(),
		);
	}
}
