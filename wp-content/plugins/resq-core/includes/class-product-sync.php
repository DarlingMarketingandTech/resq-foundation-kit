<?php
/**
 * Product save handlers — compliance zone sync and cache busting.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Syncs compliance zone meta and invalidates caches on product save.
 */
class ResQ_Core_Product_Sync {

	/**
	 * Register hooks.
	 */
	public static function register_hooks(): void {
		add_action( 'woocommerce_process_product_meta', array( self::class, 'on_process_product_meta' ), 20 );
		add_action( 'set_object_terms', array( self::class, 'on_set_object_terms' ), 10, 6 );
		add_action( 'save_post_resq_routine', array( self::class, 'on_save_routine' ), 10, 2 );
		add_action( 'save_post_product', array( self::class, 'on_save_product' ), 20, 2 );
	}

	/**
	 * Sync compliance zone meta after WooCommerce product meta save.
	 *
	 * @param int $product_id Product ID.
	 */
	public static function on_process_product_meta( int $product_id ): void {
		self::sync_compliance_zone_meta( $product_id );
		ResQ_Core_Cache::bust_product( $product_id );
	}

	/**
	 * Sync zone meta when compliance zone terms are assigned.
	 *
	 * @param int    $object_id  Object ID.
	 * @param array  $terms      Term IDs.
	 * @param array  $tt_ids     Term taxonomy IDs.
	 * @param string $taxonomy   Taxonomy slug.
	 * @param bool   $append     Whether terms are appended.
	 * @param array  $old_tt_ids Old term taxonomy IDs.
	 */
	public static function on_set_object_terms( int $object_id, array $terms, array $tt_ids, string $taxonomy, bool $append, array $old_tt_ids ): void { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		if ( 'resq_compliance_zone' !== $taxonomy ) {
			return;
		}

		self::sync_compliance_zone_meta( $object_id );
		ResQ_Core_Cache::bust_product( $object_id );
	}

	/**
	 * Bust routine cache on save.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public static function on_save_routine( int $post_id, WP_Post $post ): void { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		ResQ_Core_Cache::bust_routine( $post_id );

		// Bust routines cache for products linked to this routine.
		$products = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => '_resq_routine_ids',
						'value'   => '"' . $post_id . '"',
						'compare' => 'LIKE',
					),
				),
			)
		);

		foreach ( $products as $product_id ) {
			ResQ_Core_Cache::bust_product( (int) $product_id );
		}
	}

	/**
	 * Ensure published products have a default compliance zone.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public static function on_save_product( int $post_id, WP_Post $post ): void {
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( 'product' !== $post->post_type || 'publish' !== $post->post_status ) {
			return;
		}

		$zone_meta = get_post_meta( $post_id, '_resq_compliance_zone', true );
		$terms     = wp_get_post_terms( $post_id, 'resq_compliance_zone', array( 'fields' => 'slugs' ) );

		if ( ( '' === $zone_meta || false === $zone_meta ) && ( is_wp_error( $terms ) || empty( $terms ) ) ) {
			update_post_meta( $post_id, '_resq_compliance_zone', 'standard' );
		}

		ResQ_Core_Cache::bust_product( $post_id );
	}

	/**
	 * Mirror resq_compliance_zone taxonomy assignment to _resq_compliance_zone meta.
	 *
	 * @param int $product_id Product ID.
	 */
	public static function sync_compliance_zone_meta( int $product_id ): void {
		if ( $product_id <= 0 ) {
			return;
		}

		$terms = wp_get_post_terms( $product_id, 'resq_compliance_zone', array( 'fields' => 'slugs' ) );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			$existing = get_post_meta( $product_id, '_resq_compliance_zone', true );
			if ( '' === $existing || false === $existing ) {
				update_post_meta( $product_id, '_resq_compliance_zone', 'standard' );
			}
			return;
		}

		$slug = ResQ_Core_Registrations_Post_Meta::sanitize_compliance_zone( $terms[0] );
		update_post_meta( $product_id, '_resq_compliance_zone', $slug );
	}
}
