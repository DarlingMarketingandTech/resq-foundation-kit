<?php
/**
 * Custom post type registration.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers ResQ custom post types.
 */
class ResQ_Core_Registrations_Cpt {

	/**
	 * Register all CPTs.
	 */
	public static function register(): void {
		self::register_routine();
	}

	/**
	 * resq_routine — ordered regimen definitions.
	 */
	private static function register_routine(): void {
		register_post_type(
			'resq_routine',
			array(
				'labels'       => array(
					'name'          => __( 'Routines', 'resq-core' ),
					'singular_name' => __( 'Routine', 'resq-core' ),
				),
				'public'       => false,
				'show_ui'      => true,
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'custom-fields' ),
				'menu_icon'    => 'dashicons-list-view',
			)
		);
	}
}
