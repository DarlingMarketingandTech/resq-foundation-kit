<?php
/**
 * Phase 7 demo fixture catalog — safe placeholder data only.
 *
 * No production SKUs, prices, claims, or images. For local sandbox use.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_fixtures_get_catalog' ) ) {
	/**
	 * Return the fixture catalog definition.
	 *
	 * @return array{products: array<string, array<string, mixed>>, routines: array<string, array<string, mixed>>, bundles: array<string, array<string, mixed>>}
	 */
	function resq_fixtures_get_catalog(): array {
		return array(
			'products' => array(
				'fixture-human-comfort-cream'   => array(
					'name'               => 'Demo Comfort Moisturizer',
					'type'               => 'variable',
					'price'              => '19.00',
					'categories'         => array(
						array(
							'slug' => 'fixture-human-care',
							'name' => 'Fixture Human Care',
						),
					),
					'audiences'          => array( 'human' ),
					'concerns'           => array( 'dry-skin-comfort', 'daily-moisture' ),
					'ingredients'        => array( 'demo-botanical-extract' ),
					'compliance_flags'   => array(),
					'zone'               => 'standard',
					'card_sub'           => 'Placeholder daily moisture support',
					'benefits'           => array( 'Fragrance-free', 'Gentle formula', 'Demo only' ),
					'gateway_featured'   => array( 'human' ),
					'fbt'                => array( 'fixture-daily-face-wash', 'fixture-night-support-serum' ),
					'canonical_target_sku' => '',
					'ingredient_profile' => array(
						array(
							'term_slug'  => 'demo-botanical-extract',
							'label'      => 'Demo Botanical Extract',
							'descriptor' => 'Placeholder ingredient descriptor for sandbox testing.',
							'claim_safe' => true,
						),
					),
					'variations'         => array(
						array(
							'sku'        => 'fixture-human-comfort-cream-2oz',
							'attributes' => array( 'size' => '2oz' ),
							'price'      => '19.00',
						),
						array(
							'sku'        => 'fixture-human-comfort-cream-4oz',
							'attributes' => array( 'size' => '4oz' ),
							'price'      => '24.00',
						),
					),
				),
				'fixture-comfort-cream-listing' => array(
					'name'               => 'Demo Comfort Moisturizer (Gateway Listing)',
					'type'               => 'simple',
					'price'              => '19.00',
					'categories'         => array(
						array(
							'slug' => 'fixture-human-care',
							'name' => 'Fixture Human Care',
						),
					),
					'audiences'          => array( 'human' ),
					'concerns'           => array( 'dry-skin-comfort' ),
					'ingredients'        => array(),
					'compliance_flags'   => array(),
					'zone'               => 'standard',
					'card_sub'           => 'Canonical mapping demo listing',
					'benefits'           => array( 'Demo alias', 'Same parent SKU' ),
					'gateway_featured'   => array(),
					'fbt'                => array(),
					'canonical_target_sku' => 'fixture-human-comfort-cream',
					'ingredient_profile' => array(),
					'variations'         => array(),
				),
				'fixture-daily-face-wash'       => array(
					'name'               => 'Demo Gentle Face Wash',
					'type'               => 'simple',
					'price'              => '14.00',
					'categories'         => array(
						array(
							'slug' => 'fixture-human-care',
							'name' => 'Fixture Human Care',
						),
					),
					'audiences'          => array( 'human' ),
					'concerns'           => array( 'daily-cleansing' ),
					'ingredients'        => array(),
					'compliance_flags'   => array(),
					'zone'               => 'standard',
					'card_sub'           => 'Placeholder cleanse step',
					'benefits'           => array( 'Soap-free', 'Demo only' ),
					'gateway_featured'   => array( 'human' ),
					'fbt'                => array(),
					'canonical_target_sku' => '',
					'ingredient_profile' => array(),
					'variations'         => array(),
				),
				'fixture-night-support-serum'   => array(
					'name'               => 'Demo Night Support Serum',
					'type'               => 'simple',
					'price'              => '22.00',
					'categories'         => array(
						array(
							'slug' => 'fixture-human-care',
							'name' => 'Fixture Human Care',
						),
					),
					'audiences'          => array( 'human' ),
					'concerns'           => array( 'overnight-comfort' ),
					'ingredients'        => array(),
					'compliance_flags'   => array(),
					'zone'               => 'standard',
					'card_sub'           => 'Placeholder overnight step',
					'benefits'           => array( 'Lightweight', 'Demo only' ),
					'gateway_featured'   => array(),
					'fbt'                => array(),
					'canonical_target_sku' => '',
					'ingredient_profile' => array(),
					'variations'         => array(),
				),
				'fixture-baby-gentle-balm'      => array(
					'name'               => 'Demo Baby Gentle Balm',
					'type'               => 'simple',
					'price'              => '16.00',
					'categories'         => array(
						array(
							'slug' => 'fixture-baby-care',
							'name' => 'Fixture Baby Care',
						),
					),
					'audiences'          => array( 'human' ),
					'concerns'           => array( 'sensitive-skin-comfort' ),
					'ingredients'        => array(),
					'compliance_flags'   => array( 'baby' ),
					'zone'               => 'baby',
					'card_sub'           => 'Placeholder baby-safe support',
					'benefits'           => array( 'Gentle', 'Demo only' ),
					'gateway_featured'   => array(),
					'fbt'                => array(),
					'canonical_target_sku' => '',
					'ingredient_profile' => array(),
					'variations'         => array(),
				),
				'fixture-pet-coat-wash'         => array(
					'name'               => 'Demo Pet Coat Wash',
					'type'               => 'simple',
					'price'              => '15.00',
					'categories'         => array(
						array(
							'slug' => 'fixture-pet-care',
							'name' => 'Fixture Pet Care',
						),
					),
					'audiences'          => array( 'pet' ),
					'concerns'           => array( 'coat-comfort', 'seasonal-itch-support' ),
					'ingredients'        => array(),
					'compliance_flags'   => array( 'pet-health' ),
					'zone'               => 'pet-health',
					'card_sub'           => 'Placeholder pet coat cleanse',
					'benefits'           => array( 'Rinse-friendly', 'Demo only' ),
					'gateway_featured'   => array( 'pet' ),
					'fbt'                => array(),
					'canonical_target_sku' => '',
					'ingredient_profile' => array(),
					'variations'         => array(),
				),
				'fixture-pet-skin-balm'         => array(
					'name'               => 'Demo Pet Skin Balm',
					'type'               => 'simple',
					'price'              => '17.00',
					'categories'         => array(
						array(
							'slug' => 'fixture-pet-care',
							'name' => 'Fixture Pet Care',
						),
					),
					'audiences'          => array( 'pet' ),
					'concerns'           => array( 'paw-comfort' ),
					'ingredients'        => array(),
					'compliance_flags'   => array( 'pet-health' ),
					'zone'               => 'pet-health',
					'card_sub'           => 'Placeholder topical pet support',
					'benefits'           => array( 'Demo only', 'Pet zone test' ),
					'gateway_featured'   => array( 'pet' ),
					'fbt'                => array( 'fixture-pet-coat-wash' ),
					'canonical_target_sku' => '',
					'ingredient_profile' => array(),
					'variations'         => array(),
				),
				'fixture-cbd-wellness-oil'      => array(
					'name'               => 'Demo Wellness Oil (CBD Zone)',
					'type'               => 'simple',
					'price'              => '42.00',
					'categories'         => array(
						array(
							'slug' => 'fixture-cbd-wellness',
							'name' => 'Fixture CBD Wellness',
						),
					),
					'audiences'          => array( 'human' ),
					'concerns'           => array( 'daily-wellness' ),
					'ingredients'        => array(),
					'compliance_flags'   => array( 'cbd' ),
					'zone'               => 'cbd',
					'card_sub'           => 'CBD isolation sandbox product',
					'benefits'           => array( 'Demo only', 'Zone isolated' ),
					'gateway_featured'   => array( 'cbd' ),
					'fbt'                => array(),
					'canonical_target_sku' => '',
					'ingredient_profile' => array(),
					'variations'         => array(),
				),
			),
			'routines' => array(
				'fixture-routine-human-daily' => array(
					'title'       => 'Demo Human Daily Comfort Routine',
					'audience'    => 'human',
					'steps'       => array(
						'fixture-daily-face-wash',
						'fixture-human-comfort-cream',
						'fixture-night-support-serum',
					),
					'primary_for' => 'fixture-human-comfort-cream',
				),
				'fixture-routine-pet-coat'    => array(
					'title'       => 'Demo Pet Coat Comfort Routine',
					'audience'    => 'pet',
					'steps'       => array(
						'fixture-pet-coat-wash',
						'fixture-pet-skin-balm',
					),
					'primary_for' => 'fixture-pet-skin-balm',
				),
			),
			'bundles'  => array(
				'fixture-bundle-daily-comfort' => array(
					'name'       => 'Demo Daily Comfort Kit',
					'price'      => '49.00',
					'zone'       => 'standard',
					'audiences'  => array( 'human' ),
					'components' => array(
						array(
							'sku' => 'fixture-daily-face-wash',
							'qty' => 1,
						),
						array(
							'sku' => 'fixture-human-comfort-cream',
							'qty' => 1,
						),
						array(
							'sku' => 'fixture-night-support-serum',
							'qty' => 1,
						),
					),
					'routine_slug' => 'fixture-routine-human-daily',
				),
				'fixture-bundle-pet-rescue'    => array(
					'name'       => 'Demo Pet Coat Rescue Kit',
					'price'      => '28.00',
					'zone'       => 'pet-health',
					'audiences'  => array( 'pet' ),
					'components' => array(
						array(
							'sku' => 'fixture-pet-coat-wash',
							'qty' => 1,
						),
						array(
							'sku' => 'fixture-pet-skin-balm',
							'qty' => 1,
						),
					),
					'routine_slug' => 'fixture-routine-pet-coat',
				),
			),
		);
	}
}
