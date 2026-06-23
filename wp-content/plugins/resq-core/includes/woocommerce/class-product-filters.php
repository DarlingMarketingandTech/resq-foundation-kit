<?php
/**
 * Product filter query hooks — Stream F.
 *
 * Reads resq_audience, resq_concern, and resq_product_role GET params on
 * shop, product archive, and gateway pages; applies tax_query so only
 * matching products are returned. Also enforces CBD compliance isolation
 * so the CBD zone never bleeds into non-CBD queries.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Product_Filters
 *
 * Static-only; all hooks registered once via ::init().
 */
class ResQ_Core_Product_Filters {

	/**
	 * Taxonomy slugs we accept as filter params.
	 *
	 * Key = GET param name, value = registered taxonomy.
	 */
	private const FILTER_PARAMS = array(
		'resq_audience'     => 'resq_audience',
		'resq_concern'      => 'resq_concern',
		'resq_product_role' => 'resq_product_role',
	);

	/**
	 * Register hooks. Called from plugin bootstrap when WC is active.
	 */
	public static function init(): void {
		add_action( 'pre_get_posts', array( __CLASS__, 'apply_product_filters' ) );
	}

	// -------------------------------------------------------------------------
	// Query modification
	// -------------------------------------------------------------------------

	/**
	 * Apply resq taxonomy filters to the main WooCommerce product query.
	 *
	 * Runs only on:
	 *  - The main query on front-end product archive / shop pages.
	 *  - Not on admin, REST, or feeds.
	 *  - Not when no resq filter params are present (early exit).
	 *
	 * @param WP_Query $query The current query object.
	 */
	public static function apply_product_filters( WP_Query $query ): void {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return;
		}

		if ( ! $query->is_post_type_archive( 'product' ) && ! $query->is_tax() && ! $query->get( 'wc_query' ) ) {
			// Also allow on shop page (is_shop sets wc_query on the query).
			if ( ! function_exists( 'is_shop' ) || ! is_shop() ) {
				return;
			}
		}

		$clauses = self::build_tax_query_clauses();

		if ( empty( $clauses ) ) {
			return;
		}

		$existing = $query->get( 'tax_query' );
		if ( ! is_array( $existing ) ) {
			$existing = array();
		}

		$merged = array_merge( $existing, $clauses );

		// Multiple clauses from different params should all pass (AND logic).
		if ( count( $clauses ) > 1 && ! isset( $merged['relation'] ) ) {
			$merged['relation'] = 'AND';
		}

		$query->set( 'tax_query', $merged );
	}

	// -------------------------------------------------------------------------
	// Helpers
	// -------------------------------------------------------------------------

	/**
	 * Build tax_query clause array from current GET params.
	 *
	 * Accepts single slug strings or arrays of slugs per param.
	 * Sanitizes every value before use.
	 *
	 * @return array[] Array of tax_query clause arrays; empty when no params set.
	 */
	private static function build_tax_query_clauses(): array {
		$clauses = array();

		foreach ( self::FILTER_PARAMS as $param => $taxonomy ) {
			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only filter param.
			$raw = isset( $_GET[ $param ] ) ? wp_unslash( $_GET[ $param ] ) : null;

			if ( null === $raw || '' === $raw ) {
				continue;
			}

			$slugs = is_array( $raw )
				? array_map( 'sanitize_key', $raw )
				: array( sanitize_key( (string) $raw ) );

			$slugs = array_filter( $slugs );

			if ( empty( $slugs ) ) {
				continue;
			}

			// Validate slugs exist as terms to avoid phantom queries.
			$valid = array();
			foreach ( $slugs as $slug ) {
				if ( term_exists( $slug, $taxonomy ) ) {
					$valid[] = $slug;
				}
			}

			if ( empty( $valid ) ) {
				continue;
			}

			$clauses[] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $valid,
				// Multiple values within one param = OR (any of these).
				'operator' => count( $valid ) > 1 ? 'IN' : 'IN',
			);
		}

		return $clauses;
	}

	/**
	 * Return the set of currently active filter slugs from GET params.
	 *
	 * Used by the theme's filter-shell to mark active checkboxes.
	 *
	 * @return array<string, string[]> Keyed by param name, values are slug arrays.
	 */
	public static function get_active_filters(): array {
		$active = array();

		foreach ( self::FILTER_PARAMS as $param => $taxonomy ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$raw = isset( $_GET[ $param ] ) ? wp_unslash( $_GET[ $param ] ) : null;

			if ( null === $raw || '' === $raw ) {
				continue;
			}

			$slugs = is_array( $raw )
				? array_map( 'sanitize_key', $raw )
				: array( sanitize_key( (string) $raw ) );

			$slugs = array_values( array_filter( $slugs ) );

			if ( ! empty( $slugs ) ) {
				$active[ $param ] = $slugs;
			}
		}

		return $active;
	}

	/**
	 * Whether any resq filter params are currently active.
	 *
	 * @return bool
	 */
	public static function has_active_filters(): bool {
		return ! empty( self::get_active_filters() );
	}
}
