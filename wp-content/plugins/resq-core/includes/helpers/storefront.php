<?php
/**
 * Storefront helper functions.
 *
 * All 19 public helpers from docs/12-PLUGIN-HELPER-CONTRACTS.md.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/*
 * ──────────────────────────────────────────────────
 *  Product Intelligence
 * ──────────────────────────────────────────────────
 */

if ( ! function_exists( 'resq_get_product_audiences' ) ) {
	/**
	 * Return audience terms assigned to a product.
	 *
	 * @param int $product_id WooCommerce product or variation ID.
	 * @return array[] List of audience objects.
	 */
	function resq_get_product_audiences( int $product_id ): array {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 || ! taxonomy_exists( 'resq_audience' ) ) {
			return array();
		}

		$cache_key = 'product_audiences_' . $product_id;
		$cached    = ResQ_Core_Cache::get( $cache_key );
		if ( false !== $cached && is_array( $cached ) ) {
			return $cached;
		}

		$terms = wp_get_post_terms( $product_id, 'resq_audience' );
		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		$result = array_map( 'resq_map_term_to_audience', $terms );
		ResQ_Core_Cache::set( $cache_key, $result );

		return $result;
	}
}

if ( ! function_exists( 'resq_get_product_concerns' ) ) {
	/**
	 * Return concern/problem terms for discovery and filters.
	 *
	 * @param int $product_id WooCommerce product or variation ID.
	 * @return array[] List of concern objects with parent context.
	 */
	function resq_get_product_concerns( int $product_id ): array {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 || ! taxonomy_exists( 'resq_concern' ) ) {
			return array();
		}

		$cache_key = 'product_concerns_' . $product_id;
		$cached    = ResQ_Core_Cache::get( $cache_key );
		if ( false !== $cached && is_array( $cached ) ) {
			return $cached;
		}

		$terms = wp_get_post_terms( $product_id, 'resq_concern' );
		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		$result = array_map( 'resq_map_term_to_concern', $terms );
		ResQ_Core_Cache::set( $cache_key, $result );

		return $result;
	}
}

if ( ! function_exists( 'resq_get_product_routines' ) ) {
	/**
	 * Return routines that include this product.
	 *
	 * @param int $product_id WooCommerce product or variation ID.
	 * @return array[] List of routine summary objects.
	 */
	function resq_get_product_routines( int $product_id ): array {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 ) {
			return array();
		}

		$cache_key = 'product_routines_' . $product_id;
		$cached    = ResQ_Core_Cache::get( $cache_key );
		if ( false !== $cached && is_array( $cached ) ) {
			return $cached;
		}

		$routine_ids = resq_get_product_meta( $product_id, '_resq_routine_ids', array() );
		if ( ! is_array( $routine_ids ) || empty( $routine_ids ) ) {
			return array();
		}

		$primary_id = (int) resq_get_product_meta( $product_id, '_resq_primary_routine_id', 0 );
		if ( $primary_id <= 0 ) {
			$primary_id = (int) ( $routine_ids[0] ?? 0 );
		}

		$result = array();

		foreach ( $routine_ids as $routine_id ) {
			$routine_id = (int) $routine_id;
			if ( $routine_id <= 0 || 'resq_routine' !== get_post_type( $routine_id ) ) {
				continue;
			}

			$steps         = resq_get_routine_meta( $routine_id, '_resq_routine_steps', array() );
			$bundle_target = (int) resq_get_routine_meta( $routine_id, '_resq_routine_bundle_target', 0 );

			$result[] = array(
				'routine_id'    => $routine_id,
				'title'         => get_the_title( $routine_id ),
				'slug'          => get_post_field( 'post_name', $routine_id ),
				'step_count'    => is_array( $steps ) ? count( $steps ) : 0,
				'is_primary'    => $routine_id === $primary_id,
				'bundle_target' => $bundle_target,
			);
		}

		ResQ_Core_Cache::set( $cache_key, $result );

		return $result;
	}
}

if ( ! function_exists( 'resq_get_routine_steps' ) ) {
	/**
	 * Return ordered steps for a routine.
	 *
	 * @param int $routine_id         resq_routine CPT post ID.
	 * @param int $current_product_id Optional current PDP product marker.
	 * @return array[] Ordered step objects.
	 */
	function resq_get_routine_steps( int $routine_id, int $current_product_id = 0 ): array {
		if ( $routine_id <= 0 || 'resq_routine' !== get_post_type( $routine_id ) ) {
			return array();
		}

		$current_product_id = resq_resolve_product_id( $current_product_id );

		$cache_key = 'routine_steps_' . $routine_id;
		$cached    = ResQ_Core_Cache::get( $cache_key );
		$raw_steps = false !== $cached ? $cached : null;

		if ( null === $raw_steps ) {
			$raw_steps = resq_get_routine_meta( $routine_id, '_resq_routine_steps', array() );
			if ( ! is_array( $raw_steps ) ) {
				$raw_steps = array();
			}
			ResQ_Core_Cache::set( $cache_key, $raw_steps );
		}

		if ( empty( $raw_steps ) ) {
			return array();
		}

		usort(
			$raw_steps,
			static function ( array $a, array $b ): int {
				return (int) ( $a['order'] ?? 0 ) <=> (int) ( $b['order'] ?? 0 );
			}
		);

		$result = array();

		foreach ( $raw_steps as $step ) {
			if ( ! is_array( $step ) ) {
				continue;
			}

			$step_product_id = (int) ( $step['product_id'] ?? 0 );
			$canonical_id    = $step_product_id > 0
				? ( resq_get_canonical_product_id( $step_product_id, 'product' ) ?? $step_product_id )
				: 0;

			$summary = $step_product_id > 0 ? resq_get_wc_product_summary( $step_product_id ) : null;

			if ( ! $summary && $step_product_id > 0 ) {
				continue;
			}

			$result[] = array(
				'order'         => (int) ( $step['order'] ?? 0 ),
				'title'         => (string) ( $step['title'] ?? '' ),
				'product_id'    => $step_product_id,
				'canonical_id'  => $canonical_id,
				'bundle_id'     => (int) ( $step['bundle_id'] ?? 0 ),
				'is_current'    => $current_product_id > 0 && $step_product_id === $current_product_id,
				'is_optional'   => ! empty( $step['is_optional'] ),
				'product_title' => $summary['title'] ?? '',
				'product_url'   => $summary['url'] ?? '',
				'in_stock'      => $summary['in_stock'] ?? false,
			);
		}

		return $result;
	}
}

