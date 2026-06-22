<?php
/**
 * Transient cache for relationship lookups.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Cache — plugin-owned transient wrapper.
 */
class ResQ_Core_Cache {

	/**
	 * Transient prefix.
	 */
	private const PREFIX = 'resq_core_';

	/**
	 * Default TTL in seconds (1 hour).
	 */
	private const DEFAULT_TTL = HOUR_IN_SECONDS;

	/**
	 * Get a cached value.
	 *
	 * @param string $key Cache key without prefix.
	 * @return mixed|false Cached value or false on miss.
	 */
	public static function get( string $key ) {
		return get_transient( self::PREFIX . $key );
	}

	/**
	 * Set a cached value.
	 *
	 * @param string $key   Cache key without prefix.
	 * @param mixed  $value Value to store.
	 * @param int    $ttl   TTL in seconds.
	 */
	public static function set( string $key, mixed $value, int $ttl = self::DEFAULT_TTL ): void {
		set_transient( self::PREFIX . $key, $value, $ttl );
	}

	/**
	 * Delete a cached value.
	 *
	 * @param string $key Cache key without prefix.
	 */
	public static function delete( string $key ): void {
		delete_transient( self::PREFIX . $key );
	}

	/**
	 * Invalidate all product-scoped cache keys.
	 *
	 * @param int $product_id Product ID.
	 */
	public static function bust_product( int $product_id ): void {
		if ( $product_id <= 0 ) {
			return;
		}

		$keys = array(
			'product_audiences_' . $product_id,
			'product_concerns_' . $product_id,
			'product_routines_' . $product_id,
			'compliance_zone_' . $product_id,
			'canonical_' . $product_id,
			'product_card_' . $product_id,
			'product_badges_' . $product_id,
			'fbt_' . $product_id,
		);

		foreach ( $keys as $key ) {
			self::delete( $key );
		}
	}

	/**
	 * Invalidate routine-scoped cache keys.
	 *
	 * @param int $routine_id Routine CPT post ID.
	 */
	public static function bust_routine( int $routine_id ): void {
		if ( $routine_id <= 0 ) {
			return;
		}

		self::delete( 'routine_steps_' . $routine_id );
	}

	/**
	 * Invalidate gateway featured cache.
	 *
	 * @param string $gateway Gateway slug.
	 */
	public static function bust_gateway( string $gateway ): void {
		if ( '' === $gateway ) {
			return;
		}

		self::delete( 'gateway_featured_' . sanitize_key( $gateway ) );
	}
}
