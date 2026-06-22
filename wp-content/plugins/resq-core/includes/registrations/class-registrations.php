<?php
/**
 * Central registration loader.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Loads all Phase 3 data structure registrations.
 */
class ResQ_Core_Registrations {

	/**
	 * Register taxonomies, CPTs, and meta on init.
	 */
	public static function register_all(): void {
		ResQ_Core_Registrations_Taxonomies::register();
		ResQ_Core_Registrations_Cpt::register();
		ResQ_Core_Registrations_Post_Meta::register();
		ResQ_Core_Registrations_Term_Meta::register();
	}
}