if ( ! function_exists( 'resq_get_product_ingredient_profile' ) ) {
	/**
	 * Return claim-safe ingredient profile for PDP and Learn modules.
	 *
	 * @param int $product_id WooCommerce product or variation ID.
	 * @return array[] List of ingredient descriptor objects.
	 */
	function resq_get_product_ingredient_profile( int $product_id ): array {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 ) {
			return array();
		}

		$profile = resq_get_product_meta( $product_id, '_resq_ingredient_profile', array() );
		if ( ! is_array( $profile ) ) {
			$profile = array();
		}

		$term_map = array();
		if ( taxonomy_exists( 'resq_ingredient' ) ) {
			$terms = wp_get_post_terms( $product_id, 'resq_ingredient' );
			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$term_map[ $term->slug ] = $term;
				}
			}
		}

		if ( empty( $profile ) && empty( $term_map ) ) {
			return array();
		}

		$result  = array();
		$seen    = array();

		foreach ( $profile as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$slug = sanitize_key( (string) ( $item['term_slug'] ?? '' ) );
			$term = $slug && isset( $term_map[ $slug ] ) ? $term_map[ $slug ] : null;

			$result[] = array(
				'term_id'    => $term ? (int) $term->term_id : 0,
				'term_slug'  => $slug,
				'label'      => (string) ( $item['label'] ?? ( $term ? $term->name : '' ) ),
				'descriptor' => (string) ( $item['descriptor'] ?? '' ),
				'claim_safe' => ! empty( $item['claim_safe'] ),
			);

			if ( $slug ) {
				$seen[ $slug ] = true;
			}
		}

		foreach ( $term_map as $slug => $term ) {
			if ( isset( $seen[ $slug ] ) ) {
				continue;
			}

			$result[] = array(
				'term_id'    => (int) $term->term_id,
				'term_slug'  => $slug,
				'label'      => $term->name,
				'descriptor' => '',
				'claim_safe' => true,
			);
		}

		return $result;
	}
}

/*
 * ──────────────────────────────────────────────────
 *  Canonical Mapping
 * ──────────────────────────────────────────────────
 */

if ( ! function_exists( 'resq_get_canonical_product_id' ) ) {
	/**
	 * Resolve any source to a canonical WooCommerce product ID.
	 *
	 * @param int|string $source_id   ID or route slug.
	 * @param string     $source_type Resolver context.
	 * @return int|null Canonical product ID or null.
	 */
	function resq_get_canonical_product_id( int|string $source_id, string $source_type = 'product' ): ?int {
		$allowed = array( 'product', 'variation', 'term', 'page', 'route', 'routine', 'bundle', 'learn' );
		if ( ! in_array( $source_type, $allowed, true ) ) {
			return null;
		}

		switch ( $source_type ) {
			case 'product':
				if ( ! is_numeric( $source_id ) || (int) $source_id <= 0 ) {
					return null;
				}

				$product_id = resq_resolve_product_id( (int) $source_id );
				if ( $product_id <= 0 ) {
					return null;
				}

				$override = (int) resq_get_product_meta( $product_id, '_resq_canonical_product_id', 0 );
				if ( $override > 0 && resq_product_exists( $override ) ) {
					return $override;
				}

				return $product_id;

			case 'variation':
				if ( ! is_numeric( $source_id ) || (int) $source_id <= 0 ) {
					return null;
				}

				$parent_id = resq_resolve_product_id( (int) $source_id );
				return $parent_id > 0 ? $parent_id : null;

			case 'bundle':
				if ( ! is_numeric( $source_id ) || (int) $source_id <= 0 ) {
					return null;
				}

				return resq_product_exists( (int) $source_id ) ? (int) $source_id : null;

			case 'routine':
				if ( ! is_numeric( $source_id ) || (int) $source_id <= 0 ) {
					return null;
				}

				$routine_id = (int) $source_id;
				if ( 'resq_routine' !== get_post_type( $routine_id ) ) {
					return null;
				}

				$bundle_target = (int) resq_get_routine_meta( $routine_id, '_resq_routine_bundle_target', 0 );
				if ( $bundle_target > 0 && resq_product_exists( $bundle_target ) ) {
					return $bundle_target;
				}

				$steps = resq_get_routine_meta( $routine_id, '_resq_routine_steps', array() );
				if ( is_array( $steps ) && ! empty( $steps ) ) {
					usort(
						$steps,
						static function ( array $a, array $b ): int {
							return (int) ( $a['order'] ?? 0 ) <=> (int) ( $b['order'] ?? 0 );
						}
					);

					$first_product = (int) ( $steps[0]['product_id'] ?? 0 );
					if ( $first_product > 0 ) {
						return resq_get_canonical_product_id( $first_product, 'product' );
					}
				}

				return null;

			case 'term':
				if ( ! is_numeric( $source_id ) || (int) $source_id <= 0 ) {
					return null;
				}

				$term = get_term( (int) $source_id );
				if ( ! $term || is_wp_error( $term ) ) {
					return null;
				}

				$targets = get_term_meta( (int) $term->term_id, '_resq_canonical_targets', true );
				if ( is_array( $targets ) && ! empty( $targets ) ) {
					$first = (int) $targets[0];
					return $first > 0 ? $first : null;
				}

				return null;

			case 'page':
			case 'learn':
				if ( ! is_numeric( $source_id ) || (int) $source_id <= 0 ) {
					return null;
				}

				$targets = get_post_meta( (int) $source_id, '_resq_canonical_targets', true );
				if ( is_array( $targets ) && ! empty( $targets ) ) {
					$first = (int) $targets[0];
					return $first > 0 ? $first : null;
				}

				return null;

			case 'route':
				$slug = sanitize_key( (string) $source_id );
				$map  = resq_get_route_canonical_map();

				if ( ! array_key_exists( $slug, $map ) || null === $map[ $slug ] ) {
					return null;
				}

				$mapped = (int) $map[ $slug ];
				return $mapped > 0 ? $mapped : null;
		}

		return null;
	}
}

