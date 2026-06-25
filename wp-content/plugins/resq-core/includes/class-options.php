<?php
/**
 * Plugin options and defaults.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Options — manages plugin option defaults and access.
 *
 * Option keys from docs/11-PLUGIN-DATA-SCHEMA.md:
 *   resq_core_version, resq_core_features, resq_core_settings,
 *   resq_core_compliance, resq_core_merchandising.
 */
class ResQ_Core_Options {

	/**
	 * Default feature flags (docs/11 § Feature flag map).
	 *
	 * Note: `learn_bridges` is the feature flag for Learn-to-shop bridge
	 * modules. The separate product meta key `_resq_learn_links` stores
	 * per-product Learn guide links; the helper is
	 * `resq_get_learn_links_for_product()`. Do not conflate the two.
	 *
	 * @return array<string, bool>
	 */
	public static function default_features(): array {
		return array(
			'routine_ladder'          => true,
			'cart_drawer_suggestions' => true,
			'cbd_isolation'           => true,
			'donation_display'        => false,
			'gateway_featured'        => true,
			'learn_bridges'           => true,
			// Phase 10 compliance features. Enabled on the dev site so the owner
			// can review each surface; all chrome copy is neutral (non-claim) and
			// every legal/claim string (CBD disclaimer, restricted-state list) is
			// left empty for owner/legal sign-off before launch.
			'coa_disclosure'          => true,
			'state_restriction'       => true,
			'age_gate'                => true,
			'cookie_consent'          => true,
		);
	}

	/**
	 * Default compliance settings (docs/11 § Compliance options).
	 *
	 * @return array<string, mixed>
	 */
	public static function default_compliance(): array {
		return array(
			'cbd_isolation_enabled'    => true,
			'cart_isolation_enabled'   => true,
			'donation_display_enabled' => false,
			'notice_text'              => array(
				'standard'   => '',
				'cbd'        => '',
				'baby'       => '',
				'pet-health' => '',
			),

			// Phase 10 A3 — state restriction. `restricted_states` is the
			// owner/legal-supplied list of USPS state codes blocked at checkout
			// for CBD carts. Empty by default → blocks nothing until configured.
			// `state_restriction_notice` is owner copy; empty → a neutral
			// fallback is shown.
			'restricted_states'        => array(),
			'state_restriction_notice' => '',

			// Phase 10 A4 — age gate minimum age for CBD surfaces.
			'age_gate_min_age'         => 21,
		);
	}

	/**
	 * Default merchandising settings (docs/11 § Merchandising options).
	 *
	 * @return array<string, mixed>
	 */
	public static function default_merchandising(): array {
		return array(
			'routine_ladder_enabled'          => true,
			'cart_drawer_suggestions_enabled' => true,
			'default_badge_config'            => array(
				array(
					'condition' => 'on_sale',
					'label'     => __( 'Sale', 'resq-core' ),
					'type'      => 'sale',
					'priority'  => 5,
				),
				array(
					'condition' => 'is_bundle',
					'label'     => __( 'Bundle', 'resq-core' ),
					'type'      => 'bundle',
					'priority'  => 10,
				),
			),
		);
	}

	/**
	 * All option keys with their defaults.
	 *
	 * @return array<string, mixed>
	 */
	public static function all_defaults(): array {
		return array(
			'resq_core_version'       => RESQ_CORE_VERSION,
			'resq_core_features'      => self::default_features(),
			'resq_core_settings'      => array(),
			'resq_core_compliance'    => self::default_compliance(),
			'resq_core_merchandising' => self::default_merchandising(),
		);
	}

	/**
	 * Set all defaults unconditionally (activation hook).
	 */
	public static function set_defaults(): void {
		foreach ( self::all_defaults() as $key => $default ) {
			if ( false === get_option( $key ) ) {
				add_option( $key, $default );
			}
		}

		update_option( 'resq_core_version', RESQ_CORE_VERSION );
	}

	/**
	 * Set defaults only when version option is absent (first load guard).
	 */
	public static function maybe_set_defaults(): void {
		if ( false === get_option( 'resq_core_version' ) ) {
			self::set_defaults();
		}
	}

	/**
	 * Retrieve a plugin option value.
	 *
	 * Supports dot-style keys for nested arrays, e.g.
	 * `resq_core_compliance.cbd_isolation_enabled`.
	 *
	 * @param string $key     Option key or dot-notation path.
	 * @param mixed  $default Fallback value.
	 * @return mixed
	 */
	public static function get( string $key, mixed $default = null ): mixed {
		$parts    = explode( '.', $key );
		$root_key = array_shift( $parts );
		$defaults = self::all_defaults();

		$root_default = $defaults[ $root_key ] ?? $default;
		$value        = get_option( $root_key, $root_default );

		foreach ( $parts as $segment ) {
			if ( is_array( $value ) && array_key_exists( $segment, $value ) ) {
				$value = $value[ $segment ];
			} else {
				return $default;
			}
		}

		return $value;
	}

	/**
	 * Check whether a feature flag is enabled.
	 *
	 * @param string $feature Feature key from `resq_core_features`.
	 * @return bool
	 */
	public static function feature_enabled( string $feature ): bool {
		$features = self::get( 'resq_core_features', array() );

		if ( ! is_array( $features ) || ! array_key_exists( $feature, $features ) ) {
			$defaults = self::default_features();
			return ! empty( $defaults[ $feature ] );
		}

		return (bool) apply_filters( 'resq_core_feature_enabled', ! empty( $features[ $feature ] ), $feature );
	}
}
