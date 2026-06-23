<?php
/**
 * Real catalog importer — extends fixture importer patterns.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Idempotent WooCommerce catalog importer for production catalog data.
 */
class ResQ_Core_Catalog_Importer extends ResQ_Core_Fixture_Importer {

	/**
	 * SKU prefix for catalog products.
	 */
	public const SKU_PREFIX = 'RQ-';

	/**
	 * Routine slug prefix.
	 */
	public const ROUTINE_PREFIX = 'rq-';

	/**
	 * @return void
	 */
	protected function assert_prerequisites(): void {
		parent::assert_prerequisites();

		if ( ! taxonomy_exists( 'resq_product_role' ) ) {
			throw new RuntimeException( 'Required taxonomy "resq_product_role" is missing. Activate resq-core fully before importing.' );
		}
	}

	/**
	 * Import the full real catalog.
	 *
	 * @return array{products: array<string, int>, routines: array<string, int>, bundles: array<string, int>}
	 */
	public function import(): array {
		$this->assert_prerequisites();

		if ( function_exists( 'wc_delete_product_transients' ) ) {
			wc_delete_product_transients();
		}

		$catalog = resq_catalog_get_data();
		$this->seed_taxonomies( $catalog['seed_taxonomies'] ?? array() );

		$ids = array(
			'products' => array(),
			'routines' => array(),
			'bundles'  => array(),
		);

		foreach ( $catalog['products'] as $sku => $data ) {
			$product_id = $this->import_product( $sku, $data );
			if ( $product_id > 0 ) {
				$ids['products'][ $sku ] = $product_id;
			}

			if ( 'variable' === ( $data['type'] ?? 'simple' ) && ! empty( $data['variations'] ) ) {
				foreach ( $data['variations'] as $variation ) {
					$var_sku = (string) ( $variation['sku'] ?? '' );
					if ( '' === $var_sku ) {
						continue;
					}
					$var_id = $this->get_product_id_by_sku( $var_sku );
					if ( $var_id > 0 ) {
						$ids['products'][ $var_sku ] = $var_id;
					}
				}
			}
		}

		$this->apply_canonical_targets( $catalog['products'], $ids['products'] );
		$this->apply_fbt_links( $catalog['products'], $ids['products'] );

		foreach ( $catalog['routines'] as $slug => $routine_data ) {
			$routine_id = $this->import_routine( $slug, $routine_data, $ids['products'] );
			if ( $routine_id > 0 ) {
				$ids['routines'][ $slug ] = $routine_id;
			}
		}

		foreach ( $catalog['bundles'] as $sku => $bundle_data ) {
			$bundle_id = $this->import_bundle( $sku, $bundle_data, $ids['products'], $ids['routines'] );
			if ( $bundle_id > 0 ) {
				$ids['bundles'][ $sku ] = $bundle_id;
			}
		}

		update_option( 'resq_catalog_import_version', RESQ_CORE_VERSION );
		update_option( 'resq_catalog_import_at', current_time( 'mysql' ) );

		return $ids;
	}

	/**
	 * Remove catalog-owned products and routines.
	 *
	 * @return array{products: int, routines: int}
	 */
	public function reset(): array {
		$this->assert_prerequisites();

		$removed = array(
			'products' => 0,
			'routines' => 0,
		);

		foreach ( $this->find_catalog_product_ids() as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( $product ) {
				$product->delete( true );
				++$removed['products'];
			}
		}

		foreach ( get_posts(
			array(
				'post_type'      => 'resq_routine',
				'post_status'    => 'any',
				'posts_per_page' => -1,
			)
		) as $routine_post ) {
			if ( ! str_starts_with( $routine_post->post_name, self::ROUTINE_PREFIX ) ) {
				continue;
			}

			wp_delete_post( $routine_post->ID, true );
			++$removed['routines'];
		}

		return $removed;
	}

	/**
	 * @param array<string, mixed> $seed Taxonomy seed definitions.
	 */
	protected function seed_taxonomies( array $seed ): void {
		foreach ( (array) ( $seed['resq_audience'] ?? array() ) as $slug => $name ) {
			$this->ensure_term( (string) $slug, (string) $name, 'resq_audience' );
		}

		foreach ( (array) ( $seed['resq_ingredient'] ?? array() ) as $slug => $name ) {
			$this->ensure_term( (string) $slug, (string) $name, 'resq_ingredient' );
		}

		foreach ( (array) ( $seed['resq_product_role'] ?? array() ) as $slug => $name ) {
			$this->ensure_term( (string) $slug, (string) $name, 'resq_product_role' );
		}

		foreach ( (array) ( $seed['resq_compliance_zone'] ?? array() ) as $slug => $name ) {
			$this->ensure_term( (string) $slug, (string) $name, 'resq_compliance_zone' );
		}

		foreach ( (array) ( $seed['resq_concern'] ?? array() ) as $parent_slug => $parent_data ) {
			$parent_id = $this->ensure_term(
				(string) $parent_slug,
				(string) ( $parent_data['name'] ?? $parent_slug ),
				'resq_concern'
			);
			foreach ( (array) ( $parent_data['children'] ?? array() ) as $child_slug => $child_name ) {
				$this->ensure_term( (string) $child_slug, (string) $child_name, 'resq_concern', $parent_id );
			}
		}

		$cat_parents = array();
		foreach ( (array) ( $seed['product_cat'] ?? array() ) as $slug => $cat_data ) {
			if ( is_string( $cat_data ) ) {
				$cat_data = array( 'name' => $cat_data, 'parent' => '' );
			}
			$parent_slug = (string) ( $cat_data['parent'] ?? '' );
			$parent_id   = ( '' !== $parent_slug && isset( $cat_parents[ $parent_slug ] ) ) ? $cat_parents[ $parent_slug ] : 0;
			$term_id     = $this->ensure_term( (string) $slug, (string) ( $cat_data['name'] ?? $slug ), 'product_cat', $parent_id );
			if ( $term_id > 0 ) {
				$cat_parents[ $slug ] = $term_id;
			}
		}
	}