if ( ! function_exists( 'resq_resolve_product_context' ) ) {
	/**
	 * Resolve a page, term, route, or post into shopping context.
	 *
	 * @param int|string $context_id   WP post ID, term ID, or route slug.
	 * @param string     $context_type page, term, route, learn, gateway.
	 * @return array Context object.
	 */
	function resq_resolve_product_context( int|string $context_id, string $context_type ): array {
		$empty = array(
			'context_type'      => $context_type,
			'context_id'        => $context_id,
			'audience'          => '',
			'concerns'          => array(),
			'featured_products' => array(),
			'canonical_targets' => array(),
			'compliance_zone'   => 'standard',
			'filters'           => array(),
		);

		if ( 'gateway' === $context_type || 'route' === $context_type ) {
			$gateway = sanitize_key( (string) $context_id );
			$allowed = array( 'human', 'pet', 'bundles', 'cbd', 'learn' );

			if ( ! in_array( $gateway, $allowed, true ) ) {
				return $empty;
			}

			$audience_map = array(
				'human'   => 'human',
				'pet'     => 'pet',
				'cbd'     => '',
				'bundles' => '',
				'learn'   => '',
			);

			$zone_map = array(
				'cbd' => 'cbd',
			);

			$featured = resq_get_gateway_featured_products( $gateway );
			$targets  = array();

			foreach ( $featured as $product_id ) {
				$canonical = resq_get_canonical_product_id( $product_id, 'product' );
				if ( $canonical ) {
					$targets[] = $canonical;
				}
			}

			$filters = array(
				'audience' => array(),
				'concern'  => array(),
			);

			if ( ! empty( $audience_map[ $gateway ] ) ) {
				$filters['audience'] = array( $audience_map[ $gateway ] );
			}

			return array(
				'context_type'      => 'gateway',
				'context_id'        => $gateway,
				'audience'          => $audience_map[ $gateway ] ?? '',
				'concerns'          => array(),
				'featured_products' => $featured,
				'canonical_targets' => array_values( array_unique( $targets ) ),
				'compliance_zone'   => $zone_map[ $gateway ] ?? 'standard',
				'filters'           => $filters,
			);
		}

		if ( 'term' === $context_type && is_numeric( $context_id ) ) {
			$term = get_term( (int) $context_id );
			if ( ! $term || is_wp_error( $term ) ) {
				return $empty;
			}

			$targets = get_term_meta( (int) $term->term_id, '_resq_canonical_targets', true );
			if ( ! is_array( $targets ) ) {
				$targets = array();
			}

			$concerns = array();
			if ( 'resq_concern' === $term->taxonomy ) {
				$concerns = array( $term->slug );
			}

			$audience = '';
			if ( 'resq_audience' === $term->taxonomy ) {
				$audience = $term->slug;
			}

			$zone = 'standard';
			if ( 'resq_compliance_zone' === $term->taxonomy ) {
				$zone = $term->slug;
			}

			return array(
				'context_type'      => 'term',
				'context_id'        => (int) $term->term_id,
				'audience'          => $audience,
				'concerns'          => $concerns,
				'featured_products' => array(),
				'canonical_targets' => array_map( 'intval', $targets ),
				'compliance_zone'   => $zone,
				'filters'           => array(
					'audience' => $audience ? array( $audience ) : array(),
					'concern'  => $concerns,
				),
			);
		}

		if ( in_array( $context_type, array( 'page', 'learn' ), true ) && is_numeric( $context_id ) ) {
			$post_id = (int) $context_id;
			$targets = get_post_meta( $post_id, '_resq_canonical_targets', true );
			if ( ! is_array( $targets ) ) {
				$targets = array();
			}

			return array_merge(
				$empty,
				array(
					'context_id'        => $post_id,
					'canonical_targets' => array_map( 'intval', $targets ),
				)
			);
		}

		return $empty;
	}
}

/*
 * ──────────────────────────────────────────────────
 *  Compliance
 * ──────────────────────────────────────────────────
 */

if ( ! function_exists( 'resq_get_compliance_zone' ) ) {
	/**
	 * Return the compliance zone slug for structural isolation.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return string standard, cbd, baby, or pet-health.
	 */
	function resq_get_compliance_zone( int $product_id ): string {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 ) {
			return 'standard';
		}

		$cache_key = 'compliance_zone_' . $product_id;
		$cached    = ResQ_Core_Cache::get( $cache_key );
		if ( false !== $cached && is_string( $cached ) ) {
			return $cached;
		}

		$zone_meta = resq_get_product_meta( $product_id, '_resq_compliance_zone', '' );
		if ( is_string( $zone_meta ) && '' !== $zone_meta ) {
			$zone = ResQ_Core_Registrations_Post_Meta::sanitize_compliance_zone( $zone_meta );
			ResQ_Core_Cache::set( $cache_key, $zone );
			return $zone;
		}

		if ( taxonomy_exists( 'resq_compliance_zone' ) ) {
			$terms = wp_get_post_terms( $product_id, 'resq_compliance_zone', array( 'fields' => 'slugs' ) );
			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$zone = ResQ_Core_Registrations_Post_Meta::sanitize_compliance_zone( $terms[0] );
				ResQ_Core_Cache::set( $cache_key, $zone );
				return $zone;
			}
		}

		ResQ_Core_Cache::set( $cache_key, 'standard' );
		return 'standard';
	}
}

if ( ! function_exists( 'resq_is_cbd_product' ) ) {
	/**
	 * Whether a product is in the CBD-regulated lane.
	 *
	 * @param int $product_id WooCommerce product or variation ID.
	 * @return bool
	 */
	function resq_is_cbd_product( int $product_id ): bool {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 ) {
			return false;
		}

		if ( 'cbd' === resq_get_compliance_zone( $product_id ) ) {
			return true;
		}

		$flags = resq_get_compliance_flags( $product_id );
		return in_array( 'cbd', $flags, true );
	}
}

if ( ! function_exists( 'resq_requires_compliance_notice' ) ) {
	/**
	 * Whether a notice slot must render for this product and context.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $context    pdp, category, cart, checkout, card.
	 * @return bool
	 */
	function resq_requires_compliance_notice( int $product_id, string $context = 'pdp' ): bool {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 ) {
			return false;
		}

		$zone  = resq_get_compliance_zone( $product_id );
		$flags = resq_get_compliance_flags( $product_id );

		if ( 'standard' !== $zone ) {
			return true;
		}

		$notice_flags = array( 'cbd', 'medical-adjacent', 'pet-health', 'baby' );
		foreach ( $notice_flags as $flag ) {
			if ( in_array( $flag, $flags, true ) ) {
				return true;
			}
		}

		$notice_text = resq_core_get_option( 'resq_core_compliance.notice_text', array() );
		if ( is_array( $notice_text ) && ! empty( $notice_text[ $zone ] ) ) {
			return true;
		}

		unset( $context ); // Reserved for future context-specific rules.

		return false;
	}
}

