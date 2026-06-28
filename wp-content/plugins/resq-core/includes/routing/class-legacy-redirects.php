<?php
/**
 * Legacy top-level gateway path redirects.
 *
 * Maps pre-/shop/ discovery URLs to canonical gateway children.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Legacy_Redirects
 */
class ResQ_Core_Legacy_Redirects {

	/**
	 * Bootstrap hooks.
	 */
	public static function init(): void {
		add_action( 'template_redirect', array( __CLASS__, 'maybe_redirect' ), 1 );
	}

	/**
	 * Legacy slug => canonical path (relative to site home).
	 *
	 * @return array<string, string>
	 */
	public static function get_map(): array {
		$map = array(
			'human'   => '/shop/human/',
			'pets'    => '/shop/pet/',
			'bundles' => '/shop/bundles/',
			'cbd'     => '/shop/cbd/',
		);

		/**
		 * Filter legacy gateway redirect map.
		 *
		 * @param array<string, string> $map Legacy slug to canonical path.
		 */
		return apply_filters( 'resq_legacy_gateway_redirects', $map );
	}

	/**
	 * Issue a 301 redirect for legacy top-level gateway slugs.
	 */
	public static function maybe_redirect(): void {
		if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}

		$request_path = self::get_request_path();
		if ( '' === $request_path ) {
			return;
		}

		$segments = explode( '/', $request_path );
		$slug     = sanitize_key( (string) ( $segments[0] ?? '' ) );

		if ( '' === $slug || count( $segments ) > 1 ) {
			return;
		}

		$map = self::get_map();
		if ( ! isset( $map[ $slug ] ) ) {
			return;
		}

		$target = home_url( $map[ $slug ] );
		if ( untrailingslashit( $target ) === untrailingslashit( home_url( '/' . $request_path . '/' ) ) ) {
			return;
		}

		wp_safe_redirect( $target, 301 );
		exit;
	}

	/**
	 * Request path relative to the WordPress home path (no leading/trailing slashes).
	 *
	 * @return string
	 */
	private static function get_request_path(): string {
		$uri_path = (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );
		$uri_path = trim( $uri_path, '/' );

		$home_path = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
		$home_path = trim( $home_path, '/' );

		if ( '' !== $home_path && str_starts_with( $uri_path, $home_path ) ) {
			$uri_path = trim( substr( $uri_path, strlen( $home_path ) ), '/' );
		}

		return $uri_path;
	}
}
