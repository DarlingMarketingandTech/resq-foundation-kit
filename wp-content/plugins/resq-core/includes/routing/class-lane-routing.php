<?php
/**
 * Lane routing — rewrite rules and template loader for
 * /shop/{audience}/{category}/{problem}/ discovery paths.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Lane_Routing
 */
class ResQ_Core_Lane_Routing {

	/**
	 * Bootstrap hooks.
	 */
	public static function init(): void {
		add_action( 'init', array( __CLASS__, 'register_rewrite_rules' ), 20 );
		add_filter( 'query_vars', array( __CLASS__, 'register_query_vars' ) );
		add_filter( 'template_include', array( __CLASS__, 'load_lane_template' ), 99 );
		add_action( 'template_redirect', array( __CLASS__, 'handle_unknown_lane' ) );
		add_filter( 'resq_route_canonical_map', array( __CLASS__, 'merge_route_canonical_map' ) );
	}

	/**
	 * Register rewrite rules (only claims paths with known category segments).
	 */
	public static function register_rewrite_rules(): void {
		$categories = self::get_registered_categories();

		if ( empty( $categories ) ) {
			return;
		}

		// Three-segment problem lanes: shop/{audience}/{category}/{problem}/
		add_rewrite_rule(
			'^shop/([^/]+)/([^/]+)/([^/]+)/?$',
			'index.php?resq_lane=1&resq_lane_audience=$matches[1]&resq_lane_category=$matches[2]&resq_lane_problem=$matches[3]',
			'top'
		);

		// Two-segment category landings: shop/{audience}/{category}/
		add_rewrite_rule(
			'^shop/([^/]+)/([^/]+)/?$',
			'index.php?resq_lane=1&resq_lane_audience=$matches[1]&resq_lane_category=$matches[2]',
			'top'
		);
	}

	/**
	 * Collect category slugs from the lane registry for rewrite guards.
	 *
	 * @return string[]
	 */
	private static function get_registered_categories(): array {
		if ( ! function_exists( 'resq_get_lane_registry' ) ) {
			return array();
		}

		$categories = array();

		foreach ( resq_get_lane_registry() as $lane ) {
			if ( ! is_array( $lane ) ) {
				continue;
			}

			$category = sanitize_key( (string) ( $lane['category'] ?? '' ) );
			if ( '' !== $category ) {
				$categories[] = $category;
			}
		}

		return array_values( array_unique( $categories ) );
	}

	/**
	 * Register custom query vars.
	 *
	 * @param string[] $vars Existing query vars.
	 * @return string[]
	 */
	public static function register_query_vars( array $vars ): array {
		$vars[] = 'resq_lane';
		$vars[] = 'resq_lane_audience';
		$vars[] = 'resq_lane_category';
		$vars[] = 'resq_lane_problem';

		return $vars;
	}

	/**
	 * Load the theme lane template when a lane query is active.
	 *
	 * @param string $template Current template path.
	 * @return string
	 */
	public static function load_lane_template( string $template ): string {
		if ( ! self::is_lane_request() ) {
			return $template;
		}

		$lane = resq_get_lane_from_request();
		if ( null === $lane ) {
			return $template;
		}

		$lane_template = locate_template( 'resq-lane.php' );
		if ( $lane_template ) {
			return $lane_template;
		}

		return $template;
	}

	/**
	 * 404 when lane query vars are present but registry has no match.
	 */
	public static function handle_unknown_lane(): void {
		if ( ! self::is_lane_request() ) {
			return;
		}

		$lane = resq_get_lane_from_request();
		if ( null === $lane ) {
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
			nocache_headers();
		}
	}

	/**
	 * Whether the current request is a lane route.
	 *
	 * @return bool
	 */
	public static function is_lane_request(): bool {
		return (bool) get_query_var( 'resq_lane' );
	}

	/**
	 * Flush rewrite rules (call on plugin activation).
	 */
	public static function flush_rewrite_rules(): void {
		self::register_rewrite_rules();
		flush_rewrite_rules();
	}

	/**
	 * Populate resq_route_canonical_map with problem-lane slugs.
	 *
	 * @param array<string, int|null> $map Existing map.
	 * @return array<string, int|null>
	 */
	public static function merge_route_canonical_map( array $map ): array {
		if ( ! function_exists( 'resq_get_lane_registry' ) || ! function_exists( 'resq_resolve_sku_to_product_id' ) ) {
			return $map;
		}

		foreach ( resq_get_lane_registry() as $lane ) {
			if ( ! is_array( $lane ) ) {
				continue;
			}

			$problem = sanitize_key( (string) ( $lane['problem'] ?? '' ) );
			$sku     = (string) ( $lane['canonical_sku'] ?? '' );

			if ( '' === $problem || '' === $sku ) {
				continue;
			}

			$product_id = resq_resolve_sku_to_product_id( $sku );
			if ( $product_id ) {
				$canonical = resq_get_canonical_product_id( $product_id, 'product' );
				$map[ $problem ] = $canonical ?? $product_id;
			}
		}

		return $map;
	}
}