if ( ! function_exists( 'resq_can_cross_sell_products' ) ) {
	/**
	 * Whether target may be cross-sold from source product context.
	 *
	 * @param int $source_product_id Source product ID.
	 * @param int $target_product_id Target product ID.
	 * @return bool
	 */
	function resq_can_cross_sell_products( int $source_product_id, int $target_product_id ): bool {
		if ( $source_product_id <= 0 || $target_product_id <= 0 ) {
			return false;
		}

		if ( $source_product_id === $target_product_id ) {
			return false;
		}

		if ( ! resq_is_woocommerce_available() ) {
			return false;
		}

		$source_id = resq_get_canonical_product_id( $source_product_id, 'product' ) ?? resq_resolve_product_id( $source_product_id );
		$target_id = resq_get_canonical_product_id( $target_product_id, 'product' ) ?? resq_resolve_product_id( $target_product_id );

		if ( $source_id <= 0 || $target_id <= 0 ) {
			return false;
		}

		if ( $source_id === $target_id ) {
			return false;
		}

		$cbd_isolation = resq_core_feature_enabled( 'cbd_isolation' )
			&& (bool) resq_core_get_option( 'resq_core_compliance.cbd_isolation_enabled', true );

		if ( $cbd_isolation ) {
			$source_is_cbd = resq_is_cbd_product( $source_id );
			$target_is_cbd = resq_is_cbd_product( $target_id );

			if ( $source_is_cbd !== $target_is_cbd ) {
				return false;
			}
		}

		if ( taxonomy_exists( 'resq_audience' ) ) {
			$source_audiences = wp_get_post_terms( $source_id, 'resq_audience', array( 'fields' => 'slugs' ) );
			$target_audiences = wp_get_post_terms( $target_id, 'resq_audience', array( 'fields' => 'slugs' ) );

			if ( ! is_wp_error( $source_audiences ) && ! is_wp_error( $target_audiences ) ) {
				if ( ! empty( $source_audiences ) && ! empty( $target_audiences ) ) {
					$shared = array_intersect( $source_audiences, $target_audiences );
					if ( empty( $shared ) ) {
						return false;
					}
				}
			}
		}

		$target_flags = resq_get_compliance_flags( $target_id );
		if ( in_array( 'baby', $target_flags, true ) ) {
			$source_flags = resq_get_compliance_flags( $source_id );
			if ( ! in_array( 'baby', $source_flags, true ) ) {
				return false;
			}
		}

		return (bool) apply_filters( 'resq_can_cross_sell_products', true, $source_id, $target_id );
	}
}

if ( ! function_exists( 'resq_get_product_cbd_disclosure' ) ) {
	/**
	 * Return CBD disclosure data (COA link + THC value) for a product.
	 *
	 * Phase 10 A1. Returns an empty array unless the `coa_disclosure` feature is
	 * enabled, the product is in the CBD lane, and at least one field is set —
	 * so the PDP slot stays silent until the owner populates real COA/THC data.
	 *
	 * @param int $product_id WooCommerce product or variation ID.
	 * @return array{coa_url: string, thc_disclosure: string} Empty when nothing to show.
	 */
	function resq_get_product_cbd_disclosure( int $product_id ): array {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 ) {
			return array();
		}

		if ( ! resq_core_feature_enabled( 'coa_disclosure' ) ) {
			return array();
		}

		if ( ! resq_is_cbd_product( $product_id ) ) {
			return array();
		}

		$coa_url = esc_url_raw( (string) resq_get_product_meta( $product_id, '_resq_coa_url', '' ) );
		$thc     = sanitize_text_field( (string) resq_get_product_meta( $product_id, '_resq_thc_disclosure', '' ) );

		if ( '' === $coa_url && '' === $thc ) {
			if ( resq_core_is_local_sandbox() ) {
				return resq_core_dev_cbd_disclosure();
			}

			return array();
		}

		return array(
			'coa_url'        => $coa_url,
			'thc_disclosure' => $thc,
			'dev_preview'    => false,
		);
	}
}

/*
 * ──────────────────────────────────────────────────
 *  Routine Commerce
 * ──────────────────────────────────────────────────
 */

if ( ! function_exists( 'resq_get_product_routine_ladder' ) ) {
	/**
	 * Return the primary routine ladder payload for PDP display.
	 *
	 * @param int $product_id Current PDP product ID.
	 * @return array Ladder object or empty array.
	 */
	function resq_get_product_routine_ladder( int $product_id ): array {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 ) {
			return array();
		}

		if ( ! resq_core_feature_enabled( 'routine_ladder' ) ) {
			return array();
		}

		$enabled = (bool) resq_core_get_option( 'resq_core_merchandising.routine_ladder_enabled', true );
		if ( ! $enabled ) {
			return array();
		}

		$routines = resq_get_product_routines( $product_id );
		if ( empty( $routines ) ) {
			return array();
		}

		$primary = null;
		foreach ( $routines as $routine ) {
			if ( ! empty( $routine['is_primary'] ) ) {
				$primary = $routine;
				break;
			}
		}

		if ( ! $primary ) {
			$primary = $routines[0];
		}

		$steps = resq_get_routine_steps( (int) $primary['routine_id'], $product_id );
		if ( empty( $steps ) ) {
			return array();
		}

		$bundle_target  = (int) ( $primary['bundle_target'] ?? 0 );
		$bundle_savings = '';
		$is_complete_bundle = $bundle_target > 0 && $product_id === $bundle_target;

		if ( $is_complete_bundle ) {
			$bundle_target = 0;
		} elseif ( $bundle_target > 0 && resq_is_woocommerce_available() ) {
			$bundle_product = wc_get_product( $bundle_target );
			if ( $bundle_product ) {
				$breakdown = resq_get_bundle_savings_breakdown( $bundle_target );
				if ( $breakdown['composition_savings'] > 0 ) {
					$bundle_savings = wp_strip_all_tags( wc_price( $breakdown['composition_savings'] ) );
				} else {
					$steps_total = 0.0;
					foreach ( $steps as $step ) {
						$step_id = (int) ( $step['product_id'] ?? 0 );
						if ( $step_id <= 0 ) {
							continue;
						}
						$summary = resq_get_wc_product_summary( $step_id );
						if ( $summary ) {
							$steps_total += (float) $summary['price'];
						}
					}

					$bundle_price = (float) $bundle_product->get_price();
					if ( $steps_total > $bundle_price && $bundle_price > 0 ) {
						$bundle_savings = wp_strip_all_tags( wc_price( $steps_total - $bundle_price ) );
					}
				}
			}
		}

		return array(
			'routine_id'              => (int) $primary['routine_id'],
			'title'                   => (string) $primary['title'],
			'description'             => '',
			'steps'                   => $steps,
			'bundle_target'           => $bundle_target,
			'bundle_label'            => __( 'Upgrade to Full Routine Kit', 'resq-core' ),
			'bundle_savings'          => $bundle_savings,
			'is_complete_bundle'      => $is_complete_bundle,
			'bundle_complete_message' => __( 'You\'re viewing the complete routine', 'resq-core' ),
		);
	}
}