	/**
	 * @param string               $slug Term slug.
	 * @param string               $name Display name.
	 * @param string               $taxonomy Taxonomy.
	 * @param int                  $parent Parent term ID.
	 * @return int
	 */
	protected function ensure_term( string $slug, string $name, string $taxonomy, int $parent = 0 ): int {
		$slug = sanitize_title( $slug );
		if ( '' === $slug || ! taxonomy_exists( $taxonomy ) ) {
			return 0;
		}

		$existing = get_term_by( 'slug', $slug, $taxonomy );
		if ( $existing && ! is_wp_error( $existing ) ) {
			if ( $parent > 0 && (int) $existing->parent !== $parent ) {
				wp_update_term( (int) $existing->term_id, $taxonomy, array( 'parent' => $parent ) );
			}
			return (int) $existing->term_id;
		}

		$args = array( 'slug' => $slug );
		if ( $parent > 0 ) {
			$args['parent'] = $parent;
		}

		$result = wp_insert_term( $name, $taxonomy, $args );
		if ( is_wp_error( $result ) ) {
			if ( isset( $result->error_data['term_exists'] ) ) {
				return (int) $result->error_data['term_exists'];
			}
			return 0;
		}

		return (int) $result['term_id'];
	}

	/**
	 * @param string               $sku  Product SKU.
	 * @param array<string, mixed> $data Product definition.
	 * @return int
	 */
	protected function import_product( string $sku, array $data ): int {
		$product_id = parent::import_product( $sku, $data );
		if ( $product_id <= 0 ) {
			return 0;
		}

		if ( ! empty( $data['roles'] ) ) {
			$this->assign_terms( $product_id, (array) $data['roles'], 'resq_product_role' );
		}

		if ( ! empty( $data['badge_label'] ) ) {
			update_post_meta( $product_id, '_resq_badge_label', sanitize_text_field( (string) $data['badge_label'] ) );
			update_post_meta( $product_id, '_resq_badge_type', sanitize_key( (string) ( $data['badge_type'] ?? 'custom' ) ) );
		}

		if ( ! empty( $data['short_description'] ) ) {
			wp_update_post(
				array(
					'ID'           => $product_id,
					'post_excerpt' => sanitize_textarea_field( (string) $data['short_description'] ),
				)
			);
		}

		return $product_id;
	}

