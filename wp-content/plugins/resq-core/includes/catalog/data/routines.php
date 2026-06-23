<?php
/**
 * Catalog routine definitions.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_catalog_get_routines' ) ) {
	/**
	 * @return array<string, array<string, mixed>>
	 */
	function resq_catalog_get_routines(): array {
		return array(
			'rq-routine-womens-daily' => array(
				'title'       => 'Women\'s Daily Repair Routine',
				'audience'    => 'human',
				'steps'       => array( 'RQ-HUM-WASH', 'RQ-HUM-AIOCREAM', 'RQ-HUM-NIGHTSERUM' ),
				'primary_for' => 'RQ-HUM-AIOCREAM',
			),
			'rq-routine-anti-aging' => array(
				'title'       => 'Anti-Aging Routine',
				'audience'    => 'human',
				'steps'       => array( 'RQ-HUM-NIGHTSERUM', 'RQ-HUM-SCRUB', 'RQ-HUM-AIOCREAM' ),
				'primary_for' => 'RQ-HUM-NIGHTSERUM',
			),
			'rq-routine-mens-core'  => array(
				'title'       => 'Men\'s Core Grooming Routine',
				'audience'    => 'human',
				'steps'       => array( 'RQ-HUM-MENSWASH', 'RQ-HUM-MOISTURIZER', 'RQ-HUM-MENSSERUM' ),
				'primary_for' => 'RQ-HUM-MOISTURIZER',
			),
			'rq-routine-pet-hotspot' => array(
				'title'       => 'Pet Hot Spot Rescue Routine',
				'audience'    => 'pet',
				'steps'       => array( 'RQ-PET-SHAMPOO', 'RQ-PET-CONDITIONER', 'RQ-PET-SKINCREAM' ),
				'primary_for' => 'RQ-PET-SKINCREAM',
			),
			'rq-routine-baby-bath'  => array(
				'title'       => 'Baby Gentle Skin Routine',
				'audience'    => 'human',
				'steps'       => array( 'RQ-BABY-WASH', 'RQ-BABY-CREAM' ),
				'primary_for' => 'RQ-BABY-CREAM',
			),
			'rq-routine-hair-duo'   => array(
				'title'       => 'Manuka Hair Care Routine',
				'audience'    => 'human',
				'steps'       => array( 'RQ-HUM-SHAMPOO', 'RQ-HUM-CONDITIONER' ),
				'primary_for' => 'RQ-HUM-SHAMPOO',
			),
		);
	}
}