if ( ! function_exists( 'resq_get_recommended_routine_addons' ) ) {
	/**
	 * Return next-step or complete-kit suggestions for cart drawer.
	 *
	 * @param int $product_id Product just added or in cart.
	 * @return array[] Suggestion objects.
	 */
	function resq_get_recommended_routine_addons( int $product_id ): array {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 ) {
			return array();
		}

		if ( ! resq_core_feature_enabled( 'cart_drawer_suggestions' ) ) {
			return array();
		}

		$enabled = (bool) resq_core_get_option( 'resq_core_merchandising.cart_drawer_suggestions_enabled', true );
		if ( ! $enabled ) {
			return array();
		}

		$ladder = resq_get_product_routine_ladder( $product_id );
		if ( empty( $ladder ) ) {
			return array();
		}

		$suggestions = array();
		$found_current = false;

		foreach ( $ladder['steps'] as $step ) {
			if ( ! empty( $step['is_current'] ) ) {
				$found_current = true;
				continue;
			}

			if ( $found_current && ! empty( $step['product_id'] ) ) {
				$step_id = (int) $step['product_id'];
				if ( resq_can_cross_sell_products( $product_id, $step_id ) ) {
					$suggestions[] = array(
						'type'         => 'routine_step',
						'product_id'   => $step_id,
						'canonical_id' => (int) ( $step['canonical_id'] ?? $step_id ),
						'title'        => (string) ( $step['title'] ?: $step['product_title'] ),
						'reason'       => 'complete_routine',
					);
				}
				break;
			}
		}

		$bundle_target = (int) ( $ladder['bundle_target'] ?? 0 );
		if ( $bundle_target > 0 && resq_can_cross_sell_products( $product_id, $bundle_target ) ) {
			$suggestions[] = array(
				'type'         => 'bundle',
				'product_id'   => $bundle_target,
				'canonical_id' => resq_get_canonical_product_id( $bundle_target, 'product' ) ?? $bundle_target,
				'title'        => get_the_title( $bundle_target ),
				'reason'       => 'upgrade_to_kit',
			);
		}

		return $suggestions;
	}
}

if ( ! function_exists( 'resq_get_bundle_products' ) ) {
	/**
	 * Return products and quantities in a bundle/kit.
	 *
	 * @param int $bundle_id WooCommerce bundle product ID.
	 * @return array[] Included product objects.
	 */
	function resq_get_bundle_products( int $bundle_id ): array {
		$bundle_id = resq_resolve_product_id( $bundle_id );
		if ( $bundle_id <= 0 ) {
			return array();
		}

		$composition = resq_get_product_meta( $bundle_id, '_resq_bundle_product_ids', array() );
		if ( ! is_array( $composition ) || empty( $composition ) ) {
			return array();
		}

		$result = array();

		foreach ( $composition as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$item_id = (int) ( $item['product_id'] ?? 0 );
			$qty     = max( 1, (int) ( $item['qty'] ?? 1 ) );

			if ( $item_id <= 0 ) {
				continue;
			}

			$summary = resq_get_wc_product_summary( $item_id );
			if ( ! $summary ) {
				continue;
			}

			$result[] = array(
				'product_id'   => $item_id,
				'canonical_id' => resq_get_canonical_product_id( $item_id, 'product' ) ?? $item_id,
				'qty'          => $qty,
				'title'        => $summary['title'],
				'price'        => $summary['price'],
				'in_stock'     => $summary['in_stock'],
			);
		}

		return $result;
	}
}

if ( ! function_exists( 'resq_get_frequently_bought_together' ) ) {
	/**
	 * Return FBT product IDs for PDP and cart surfaces.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return int[] Canonical product IDs.
	 */
	function resq_get_frequently_bought_together( int $product_id ): array {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 ) {
			return array();
		}

		$cache_key = 'fbt_' . $product_id;
		$cached    = ResQ_Core_Cache::get( $cache_key );
		if ( false !== $cached && is_array( $cached ) ) {
			return $cached;
		}

		$manual = resq_get_product_meta( $product_id, '_resq_fbt_product_ids', array() );
		$ids    = is_array( $manual ) ? array_map( 'intval', $manual ) : array();

		if ( empty( $ids ) && resq_is_woocommerce_available() ) {
			$product = wc_get_product( $product_id );
			if ( $product ) {
				$ids = array_map( 'intval', (array) $product->get_cross_sell_ids() );
			}
		}

		$result = array();
		foreach ( $ids as $target_id ) {
			if ( $target_id <= 0 ) {
				continue;
			}

			if ( ! resq_can_cross_sell_products( $product_id, $target_id ) ) {
				continue;
			}

			$canonical = resq_get_canonical_product_id( $target_id, 'product' ) ?? $target_id;
			if ( $canonical > 0 ) {
				$result[] = $canonical;
			}
		}

		$result = array_values( array_unique( $result ) );
		ResQ_Core_Cache::set( $cache_key, $result );

		return $result;
	}
}

/*
 * ──────────────────────────────────────────────────
 *  Presentation Helpers
 * ──────────────────────────────────────────────────
 */