	/**
	 * @param string               $sku Bundle SKU.
	 * @param array<string, mixed> $data Bundle definition.
	 * @param array<string, int>   $product_ids SKU map.
	 * @param array<string, int>   $routine_ids Routine map.
	 * @return int
	 */
	protected function import_bundle( string $sku, array $data, array $product_ids, array $routine_ids ): int {
		$product_id = $this->get_product_id_by_sku( $sku );
		$product    = $product_id > 0 ? wc_get_product( $product_id ) : new WC_Product_Simple();
		if ( ! $product instanceof WC_Product_Simple ) {
			$product = new WC_Product_Simple( $product_id );
		}

		$product->set_name( (string) $data['name'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_sku( $sku );
		$product->set_regular_price( (string) $data['price'] );
		$product->set_price( (string) $data['price'] );
		$product->set_manage_stock( false );
		$product->set_stock_status( 'instock' );

		$bundle_id = (int) $product->save();
		if ( $bundle_id <= 0 ) {
			return 0;
		}

		$composition = array();
		foreach ( (array) ( $data['components'] ?? array() ) as $component ) {
			$component_sku = (string) ( $component['sku'] ?? '' );
			$resolved_id   = $this->resolve_sku_to_product_id( $component_sku, $product_ids );
			if ( $resolved_id <= 0 ) {
				continue;
			}

			$composition[] = array(
				'product_id' => $resolved_id,
				'qty'        => max( 1, (int) ( $component['qty'] ?? 1 ) ),
			);
		}

		update_post_meta(
			$bundle_id,
			'_resq_bundle_product_ids',
			ResQ_Core_Registrations_Post_Meta::sanitize_bundle_product_ids( $composition )
		);

		update_post_meta( $bundle_id, '_resq_badge_label', 'Bundle' );
		update_post_meta( $bundle_id, '_resq_badge_type', 'bundle' );

		if ( ! empty( $data['categories'] ) ) {
			$this->assign_product_categories( $bundle_id, (array) $data['categories'] );
		}

		$this->assign_terms( $bundle_id, $data['audiences'] ?? array(), 'resq_audience' );
		$this->assign_terms( $bundle_id, array( (string) ( $data['zone'] ?? 'standard' ) ), 'resq_compliance_zone' );
		update_post_meta( $bundle_id, '_resq_compliance_flags', ResQ_Core_Registrations_Post_Meta::sanitize_compliance_flags( $data['compliance_flags'] ?? array() ) );
		update_post_meta( $bundle_id, '_resq_gateway_featured', array( 'bundles' ) );

		$routine_slug = (string) ( $data['routine_slug'] ?? '' );
		if ( '' !== $routine_slug && isset( $routine_ids[ $routine_slug ] ) ) {
			update_post_meta( $bundle_id, '_resq_routine_ids', array( (int) $routine_ids[ $routine_slug ] ) );
			update_post_meta( $bundle_id, '_resq_primary_routine_id', (int) $routine_ids[ $routine_slug ] );
			update_post_meta( (int) $routine_ids[ $routine_slug ], '_resq_routine_bundle_target', $bundle_id );
		}

		ResQ_Core_Product_Sync::sync_compliance_zone_meta( $bundle_id );
		ResQ_Core_Cache::bust_product( $bundle_id );

		return $bundle_id;
	}

	/**
	 * Resolve parent or variation SKU to product ID.
	 *
	 * @param string             $sku SKU.
	 * @param array<string, int> $map Imported SKU map.
	 * @return int
	 */
	protected function resolve_sku_to_product_id( string $sku, array $map ): int {
		if ( isset( $map[ $sku ] ) ) {
			return (int) $map[ $sku ];
		}

		return $this->get_product_id_by_sku( $sku );
	}

	/**
	 * @return int[]
	 */
	protected function find_catalog_product_ids(): array {
		global $wpdb;

		$like = $wpdb->esc_like( self::SKU_PREFIX ) . '%';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_sku' AND meta_value LIKE %s",
				$like
			)
		);

		return array_map( 'intval', $ids ?: array() );
	}

	/**
	 * Export catalog rows for WooCommerce CSV reference.
	 *
	 * @return array<int, array<string, string>>
	 */
	public function export_csv_rows(): array {
		$catalog = resq_catalog_get_data();
		$rows    = array();

		foreach ( $catalog['products'] as $sku => $data ) {
			$type = (string) ( $data['type'] ?? 'simple' );
			$cats = array_map(
				static fn( $c ) => (string) ( $c['name'] ?? '' ),
				(array) ( $data['categories'] ?? array() )
			);

			if ( 'variable' === $type ) {
				$rows[] = array(
					'Type'             => 'variable',
					'SKU'              => $sku,
					'Name'             => (string) $data['name'],
					'Published'        => '1',
					'Regular price'    => (string) ( $data['price'] ?? '' ),
					'Categories'       => implode( ' > ', $cats ),
					'Short description' => (string) ( $data['card_sub'] ?? '' ),
				);
				foreach ( (array) ( $data['variations'] ?? array() ) as $variation ) {
					$attr_parts = array();
					foreach ( (array) ( $variation['attributes'] ?? array() ) as $name => $value ) {
						$attr_parts[] = ucwords( $name ) . ': ' . $value;
					}
					$rows[] = array(
						'Type'          => 'variation',
						'SKU'           => (string) ( $variation['sku'] ?? '' ),
						'Name'          => (string) $data['name'],
						'Published'     => '1',
						'Regular price' => (string) ( $variation['price'] ?? '' ),
						'Parent'        => $sku,
						'Attribute 1 name'  => ucwords( (string) array_key_first( (array) ( $variation['attributes'] ?? array() ) ) ),
						'Attribute 1 value(s)' => (string) reset( (array) ( $variation['attributes'] ?? array() ) ),
					);
				}
			} else {
				$rows[] = array(
					'Type'             => 'simple',
					'SKU'              => $sku,
					'Name'             => (string) $data['name'],
					'Published'        => '1',
					'Regular price'    => (string) ( $data['price'] ?? '' ),
					'Categories'       => implode( ' > ', $cats ),
					'Short description' => (string) ( $data['card_sub'] ?? '' ),
				);
			}
		}

		foreach ( $catalog['bundles'] as $sku => $data ) {
			$rows[] = array(
				'Type'          => 'simple',
				'SKU'           => $sku,
				'Name'          => (string) $data['name'],
				'Published'     => '1',
				'Regular price' => (string) ( $data['price'] ?? '' ),
				'Categories'    => 'Bundles & Savings',
			);
		}

		return $rows;
	}
}
