<?php
/**
 * Term meta registration.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers plugin-owned term meta keys.
 */
class ResQ_Core_Registrations_Term_Meta {

	/**
	 * Register term meta for product categories and ResQ taxonomies.
	 */
	public static function register(): void {
		$keys = array(
			'_resq_canonical_targets'   => array( 'type' => 'array', 'default' => array(), 'rest_item_type' => 'integer' ),
			'_resq_category_hero_image' => array( 'type' => 'integer', 'default' => 0 ),
			'_resq_category_intro'      => array( 'type' => 'string', 'default' => '' ),
			'_resq_audience_type'       => array( 'type' => 'string', 'default' => '' ),
			'_resq_compliance_category' => array( 'type' => 'string', 'default' => '' ),
		);

		$taxonomies = array(
			'product_cat',
			'resq_audience',
			'resq_concern',
			'resq_ingredient',
			'resq_product_role',
			'resq_compliance_zone',
		);

		foreach ( $taxonomies as $taxonomy ) {
			foreach ( $keys as $meta_key => $config ) {
				register_term_meta(
					$taxonomy,
					$meta_key,
					array(
						'type'              => $config['type'],
						'single'            => true,
						'default'           => $config['default'],
						'show_in_rest'      => 'array' === $config['type']
							? ResQ_Core_Registrations_Post_Meta::rest_array_schema( $config['rest_item_type'] ?? 'string' )
							: true,
						'sanitize_callback' => self::get_sanitize_callback( $meta_key ),
						'auth_callback'     => static function (): bool {
							return current_user_can( 'manage_woocommerce' );
						},
					)
				);
			}
		}
	}

	/**
	 * @param string $meta_key Meta key.
	 * @return callable
	 */
	private static function get_sanitize_callback( string $meta_key ): callable {
		if ( '_resq_canonical_targets' === $meta_key ) {
			return array( ResQ_Core_Registrations_Post_Meta::class, 'sanitize_int_array' );
		}

		if ( '_resq_category_hero_image' === $meta_key ) {
			return static function ( $value ): int {
				return absint( $value );
			};
		}

		return static function ( $value ): string {
			return sanitize_text_field( (string) $value );
		};
	}
}