if ( ! function_exists( 'resq_get_product_badges' ) ) {
	/**
	 * Return badge objects for product card and PDP.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return array[] Badge objects sorted by priority.
	 */
	function resq_get_product_badges( int $product_id ): array {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 ) {
			return array();
		}

		$cache_key = 'product_badges_' . $product_id;
		$cached    = ResQ_Core_Cache::get( $cache_key );
		if ( false !== $cached && is_array( $cached ) ) {
			return $cached;
		}

		$badges = array();

		$label = (string) resq_get_product_meta( $product_id, '_resq_badge_label', '' );
		$type  = (string) resq_get_product_meta( $product_id, '_resq_badge_type', '' );

		if ( '' !== $label ) {
			$badges[] = array(
				'label'    => $label,
				'type'     => $type ?: 'custom',
				'priority' => 10,
			);
		}

		if ( empty( $badges ) ) {
			$config = resq_core_get_option( 'resq_core_merchandising.default_badge_config', array() );
			if ( is_array( $config ) && resq_is_woocommerce_available() ) {
				$product = wc_get_product( $product_id );
				foreach ( $config as $rule ) {
					if ( ! is_array( $rule ) ) {
						continue;
					}

					$condition = (string) ( $rule['condition'] ?? '' );
					$match     = false;

					if ( 'on_sale' === $condition && $product && $product->is_on_sale() ) {
						$match = true;
					}

					if ( 'is_bundle' === $condition ) {
						$bundle_items = resq_get_product_meta( $product_id, '_resq_bundle_product_ids', array() );
						$match        = is_array( $bundle_items ) && ! empty( $bundle_items );
					}

					if ( $match ) {
						$badges[] = array(
							'label'    => (string) ( $rule['label'] ?? '' ),
							'type'     => (string) ( $rule['type'] ?? 'default' ),
							'priority' => (int) ( $rule['priority'] ?? 20 ),
						);
					}
				}
			}
		}

		usort(
			$badges,
			static function ( array $a, array $b ): int {
				return (int) ( $a['priority'] ?? 99 ) <=> (int) ( $b['priority'] ?? 99 );
			}
		);

		ResQ_Core_Cache::set( $cache_key, $badges );

		return $badges;
	}
}

if ( ! function_exists( 'resq_get_product_card_data' ) ) {
	/**
	 * Aggregate scannable product card payload for PLP/gateway shelves.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return array Card data object or empty array.
	 */
	function resq_get_product_card_data( int $product_id ): array {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 || ! resq_is_woocommerce_available() ) {
			return array();
		}

		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return array();
		}

		$image_id  = (int) $product->get_image_id();
		$image_display = function_exists( 'resq_get_product_image_display' )
			? resq_get_product_image_display( $product_id, 'woocommerce_thumbnail' )
			: array();
		$audiences = array_column( resq_get_product_audiences( $product_id ), 'slug' );
		$concerns  = array_column( resq_get_product_concerns( $product_id ), 'slug' );
		$tags      = resq_get_product_meta( $product_id, '_resq_short_benefit_tags', array() );

		return array(
			'product_id'      => $product_id,
			'canonical_id'    => resq_get_canonical_product_id( $product_id, 'product' ) ?? $product_id,
			'title'           => $product->get_name(),
			'subtitle'        => (string) resq_get_product_meta( $product_id, '_resq_product_card_subtitle', '' ),
			'url'             => get_permalink( $product_id ),
			'image_id'        => $image_id,
			'image_url'       => ( 'featured' === ( $image_display['mode'] ?? '' ) ) ? (string) ( $image_display['image_url'] ?? '' ) : '',
			'image_display'   => $image_display,
			'price_html'      => $product->get_price_html(),
			'badges'          => resq_get_product_badges( $product_id ),
			'benefit_tags'    => is_array( $tags ) ? $tags : array(),
			'audiences'       => $audiences,
			'concerns'        => $concerns,
			'compliance_zone' => resq_get_compliance_zone( $product_id ),
			'requires_notice' => resq_requires_compliance_notice( $product_id, 'card' ),
			'in_stock'        => $product->is_in_stock(),
		);
	}
}

if ( ! function_exists( 'resq_get_gateway_featured_products' ) ) {
	/**
	 * Return product IDs featured on a gateway page shelf.
	 *
	 * @param string $gateway Slug: human, pet, bundles, cbd, learn.
	 * @return int[] Canonical product IDs, ordered.
	 */
	function resq_get_gateway_featured_products( string $gateway ): array {
		$gateway = sanitize_key( $gateway );
		$allowed = array( 'human', 'pet', 'bundles', 'cbd', 'learn' );

		if ( ! in_array( $gateway, $allowed, true ) ) {
			return array();
		}

		if ( ! resq_core_feature_enabled( 'gateway_featured' ) ) {
			return array();
		}

		$cache_key = 'gateway_featured_' . $gateway;
		$cached    = ResQ_Core_Cache::get( $cache_key );
		if ( false !== $cached && is_array( $cached ) ) {
			return $cached;
		}

		$query = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 50,
				'fields'         => 'ids',
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => '_resq_gateway_featured',
						'value'   => '"' . $gateway . '"',
						'compare' => 'LIKE',
					),
				),
			)
		);

		$result = array();

		foreach ( $query->posts as $post_id ) {
			$post_id   = (int) $post_id;
			$canonical = resq_get_canonical_product_id( $post_id, 'product' ) ?? $post_id;

			if ( 'cbd' === $gateway && ! resq_is_cbd_product( $post_id ) ) {
				continue;
			}

			if ( 'cbd' !== $gateway && resq_is_cbd_product( $post_id ) ) {
				continue;
			}

			if ( in_array( $gateway, array( 'human', 'pet' ), true ) ) {
				$audiences = array_column( resq_get_product_audiences( $post_id ), 'slug' );
				if ( ! empty( $audiences ) && ! in_array( $gateway, $audiences, true ) ) {
					continue;
				}
			}

			$result[] = $canonical;
		}

		$result = array_values( array_unique( $result ) );
		ResQ_Core_Cache::set( $cache_key, $result );

		return $result;
	}
}

if ( ! function_exists( 'resq_get_learn_links_for_product' ) ) {
	/**
	 * Return Learn guide links related to a product.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return array[] Link objects.
	 */
	function resq_get_learn_links_for_product( int $product_id ): array {
		$product_id = resq_resolve_product_id( $product_id );
		if ( $product_id <= 0 ) {
			return array();
		}

		if ( ! resq_core_feature_enabled( 'learn_bridges' ) ) {
			return array();
		}

		$links = resq_get_product_meta( $product_id, '_resq_learn_links', array() );
		$result = array();

		if ( is_array( $links ) ) {
			foreach ( $links as $link ) {
				if ( ! is_array( $link ) ) {
					continue;
				}

				$post_id = (int) ( $link['post_id'] ?? 0 );
				if ( $post_id <= 0 || 'publish' !== get_post_status( $post_id ) ) {
					continue;
				}

				$result[] = array(
					'post_id' => $post_id,
					'title'   => get_the_title( $post_id ),
					'url'     => get_permalink( $post_id ),
					'label'   => (string) ( $link['label'] ?? get_the_title( $post_id ) ),
				);
			}
		}

		if ( ! empty( $result ) ) {
			return $result;
		}

		// Reverse lookup: Learn posts with _resq_canonical_targets containing this product.
		$reverse = get_posts(
			array(
				'post_type'      => array( 'post', 'page' ),
				'post_status'    => 'publish',
				'posts_per_page' => 10,
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => '_resq_canonical_targets',
						'value'   => '"' . $product_id . '"',
						'compare' => 'LIKE',
					),
				),
			)
		);

		foreach ( $reverse as $post ) {
			$result[] = array(
				'post_id' => (int) $post->ID,
				'title'   => get_the_title( $post ),
				'url'     => get_permalink( $post ),
				'label'   => get_the_title( $post ),
			);
		}

		return $result;
	}
}

