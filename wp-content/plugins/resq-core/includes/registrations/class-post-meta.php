<?php
/**
 * Post meta registration for products and routines.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers plugin-owned post meta keys.
 */
class ResQ_Core_Registrations_Post_Meta {

	/**
	 * Allowed compliance flag slugs.
	 *
	 * @return string[]
	 */
	public static function compliance_flag_allowlist(): array {
		return array( 'cbd', 'medical-adjacent', 'pet-health', 'baby', 'proof', 'donation' );
	}

	/**
	 * Allowed compliance zone slugs.
	 *
	 * @return string[]
	 */
	public static function compliance_zone_allowlist(): array {
		return array( 'standard', 'cbd', 'baby', 'pet-health' );
	}

	/**
	 * Allowed gateway featured slugs.
	 *
	 * @return string[]
	 */
	public static function gateway_featured_allowlist(): array {
		return array( 'human', 'pet', 'bundles', 'cbd', 'learn' );
	}

	/**
	 * Register all post meta keys.
	 */
	public static function register(): void {
		self::register_product_meta();
		self::register_routine_meta();
	}

	/**
	 * Register product meta keys.
	 */
	private static function register_product_meta(): void {
		$product_args = array(
			'object_subtype' => 'product',
			'type'           => 'string',
			'single'         => true,
			'show_in_rest'   => true,
			'auth_callback'  => static function (): bool {
				return current_user_can( 'edit_products' );
			},
		);

		register_post_meta(
			'product',
			'_resq_canonical_product_id',
			array_merge(
				$product_args,
				array(
					'type'              => 'integer',
					'default'           => 0,
					'sanitize_callback' => static function ( $value ): int {
						return absint( $value );
					},
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_compliance_flags',
			array_merge(
				$product_args,
				array(
					'type'              => 'array',
					'default'           => array(),
					'sanitize_callback' => array( self::class, 'sanitize_compliance_flags' ),
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_compliance_zone',
			array_merge(
				$product_args,
				array(
					'default'           => 'standard',
					'sanitize_callback' => array( self::class, 'sanitize_compliance_zone' ),
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_routine_ids',
			array_merge(
				$product_args,
				array(
					'type'              => 'array',
					'default'           => array(),
					'sanitize_callback' => array( self::class, 'sanitize_int_array' ),
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_routine_step_order',
			array_merge(
				$product_args,
				array(
					'type'              => 'integer',
					'default'           => 0,
					'sanitize_callback' => static function ( $value ): int {
						return absint( $value );
					},
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_primary_routine_id',
			array_merge(
				$product_args,
				array(
					'type'              => 'integer',
					'default'           => 0,
					'sanitize_callback' => static function ( $value ): int {
						return absint( $value );
					},
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_bundle_product_ids',
			array_merge(
				$product_args,
				array(
					'type'              => 'array',
					'default'           => array(),
					'sanitize_callback' => array( self::class, 'sanitize_bundle_product_ids' ),
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_fbt_product_ids',
			array_merge(
				$product_args,
				array(
					'type'              => 'array',
					'default'           => array(),
					'sanitize_callback' => array( self::class, 'sanitize_int_array' ),
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_ingredient_profile',
			array_merge(
				$product_args,
				array(
					'type'              => 'array',
					'default'           => array(),
					'sanitize_callback' => array( self::class, 'sanitize_ingredient_profile' ),
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_short_benefit_tags',
			array_merge(
				$product_args,
				array(
					'type'              => 'array',
					'default'           => array(),
					'sanitize_callback' => array( self::class, 'sanitize_benefit_tags' ),
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_product_card_subtitle',
			array_merge(
				$product_args,
				array(
					'default'           => '',
					'sanitize_callback' => static function ( $value ): string {
						return sanitize_text_field( (string) $value );
					},
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_gateway_featured',
			array_merge(
				$product_args,
				array(
					'type'              => 'array',
					'default'           => array(),
					'sanitize_callback' => array( self::class, 'sanitize_gateway_featured' ),
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_learn_links',
			array_merge(
				$product_args,
				array(
					'type'              => 'array',
					'default'           => array(),
					'sanitize_callback' => array( self::class, 'sanitize_learn_links' ),
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_donation_eligible',
			array_merge(
				$product_args,
				array(
					'type'              => 'boolean',
					'default'           => false,
					'sanitize_callback' => static function ( $value ): bool {
						return (bool) $value;
					},
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_badge_label',
			array_merge(
				$product_args,
				array(
					'default'           => '',
					'sanitize_callback' => static function ( $value ): string {
						return sanitize_text_field( (string) $value );
					},
				)
			)
		);

		register_post_meta(
			'product',
			'_resq_badge_type',
			array_merge(
				$product_args,
				array(
					'default'           => '',
					'sanitize_callback' => static function ( $value ): string {
						return sanitize_key( (string) $value );
					},
				)
			)
		);
	}

	/**
	 * Register routine CPT meta keys.
	 */
	private static function register_routine_meta(): void {
		$routine_args = array(
			'object_subtype' => 'resq_routine',
			'single'         => true,
			'show_in_rest'   => true,
			'auth_callback'  => static function (): bool {
				return current_user_can( 'edit_posts' );
			},
		);

		register_post_meta(
			'resq_routine',
			'_resq_routine_steps',
			array_merge(
				$routine_args,
				array(
					'type'              => 'array',
					'default'           => array(),
					'sanitize_callback' => array( self::class, 'sanitize_routine_steps' ),
				)
			)
		);

		register_post_meta(
			'resq_routine',
			'_resq_routine_bundle_target',
			array_merge(
				$routine_args,
				array(
					'type'              => 'integer',
					'default'           => 0,
					'sanitize_callback' => static function ( $value ): int {
						return absint( $value );
					},
				)
			)
		);

		register_post_meta(
			'resq_routine',
			'_resq_routine_audience',
			array_merge(
				$routine_args,
				array(
					'type'              => 'string',
					'default'           => '',
					'sanitize_callback' => static function ( $value ): string {
						return sanitize_key( (string) $value );
					},
				)
			)
		);

		register_post_meta(
			'resq_routine',
			'_resq_routine_compliance_restrictions',
			array_merge(
				$routine_args,
				array(
					'type'              => 'array',
					'default'           => array(),
					'sanitize_callback' => static function ( $value ): array {
						return is_array( $value ) ? $value : array();
					},
				)
			)
		);
	}

	/**
	 * @param mixed $value Raw value.
	 * @return string[]
	 */
	public static function sanitize_compliance_flags( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}

		$allowlist = self::compliance_flag_allowlist();
		$clean     = array();

		foreach ( $value as $flag ) {
			$flag = sanitize_key( (string) $flag );
			if ( in_array( $flag, $allowlist, true ) ) {
				$clean[] = $flag;
			}
		}

		return array_values( array_unique( $clean ) );
	}

	/**
	 * @param mixed $value Raw value.
	 * @return string
	 */
	public static function sanitize_compliance_zone( $value ): string {
		$slug      = sanitize_key( (string) $value );
		$allowlist = self::compliance_zone_allowlist();

		return in_array( $slug, $allowlist, true ) ? $slug : 'standard';
	}

	/**
	 * @param mixed $value Raw value.
	 * @return int[]
	 */
	public static function sanitize_int_array( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}

		return array_values(
			array_filter(
				array_map( 'absint', $value )
			)
		);
	}

	/**
	 * @param mixed $value Raw value.
	 * @return array<int, array{product_id: int, qty: int}>
	 */
	public static function sanitize_bundle_product_ids( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}

		$clean = array();

		foreach ( $value as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$product_id = absint( $item['product_id'] ?? 0 );
			$qty        = max( 1, absint( $item['qty'] ?? 1 ) );

			if ( $product_id > 0 ) {
				$clean[] = array(
					'product_id' => $product_id,
					'qty'        => $qty,
				);
			}
		}

		return $clean;
	}

	/**
	 * @param mixed $value Raw value.
	 * @return array<int, array<string, mixed>>
	 */
	public static function sanitize_ingredient_profile( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}

		$clean = array();

		foreach ( $value as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$clean[] = array(
				'term_slug'  => sanitize_key( (string) ( $item['term_slug'] ?? '' ) ),
				'label'      => sanitize_text_field( (string) ( $item['label'] ?? '' ) ),
				'descriptor' => sanitize_text_field( (string) ( $item['descriptor'] ?? '' ) ),
				'claim_safe' => ! empty( $item['claim_safe'] ),
			);
		}

		return $clean;
	}

	/**
	 * @param mixed $value Raw value.
	 * @return string[]
	 */
	public static function sanitize_benefit_tags( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}

		$tags = array();

		foreach ( array_slice( $value, 0, 5 ) as $tag ) {
			$tag = sanitize_text_field( (string) $tag );
			if ( '' !== $tag ) {
				$tags[] = $tag;
			}
		}

		return $tags;
	}

	/**
	 * @param mixed $value Raw value.
	 * @return string[]
	 */
	public static function sanitize_gateway_featured( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}

		$allowlist = self::gateway_featured_allowlist();
		$clean     = array();

		foreach ( $value as $slug ) {
			$slug = sanitize_key( (string) $slug );
			if ( in_array( $slug, $allowlist, true ) ) {
				$clean[] = $slug;
			}
		}

		return array_values( array_unique( $clean ) );
	}

	/**
	 * @param mixed $value Raw value.
	 * @return array<int, array{post_id: int, label: string}>
	 */
	public static function sanitize_learn_links( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}

		$clean = array();

		foreach ( $value as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$post_id = absint( $item['post_id'] ?? 0 );
			if ( $post_id <= 0 ) {
				continue;
			}

			$clean[] = array(
				'post_id' => $post_id,
				'label'   => sanitize_text_field( (string) ( $item['label'] ?? '' ) ),
			);
		}

		return $clean;
	}

	/**
	 * @param mixed $value Raw value.
	 * @return array<int, array<string, mixed>>
	 */
	public static function sanitize_routine_steps( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}

		$clean = array();

		foreach ( $value as $step ) {
			if ( ! is_array( $step ) ) {
				continue;
			}

			$clean[] = array(
				'order'       => absint( $step['order'] ?? 0 ),
				'title'       => sanitize_text_field( (string) ( $step['title'] ?? '' ) ),
				'product_id'  => absint( $step['product_id'] ?? 0 ),
				'bundle_id'   => absint( $step['bundle_id'] ?? 0 ),
				'is_optional' => ! empty( $step['is_optional'] ),
			);
		}

		usort(
			$clean,
			static function ( array $a, array $b ): int {
				return $a['order'] <=> $b['order'];
			}
		);

		return $clean;
	}
}
