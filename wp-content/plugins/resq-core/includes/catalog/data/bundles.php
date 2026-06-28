<?php
/**
 * Catalog bundle and multipack definitions.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/helpers.php';

if ( ! function_exists( 'resq_catalog_get_bundles' ) ) {
	/**
	 * @return array<string, array<string, mixed>>
	 */
	function resq_catalog_get_bundles(): array {
		$bundles_human = array( resq_catalog_cat( 'bundles-human', 'Human Bundles' ) );
		$bundles_pet   = array( resq_catalog_cat( 'bundles-pet', 'Pet Bundles' ) );

		$bundles = array(
			'RQ-KIT-PET-HOTSPOT' => array(
				'name'         => 'Pet Hot Spot Rescue Kit',
				'price'        => '89.95',
				'zone'         => 'pet-health',
				'audiences'    => array( 'pet' ),
				'categories'   => $bundles_pet,
				'components'   => array(
					array( 'sku' => 'RQ-PET-SKINCREAM-4OZ', 'qty' => 1 ),
					array( 'sku' => 'RQ-PET-SHAMPOO', 'qty' => 1 ),
					array( 'sku' => 'RQ-PET-CONDITIONER', 'qty' => 1 ),
				),
				'routine_slug' => 'rq-routine-pet-hotspot',
			),
			'RQ-DUO-PET-COAT'    => array(
				'name'       => 'Pet Coat Care Duo',
				'price'      => '54.95',
				'zone'       => 'pet-health',
				'audiences'  => array( 'pet' ),
				'categories' => $bundles_pet,
				'components' => array(
					array( 'sku' => 'RQ-PET-SHAMPOO', 'qty' => 1 ),
					array( 'sku' => 'RQ-PET-CONDITIONER', 'qty' => 1 ),
				),
			),
			'RQ-KIT-PCBD-CALM-500' => array(
				'name'       => 'Canine Calm Starter Kit — 500mg',
				'price'      => '104.95',
				'zone'       => 'cbd',
				'audiences'  => array( 'pet' ),
				'categories' => $bundles_pet,
				'components' => array(
					array( 'sku' => 'RQ-PCBD-OIL-500MG', 'qty' => 1 ),
					array( 'sku' => 'RQ-PCBD-TREATS-5MG', 'qty' => 1 ),
				),
			),
			'RQ-KIT-PCBD-CALM-1000' => array(
				'name'       => 'Canine Calm Starter Kit — 1000mg',
				'price'      => '164.95',
				'zone'       => 'cbd',
				'audiences'  => array( 'pet' ),
				'categories' => $bundles_pet,
				'components' => array(
					array( 'sku' => 'RQ-PCBD-OIL-1000MG', 'qty' => 1 ),
					array( 'sku' => 'RQ-PCBD-TREATS-5MG', 'qty' => 1 ),
				),
			),
			'RQ-KIT-PET-SENIOR'  => array(
				'name'       => 'Senior Dog Comfort Bundle',
				'price'      => '129.95',
				'zone'       => 'pet-health',
				'audiences'  => array( 'pet' ),
				'categories' => $bundles_pet,
				'components' => array(
					array( 'sku' => 'RQ-PET-SKINCREAM-4OZ', 'qty' => 1 ),
					array( 'sku' => 'RQ-PCBD-OIL-500MG', 'qty' => 1 ),
					array( 'sku' => 'RQ-PET-DIABETIC-TREATS', 'qty' => 1 ),
				),
			),
			'RQ-KIT-HORSE-SKIN'  => array(
				'name'       => 'Horse Skin & Coat Care Kit',
				'price'      => '99.95',
				'zone'       => 'pet-health',
				'audiences'  => array( 'pet' ),
				'categories' => $bundles_pet,
				'components' => array(
					array( 'sku' => 'RQ-PET-HORSECREAM-8OZ', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-MANUKAHONEY', 'qty' => 1 ),
				),
			),
			'RQ-KIT-HUM-WOMENS'  => array(
				'name'         => 'Women\'s Daily Repair Routine',
				'price'        => '134.95',
				'zone'         => 'standard',
				'audiences'    => array( 'human' ),
				'categories'   => $bundles_human,
				'components'   => array(
					array( 'sku' => 'RQ-HUM-AIOCREAM-4OZ', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-WASH', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-NIGHTSERUM', 'qty' => 1 ),
				),
				'routine_slug' => 'rq-routine-womens-daily',
			),
			'RQ-KIT-HUM-ANTIAGING' => array(
				'name'       => 'Anti-Aging Routine Bundle',
				'price'      => '139.95',
				'zone'       => 'standard',
				'audiences'  => array( 'human' ),
				'categories' => $bundles_human,
				'components' => array(
					array( 'sku' => 'RQ-HUM-NIGHTSERUM', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-SCRUB', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-AIOCREAM-4OZ', 'qty' => 1 ),
				),
				'routine_slug' => 'rq-routine-anti-aging',
			),
			'RQ-KIT-HUM-WOMENS-STARTER' => array(
				'name'       => 'Women\'s Sensitive Skin Starter Set',
				'price'      => '69.95',
				'zone'       => 'standard',
				'audiences'  => array( 'human' ),
				'categories' => $bundles_human,
				'components' => array(
					array( 'sku' => 'RQ-HUM-WASH', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-AIOCREAM-2OZ', 'qty' => 1 ),
				),
			),
			'RQ-KIT-HUM-MENS'    => array(
				'name'         => 'Men\'s Core Grooming Kit',
				'price'        => '134.95',
				'zone'         => 'standard',
				'audiences'    => array( 'human' ),
				'categories'   => $bundles_human,
				'components'   => array(
					array( 'sku' => 'RQ-HUM-MENSWASH', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-MOISTURIZER-4OZ', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-MENSSERUM', 'qty' => 1 ),
				),
				'routine_slug' => 'rq-routine-mens-core',
			),
			'RQ-KIT-HUM-MENS-ELITE' => array(
				'name'       => 'Men\'s Elite Grooming Routine',
				'price'      => '169.95',
				'zone'       => 'standard',
				'audiences'  => array( 'human' ),
				'categories' => $bundles_human,
				'components' => array(
					array( 'sku' => 'RQ-HUM-MENSWASH', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-MOISTURIZER-4OZ', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-MENSSCRUB', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-MENSSERUM', 'qty' => 1 ),
				),
			),
			'RQ-DUO-HUM-MENSHAIR' => array(
				'name'       => 'Men\'s Hair Duo',
				'price'      => '54.95',
				'zone'       => 'standard',
				'audiences'  => array( 'human' ),
				'categories' => $bundles_human,
				'components' => array(
					array( 'sku' => 'RQ-HUM-MENSHAMPOO', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-MENSCOND', 'qty' => 1 ),
				),
			),
			'RQ-DUO-HUM-HAIR'    => array(
				'name'         => 'Manuka Hair Care Duo',
				'price'        => '54.95',
				'zone'         => 'standard',
				'audiences'    => array( 'human' ),
				'categories'   => $bundles_human,
				'components'   => array(
					array( 'sku' => 'RQ-HUM-SHAMPOO', 'qty' => 1 ),
					array( 'sku' => 'RQ-HUM-CONDITIONER', 'qty' => 1 ),
				),
				'routine_slug' => 'rq-routine-hair-duo',
			),
			'RQ-DUO-BABY-BATH'   => array(
				'name'         => 'Baby Bath + Skin Care Duo',
				'price'        => '72.95',
				'zone'         => 'baby',
				'audiences'    => array( 'human' ),
				'categories'   => $bundles_human,
				'components'   => array(
					array( 'sku' => 'RQ-BABY-WASH', 'qty' => 1 ),
					array( 'sku' => 'RQ-BABY-CREAM-4OZ', 'qty' => 1 ),
				),
				'routine_slug' => 'rq-routine-baby-bath',
			),
			'RQ-KIT-HCBD-NIGHT'  => array(
				'name'       => 'CBD Night Routine',
				'price'      => '72.95',
				'zone'       => 'cbd',
				'audiences'  => array( 'human' ),
				'categories' => $bundles_human,
				'components' => array(
					array( 'sku' => 'RQ-HCBD-SLEEPGUMMIES', 'qty' => 1 ),
					array( 'sku' => 'RQ-HCBD-BATHBOMB-LAV', 'qty' => 1 ),
				),
			),
			'RQ-KIT-HCBD-RELIEF' => array(
				'name'       => 'CBD Relief Routine',
				'price'      => '79.95',
				'zone'       => 'cbd',
				'audiences'  => array( 'human' ),
				'categories' => $bundles_human,
				'components' => array(
					array( 'sku' => 'RQ-HCBD-RUB', 'qty' => 1 ),
					array( 'sku' => 'RQ-HCBD-BATHBOMB-EUC', 'qty' => 1 ),
				),
			),
			'RQ-KIT-HCBD-DAILY'  => array(
				'name'       => 'CBD Daily Wellness Bundle',
				'price'      => '119.95',
				'zone'       => 'cbd',
				'audiences'  => array( 'human' ),
				'categories' => $bundles_human,
				'components' => array(
					array( 'sku' => 'RQ-HCBD-OIL-500MG', 'qty' => 1 ),
					array( 'sku' => 'RQ-HCBD-GUMMIES', 'qty' => 1 ),
				),
			),
			'RQ-PK-HCBD-BATH-3'  => array(
				'name'       => 'CBD Bath Bomb Variety 3-Pack',
				'price'      => '54.95',
				'zone'       => 'cbd',
				'audiences'  => array( 'human' ),
				'categories' => $bundles_human,
				'components' => array(
					array( 'sku' => 'RQ-HCBD-BATHBOMB-LAV', 'qty' => 1 ),
					array( 'sku' => 'RQ-HCBD-BATHBOMB-EUC', 'qty' => 1 ),
					array( 'sku' => 'RQ-HCBD-BATHBOMB-CIT', 'qty' => 1 ),
				),
			),
			'RQ-PK-HCBD-BATH-5'  => array(
				'name'       => 'CBD Bath Bomb Variety 5-Pack',
				'price'      => '84.95',
				'zone'       => 'cbd',
				'audiences'  => array( 'human' ),
				'categories' => $bundles_human,
				'components' => array(
					array( 'sku' => 'RQ-HCBD-BATHBOMB-LAV', 'qty' => 2 ),
					array( 'sku' => 'RQ-HCBD-BATHBOMB-EUC', 'qty' => 2 ),
					array( 'sku' => 'RQ-HCBD-BATHBOMB-CIT', 'qty' => 1 ),
				),
			),
			'RQ-PK-HCBD-GUMMIES-2' => array(
				'name'       => 'CBD Gummies 2-Pack',
				'price'      => '89.95',
				'zone'       => 'cbd',
				'audiences'  => array( 'human' ),
				'categories' => $bundles_human,
				'components' => array(
					array( 'sku' => 'RQ-HCBD-GUMMIES', 'qty' => 2 ),
				),
			),
			'RQ-PK-HCBD-GUMMIES-3' => array(
				'name'       => 'CBD Gummies 3-Pack',
				'price'      => '129.95',
				'zone'       => 'cbd',
				'audiences'  => array( 'human' ),
				'categories' => $bundles_human,
				'components' => array(
					array( 'sku' => 'RQ-HCBD-GUMMIES', 'qty' => 3 ),
				),
			),
			'RQ-PK-PET-DIABETIC-2' => array(
				'name'       => 'Diabetic Dog Treats 2-Pack',
				'price'      => '36.95',
				'zone'       => 'pet-health',
				'audiences'  => array( 'pet' ),
				'categories' => $bundles_pet,
				'components' => array(
					array( 'sku' => 'RQ-PET-DIABETIC-TREATS', 'qty' => 2 ),
				),
			),
			'RQ-PK-PET-DIABETIC-3' => array(
				'name'       => 'Diabetic Dog Treats 3-Pack',
				'price'      => '52.95',
				'zone'       => 'pet-health',
				'audiences'  => array( 'pet' ),
				'categories' => $bundles_pet,
				'components' => array(
					array( 'sku' => 'RQ-PET-DIABETIC-TREATS', 'qty' => 3 ),
				),
			),
		);

		foreach ( resq_catalog_get_bundle_image_map() as $sku => $image_data ) {
			if ( isset( $bundles[ $sku ] ) ) {
				$bundles[ $sku ] = array_merge( $bundles[ $sku ], $image_data );
			}
		}

		return $bundles;
	}
}

if ( ! function_exists( 'resq_catalog_get_bundle_image_map' ) ) {
	/**
	 * Return Media Library image slugs keyed by bundle and multipack SKU.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	function resq_catalog_get_bundle_image_map(): array {
		return array(
			'RQ-KIT-PET-HOTSPOT'        => array( 'image' => 'dog-skin-care-gentle-effective-lifestyle', 'gallery' => array( 'dog-skin-care-cream-manuka-honey-4oz', 'dog-shampoo-manuka-honey-16oz', 'dog-conditioner-manuka-honey-16oz' ) ),
			'RQ-DUO-PET-COAT'           => array( 'image' => 'dog-skin-care-gentle-effective-lifestyle', 'gallery' => array( 'dog-shampoo-manuka-honey-16oz', 'dog-conditioner-manuka-honey-16oz' ) ),
			'RQ-KIT-PCBD-CALM-500'      => array( 'image' => 'cbd-for-pets-natural', 'gallery' => array( 'cbd-hemp-oil-pets-beef-cheese-300mg', 'cbd-infused-organic-dog-treats-2' ) ),
			'RQ-KIT-PCBD-CALM-1000'     => array( 'image' => 'cbd-for-pets-natural', 'gallery' => array( 'cbd-hemp-oil-pets-beef-cheese-300mg', 'cbd-infused-organic-dog-treats-2' ) ),
			'RQ-KIT-PET-SENIOR'         => array( 'image' => 'dog-skin-care-conditions-lifestyle', 'gallery' => array( 'dog-skin-care-cream-manuka-honey-4oz', 'cbd-hemp-oil-pets-beef-cheese-300mg', 'diabetic-dog-treats-2' ) ),
			'RQ-KIT-HORSE-SKIN'         => array( 'image' => 'horse-skin-care-natural-healing-lifestyle', 'gallery' => array( 'horse-skin-care-cream-manuka-honey-8oz', 'horse-skin-care-vet-recommended-before-after', 'ingredients-cream-honey-aloe-triptych' ) ),
			'RQ-KIT-HUM-WOMENS'         => array( 'image' => 'womens-complete-5piece-bundle', 'gallery' => array( 'womens-ultimate-face-body-cream', 'womens-face-body-wash', 'womens-night-serum' ) ),
			'RQ-KIT-HUM-ANTIAGING'      => array( 'image' => 'womens-skincare-3piece-bundle', 'gallery' => array( 'womens-night-serum', 'womens-microderm-scrub', 'womens-ultimate-face-body-cream' ) ),
			'RQ-KIT-HUM-WOMENS-STARTER' => array( 'image' => 'womens-skincare-3piece-bundle', 'gallery' => array( 'womens-face-body-wash', 'womens-ultimate-face-body-cream' ) ),
			'RQ-KIT-HUM-MENS'           => array( 'image' => 'mens-line-complete-collection', 'gallery' => array( 'mens-face-body-wash', 'mens-face-body-moisturizer-2', 'mens-anti-aging-night-serum' ) ),
			'RQ-KIT-HUM-MENS-ELITE'     => array( 'image' => 'mens-line-complete-collection', 'gallery' => array( 'mens-face-body-wash', 'mens-face-body-moisturizer-2', 'mens-skin-care-cream-8oz', 'mens-anti-aging-night-serum' ) ),
			'RQ-DUO-HUM-MENSHAIR'       => array( 'image' => 'mens-hair-loss-prevention-shampoo', 'gallery' => array( 'womens-hair-loss-shampoo-conditioner-bundle' ) ),
			'RQ-DUO-HUM-HAIR'           => array( 'image' => 'womens-hair-loss-shampoo-conditioner-bundle', 'gallery' => array( 'womens-hair-loss-prevention-shampoo', 'womens-hair-loss-prevention-conditioner' ) ),
			'RQ-DUO-BABY-BATH'          => array( 'image' => 'baby-skin-treatment-lifestyle', 'gallery' => array( 'baby-face-body-wash', 'baby-skin-treatment' ) ),
			'RQ-KIT-HCBD-NIGHT'         => array( 'image' => 'sleep-gummies-cbd', 'gallery' => array( 'cbd-bath-bomb-for-pain' ) ),
			'RQ-KIT-HCBD-RELIEF'        => array( 'image' => 'intensive-releif-rub-cbd', 'gallery' => array( 'cbd-bath-bomb-for-healing' ) ),
			'RQ-KIT-HCBD-DAILY'         => array( 'image' => 'full-spectrum-cbd-oil-2', 'gallery' => array( 'vegan-gummies-cbd' ) ),
			'RQ-PK-HCBD-BATH-3'         => array( 'image' => 'cbd-bath-bomb-for-pain', 'gallery' => array( 'cbd-bath-bomb-for-healing' ) ),
			'RQ-PK-HCBD-BATH-5'         => array( 'image' => 'cbd-bath-bomb-for-pain', 'gallery' => array( 'cbd-bath-bomb-for-healing' ) ),
			'RQ-PK-HCBD-GUMMIES-2'      => array( 'image' => 'vegan-gummies-cbd' ),
			'RQ-PK-HCBD-GUMMIES-3'      => array( 'image' => 'vegan-gummies-cbd' ),
			'RQ-PK-PET-DIABETIC-2'      => array( 'image' => 'diabetic-dog-treats-2', 'gallery' => array( 'diabetic-dog-treats-alt' ) ),
			'RQ-PK-PET-DIABETIC-3'      => array( 'image' => 'diabetic-dog-treats-2', 'gallery' => array( 'diabetic-dog-treats-alt' ) ),
		);
	}
}