/*
 * ──────────────────────────────────────────────────
 *  Target Problem Lanes & Category Landings
 * ──────────────────────────────────────────────────
 */

if ( ! function_exists( 'resq_resolve_sku_to_product_id' ) ) {
	/**
	 * Resolve a catalog SKU to a WooCommerce product ID.
	 *
	 * @param string $sku Product SKU.
	 * @return int|null Product ID or null.
	 */
	function resq_resolve_sku_to_product_id( string $sku ): ?int {
		$sku = trim( $sku );
		if ( '' === $sku || ! function_exists( 'wc_get_product_id_by_sku' ) ) {
			return null;
		}

		$product_id = (int) wc_get_product_id_by_sku( $sku );
		return $product_id > 0 ? $product_id : null;
	}
}

if ( ! function_exists( 'resq_build_lane_key' ) ) {
	/**
	 * Build a registry lookup key from route segments.
	 *
	 * @param string $audience Audience slug.
	 * @param string $category Category slug.
	 * @param string $problem  Optional problem slug.
	 * @return string
	 */
	function resq_build_lane_key( string $audience, string $category, string $problem = '' ): string {
		$audience = sanitize_key( $audience );
		$category = sanitize_key( $category );
		$problem  = sanitize_key( $problem );

		$key = $audience . '-' . $category;
		if ( '' !== $problem ) {
			$key .= '-' . $problem;
		}

		return $key;
	}
}

if ( ! function_exists( 'resq_resolve_lane_url' ) ) {
	/**
	 * Build the public URL for a lane route.
	 *
	 * @param string $audience Audience slug.
	 * @param string $category Category slug.
	 * @param string $problem  Optional problem slug.
	 * @return string
	 */
	function resq_resolve_lane_url( string $audience, string $category, string $problem = '' ): string {
		$audience = sanitize_key( $audience );
		$category = sanitize_key( $category );
		$problem  = sanitize_key( $problem );

		$path = 'shop/' . $audience . '/' . $category;
		if ( '' !== $problem ) {
			$path .= '/' . $problem;
		}

		return trailingslashit( home_url( '/' . $path ) );
	}
}

if ( ! function_exists( 'resq_get_lane_from_request' ) ) {
	/**
	 * Resolve the current lane from query vars.
	 *
	 * @return array<string, mixed>|null Enriched lane or null.
	 */
	function resq_get_lane_from_request(): ?array {
		if ( ! class_exists( 'ResQ_Core_Lane_Routing' ) || ! ResQ_Core_Lane_Routing::is_lane_request() ) {
			return null;
		}

		return resq_get_lane(
			array(
				'audience' => (string) get_query_var( 'resq_lane_audience' ),
				'category' => (string) get_query_var( 'resq_lane_category' ),
				'problem'  => (string) get_query_var( 'resq_lane_problem' ),
			)
		);
	}
}

if ( ! function_exists( 'resq_get_lane' ) ) {
	/**
	 * Resolve a lane route to enriched registry data.
	 *
	 * @param array{audience?:string,category?:string,problem?:string} $route Route segments.
	 * @return array<string, mixed>|null
	 */
	function resq_get_lane( array $route ): ?array {
		if ( ! function_exists( 'resq_get_lane_registry' ) ) {
			return null;
		}

		$audience = sanitize_key( (string) ( $route['audience'] ?? '' ) );
		$category = sanitize_key( (string) ( $route['category'] ?? '' ) );
		$problem  = sanitize_key( (string) ( $route['problem'] ?? '' ) );

		if ( '' === $audience || '' === $category ) {
			return null;
		}

		$gateway_only = array( 'human', 'pet', 'bundles', 'cbd', 'learn' );
		if ( in_array( $category, $gateway_only, true ) && '' === $problem ) {
			return null;
		}

		$key      = resq_build_lane_key( $audience, $category, $problem );
		$registry = resq_get_lane_registry();

		if ( ! isset( $registry[ $key ] ) || ! is_array( $registry[ $key ] ) ) {
			return null;
		}

		$lane = $registry[ $key ];

		$canonical_id = null;
		$sku          = (string) ( $lane['canonical_sku'] ?? '' );

		if ( '' !== $sku ) {
			$product_id = resq_resolve_sku_to_product_id( $sku );
			if ( $product_id ) {
				$canonical_id = resq_get_canonical_product_id( $product_id, 'product' ) ?? $product_id;
			}
		}

		$product_ids = resq_get_lane_product_ids( $lane, 'problem' === ( $lane['lane_type'] ?? '' ) ? $canonical_id : null );

		$lane['registry_key'] = $key;
		$lane['url']          = resq_resolve_lane_url( $audience, $category, $problem );
		$lane['canonical_id'] = $canonical_id;
		$lane['product_ids']  = $product_ids;
		$lane['is_cbd']       = ( 'cbd' === ( $lane['compliance_zone'] ?? 'standard' ) );
		$lane['is_problem']   = ( 'problem' === ( $lane['lane_type'] ?? '' ) );
		$lane['is_draft']     = ( 'draft' === ( $lane['status'] ?? 'draft' ) );
		$lane['context']      = array(
			'context_type'      => 'lane',
			'context_id'        => $key,
			'audience'          => $audience,
			'concerns'          => '' !== $problem ? array( $problem ) : array(),
			'featured_products' => $product_ids,
			'canonical_targets' => $canonical_id ? array( $canonical_id ) : array(),
			'compliance_zone'   => (string) ( $lane['compliance_zone'] ?? 'standard' ),
			'filters'           => array(
				'audience' => array( $audience ),
				'concern'  => '' !== $problem ? array( $problem ) : array(),
			),
			'lane_url'          => resq_resolve_lane_url( $audience, $category, $problem ),
		);

		return apply_filters( 'resq_lane_resolved', $lane, $route );
	}
}

