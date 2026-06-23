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

		return array(
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
	}
}