if ( ! function_exists( 'resq_get_lane_product_ids' ) ) {
	/**
	 * Query product IDs for a lane shelf, respecting CBD isolation.
	 *
	 * @param array<string, mixed> $lane         Lane registry entry.
	 * @param int|null               $canonical_id Optional canonical override (problem lanes).
	 * @return int[]
	 */
	function resq_get_lane_product_ids( array $lane, ?int $canonical_id = null ): array {
		if ( null !== $canonical_id && $canonical_id > 0 ) {
			return array( $canonical_id );
		}

		$preset = $lane['filter_preset'] ?? array();
		if ( ! is_array( $preset ) ) {
			return array();
		}

		$cache_key = 'lane_products_' . md5( wp_json_encode( $preset ) . (string) ( $lane['cbd_excluded'] ?? true ) );
		$cached    = ResQ_Core_Cache::get( $cache_key );
		if ( false !== $cached && is_array( $cached ) ) {
			return $cached;
		}

		$tax_query = array( 'relation' => 'AND' );

		if ( ! empty( $preset['audience'] ) && taxonomy_exists( 'resq_audience' ) ) {
			$tax_query[] = array(
				'taxonomy' => 'resq_audience',
				'field'    => 'slug',
				'terms'    => array( sanitize_key( (string) $preset['audience'] ) ),
			);
		}

		if ( ! empty( $preset['product_cat'] ) && is_array( $preset['product_cat'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => array_map( 'sanitize_key', $preset['product_cat'] ),
			);
		}

		if ( ! empty( $preset['compliance_zone'] ) && taxonomy_exists( 'resq_compliance_zone' ) ) {
			$tax_query[] = array(
				'taxonomy' => 'resq_compliance_zone',
				'field'    => 'slug',
				'terms'    => array( sanitize_key( (string) $preset['compliance_zone'] ) ),
			);
		}

		if ( count( $tax_query ) <= 1 ) {
			return array();
		}

		$query = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 50,
				'fields'         => 'ids',
				'tax_query'      => $tax_query, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			)
		);

		$result = array();

		foreach ( $query->posts as $post_id ) {
			$post_id = (int) $post_id;

			if ( ! empty( $lane['cbd_excluded'] ) && resq_is_cbd_product( $post_id ) ) {
				continue;
			}

			if ( ! empty( $preset['compliance_zone'] ) && 'cbd' !== $preset['compliance_zone'] && resq_is_cbd_product( $post_id ) ) {
				continue;
			}

			$canonical = resq_get_canonical_product_id( $post_id, 'product' ) ?? $post_id;
			$result[]  = $canonical;
		}

		$result = array_values( array_unique( $result ) );
		ResQ_Core_Cache::set( $cache_key, $result );

		return $result;
	}
}

if ( ! function_exists( 'resq_get_lane_problem_links' ) ) {
	/**
	 * Return navigable problem-lane links for a category landing.
	 *
	 * @param array<string, mixed> $lane Category lane data.
	 * @return array<int, array{slug:string,label:string,url:string,status:string}>
	 */
	function resq_get_lane_problem_links( array $lane ): array {
		$slugs = $lane['problem_lanes'] ?? array();
		if ( ! is_array( $slugs ) || empty( $slugs ) ) {
			return array();
		}

		$audience = sanitize_key( (string) ( $lane['audience'] ?? '' ) );
		$category = sanitize_key( (string) ( $lane['category'] ?? '' ) );
		$links    = array();

		foreach ( $slugs as $problem_slug ) {
			$problem_slug = sanitize_key( (string) $problem_slug );
			if ( '' === $problem_slug ) {
				continue;
			}

			$problem_lane = resq_get_lane(
				array(
					'audience' => $audience,
					'category' => $category,
					'problem'  => $problem_slug,
				)
			);

			if ( null === $problem_lane ) {
				continue;
			}

			$links[] = array(
				'slug'   => $problem_slug,
				'label'  => resq_get_lane_problem_label( $problem_slug ),
				'url'    => (string) ( $problem_lane['url'] ?? resq_resolve_lane_url( $audience, $category, $problem_slug ) ),
				'status' => (string) ( $problem_lane['status'] ?? 'draft' ),
			);
		}

		return $links;
	}
}

if ( ! function_exists( 'resq_get_lane_problem_label' ) ) {
	/**
	 * Human-readable label for a problem slug.
	 *
	 * @param string $slug Problem slug.
	 * @return string
	 */
	function resq_get_lane_problem_label( string $slug ): string {
		$labels = array(
			'razor-burn'                  => __( 'Post-shave comfort', 'resq-core' ),
			'beard-and-odor-control'      => __( 'Beard & cleansing routine', 'resq-core' ),
			'makeup-removal-detox'        => __( 'Makeup removal & cleanse', 'resq-core' ),
			'exfoliation-texture'         => __( 'Texture & polishing', 'resq-core' ),
			'overnight-repair'            => __( 'Overnight appearance support', 'resq-core' ),
			'diaper-rash'                 => __( 'Diaper-area comfort', 'resq-core' ),
			'post-color-irritation'       => __( 'Post-color scalp comfort', 'resq-core' ),
			'split-end-defense'           => __( 'Split-end smoothing', 'resq-core' ),
			'hyperkeratosis'              => __( 'Rough nose & paw calluses', 'resq-core' ),
			'feline-dermatitis'           => __( 'Chin & fold comfort', 'resq-core' ),
			'seasonal-grass-itch'         => __( 'Seasonal coat wash', 'resq-core' ),
			'undercoat-friction-static'   => __( 'Mat & static glide', 'resq-core' ),
			'mud-fever-support'           => __( 'Mud & pasture debris cleanse', 'resq-core' ),
			'skin-chafing'                => __( 'Skin chafing comfort', 'resq-core' ),
			'cradle-cap-comfort'          => __( 'Cradle cap comfort', 'resq-core' ),
			'shaving-bumps'               => __( 'Shaving bump comfort', 'resq-core' ),
			'excess-sebum-control'        => __( 'Excess sebum balance', 'resq-core' ),
			'senior-dog-elbow-calluses'   => __( 'Senior elbow calluses', 'resq-core' ),
			'skin-fold-dermatitis'        => __( 'Skin fold comfort', 'resq-core' ),
			'diabetic-training-rewards'   => __( 'Training rewards routine', 'resq-core' ),
			'weight-management-rewards'   => __( 'Weight-management rewards', 'resq-core' ),
		);

		$slug = sanitize_key( $slug );

		return $labels[ $slug ] ?? ucwords( str_replace( '-', ' ', $slug ) );
	}
}
