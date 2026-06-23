<?php
/**
 * Phase 7 demo fixture importer.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Idempotent WooCommerce fixture importer for local sandbox use.
 */
class ResQ_Core_Fixture_Importer {

	/**
	 * SKU prefix for all fixture products — used by reset().
	 */
	public const SKU_PREFIX = 'fixture-';

	/**
	 * Import the full fixture catalog.
	 *
	 * @return array{products: array<string, int>, routines: array<string, int>, bundles: array<string, int>}
	 */
	public function import(): array {
		$this->assert_prerequisites();

		if ( function_exists( 'wc_delete_product_transients' ) ) {
			wc_delete_product_transients();
		}

		$catalog = resq_fixtures_get_catalog();
		$ids     = array(
			'products' => array(),
			'routines' => array(),
			'bundles'  => array(),
		);

		foreach ( $catalog['products'] as $sku => $data ) {
			$product_id = $this->import_product( $sku, $data );
			if ( $product_id > 0 ) {
				$ids['products'][ $sku ] = $product_id;
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

		return $ids;
	}

	/**
	 * Remove fixture-owned products and routines.
	 *
	 * @return array{products: int, routines: int}
	 */
	public function reset(): array {
		$this->assert_prerequisites();

		$removed = array(
			'products' => 0,
			'routines' => 0,
		);

		$product_ids = $this->find_fixture_product_ids();
		foreach ( $product_ids as $product_id ) {
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
			if ( ! str_starts_with( $routine_post->post_name, self::SKU_PREFIX ) ) {
				continue;
			}

			wp_delete_post( $routine_post->ID, true );
			++$removed['routines'];
		}

		return $removed;
	}

	/**
	 * @return void
	 */
	protected function assert_prerequisites(): void {
		if ( ! resq_is_woocommerce_available() ) {
			throw new RuntimeException( 'WooCommerce must be active before importing fixtures.' );
		}

		$taxonomies = array(
			'resq_audience',
			'resq_concern',
			'resq_ingredient',
			'resq_compliance_zone',
			'product_cat',
		);

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! taxonomy_exists( $taxonomy ) ) {
				throw new RuntimeException(
					sprintf( 'Required taxonomy "%s" is missing. Activate resq-core fully before importing.', $taxonomy )
				);
			}
		}

		if ( ! post_type_exists( 'resq_routine' ) ) {
			throw new RuntimeException( 'Required post type "resq_routine" is missing. Activate resq-core fully before importing.' );
		}
	}

	/**
	 * @param string               $sku  Fixture SKU.
	 * @param array<string, mixed> $data Product definition.
	 * @return int Product ID or 0 on failure.
	 */
	protected function import_product( string $sku, array $data ): int {
		$product_id = $this->get_product_id_by_sku( $sku );
		$type       = (string) ( $data['type'] ?? 'simple' );

		if ( 'variable' === $type ) {
			$product = $product_id > 0 ? wc_get_product( $product_id ) : new WC_Product_Variable();
			if ( ! $product instanceof WC_Product_Variable ) {
				$product = new WC_Product_Variable( $product_id );
			}
		} else {
			$product = $product_id > 0 ? wc_get_product( $product_id ) : new WC_Product_Simple();
			if ( ! $product instanceof WC_Product_Simple ) {
				$product = new WC_Product_Simple( $product_id );
			}
		}

		if ( ! $product ) {
			return 0;
		}

		$product->set_name( (string) $data['name'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_sku( $sku );
		$product->set_regular_price( (string) $data['price'] );
		$product->set_price( (string) $data['price'] );
		$product->set_manage_stock( false );
		$product->set_stock_status( 'instock' );

		if ( 'variable' === $type ) {
			$product->set_attributes( $this->build_variation_attributes( $data['variations'] ?? array() ) );
		}

		$product_id = (int) $product->save();

		if ( $product_id <= 0 ) {
			return 0;
		}

		if ( 'variable' === $type ) {
			$this->sync_variations( $product_id, $sku, $data['variations'] ?? array() );
			$variable = wc_get_product( $product_id );
			if ( $variable instanceof WC_Product_Variable ) {
				$variable->save();
			}
		}

		$this->assign_product_categories( $product_id, $data['categories'] ?? array() );
		$this->assign_terms( $product_id, $data['audiences'] ?? array(), 'resq_audience' );
		$this->assign_terms( $product_id, $data['concerns'] ?? array(), 'resq_concern' );
		$this->assign_terms( $product_id, $data['ingredients'] ?? array(), 'resq_ingredient' );
		$this->assign_terms( $product_id, array( (string) ( $data['zone'] ?? 'standard' ) ), 'resq_compliance_zone' );

		$this->update_product_meta(
			$product_id,
			array(
				'_resq_product_card_subtitle' => (string) ( $data['card_sub'] ?? '' ),
				'_resq_short_benefit_tags'    => $data['benefits'] ?? array(),
				'_resq_compliance_flags'      => $data['compliance_flags'] ?? array(),
				'_resq_gateway_featured'      => $data['gateway_featured'] ?? array(),
				'_resq_ingredient_profile'    => $data['ingredient_profile'] ?? array(),
			)
		);

		ResQ_Core_Product_Sync::sync_compliance_zone_meta( $product_id );
		ResQ_Core_Cache::bust_product( $product_id );

		return $product_id;
	}

	/**
	 * @param array<int, array<string, mixed>> $variations Variation definitions.
	 * @return WC_Product_Attribute[]
	 */
	protected function build_variation_attributes( array $variations ): array {
		$grouped = array();

		foreach ( $variations as $variation ) {
			foreach ( (array) ( $variation['attributes'] ?? array() ) as $name => $value ) {
				$label = ucwords( str_replace( '-', ' ', (string) $name ) );
				if ( ! isset( $grouped[ $label ] ) ) {
					$grouped[ $label ] = array();
				}
				$grouped[ $label ][] = (string) $value;
			}
		}

		$attributes = array();
		foreach ( $grouped as $label => $options ) {
			$options = array_values( array_unique( $options ) );
			if ( empty( $options ) ) {
				continue;
			}

			$attribute = new WC_Product_Attribute();
			$attribute->set_name( $label );
			$attribute->set_options( $options );
			$attribute->set_visible( true );
			$attribute->set_variation( true );
			$attributes[] = $attribute;
		}

		return $attributes;
	}

	/**
	 * @param int                                $parent_id Parent product ID.
	 * @param string                             $parent_sku Parent SKU.
	 * @param array<int, array<string, mixed>>   $variations Variation definitions.
	 */
	protected function sync_variations( int $parent_id, string $parent_sku, array $variations ): array {
		$variation_ids = array();
		foreach ( $variations as $variation_data ) {
			$variation_sku = (string) ( $variation_data['sku'] ?? '' );
			if ( '' === $variation_sku ) {
				continue;
			}

			$variation_id = $this->get_product_id_by_sku( $variation_sku );
			$variation    = $variation_id > 0 ? new WC_Product_Variation( $variation_id ) : new WC_Product_Variation();
			$attributes   = array();

			foreach ( (array) ( $variation_data['attributes'] ?? array() ) as $name => $value ) {
				$attributes[ 'attribute_' . sanitize_title( (string) $name ) ] = (string) $value;
			}

			$variation->set_parent_id( $parent_id );
			$variation->set_attributes( $attributes );
			$variation->set_sku( $variation_sku );
			$variation->set_regular_price( (string) ( $variation_data['price'] ?? '0' ) );
			$variation->set_price( (string) ( $variation_data['price'] ?? '0' ) );
			$variation->set_status( 'publish' );
			$variation->set_manage_stock( false );
			$variation->set_stock_status( 'instock' );
			$variation_id = (int) $variation->save();
			if ( $variation_id > 0 && '' !== $variation_sku ) {
				$variation_ids[ $variation_sku ] = $variation_id;
			}
		}

		if ( function_exists( 'wc_delete_product_transients' ) ) {
			wc_delete_product_transients( $parent_id );
		}

		return $variation_ids;
	}

	/**
	 * @param array<string, array<string, mixed>> $products Product catalog.
	 * @param array<string, int>                  $ids      Imported product IDs keyed by SKU.
	 */
	protected function apply_canonical_targets( array $products, array $ids ): void {
		foreach ( $products as $sku => $data ) {
			$target_sku = (string) ( $data['canonical_target_sku'] ?? '' );
			if ( '' === $target_sku || ! isset( $ids[ $sku ], $ids[ $target_sku ] ) ) {
				continue;
			}

			update_post_meta( $ids[ $sku ], '_resq_canonical_product_id', (int) $ids[ $target_sku ] );
		}
	}

	/**
	 * @param array<string, array<string, mixed>> $products Product catalog.
	 * @param array<string, int>                  $ids      Imported product IDs keyed by SKU.
	 */
	protected function apply_fbt_links( array $products, array $ids ): void {
		foreach ( $products as $sku => $data ) {
			if ( empty( $data['fbt'] ) || ! isset( $ids[ $sku ] ) ) {
				continue;
			}

			$fbt_ids = array();
			foreach ( (array) $data['fbt'] as $related_sku ) {
				if ( isset( $ids[ $related_sku ] ) ) {
					$fbt_ids[] = (int) $ids[ $related_sku ];
				}
			}

			update_post_meta(
				$ids[ $sku ],
				'_resq_fbt_product_ids',
				ResQ_Core_Registrations_Post_Meta::sanitize_int_array( $fbt_ids )
			);
		}
	}

	/**
	 * @param string               $slug Routine slug.
	 * @param array<string, mixed> $data Routine definition.
	 * @param array<string, int>   $product_ids Imported product IDs.
	 * @return int Routine post ID.
	 */
	protected function import_routine( string $slug, array $data, array $product_ids ): int {
		$existing = get_page_by_path( $slug, OBJECT, 'resq_routine' );
		$postarr  = array(
			'post_title'  => (string) $data['title'],
			'post_name'   => $slug,
			'post_status' => 'publish',
			'post_type'   => 'resq_routine',
		);

		if ( $existing ) {
			$postarr['ID'] = $existing->ID;
			$routine_id    = wp_update_post( $postarr, true );
		} else {
			$routine_id = wp_insert_post( $postarr, true );
		}

		if ( is_wp_error( $routine_id ) ) {
			return 0;
		}

		$routine_id = (int) $routine_id;
		$steps      = array();
		$order      = 1;

		foreach ( (array) ( $data['steps'] ?? array() ) as $step_sku ) {
			if ( ! isset( $product_ids[ $step_sku ] ) ) {
				continue;
			}

			$product_id = (int) $product_ids[ $step_sku ];
			$steps[]    = array(
				'order'       => $order,
				'title'       => sprintf( 'Step %d', $order ),
				'product_id'  => $product_id,
				'bundle_id'   => 0,
				'is_optional' => false,
			);

			$routine_ids = get_post_meta( $product_id, '_resq_routine_ids', true );
			$routine_ids = is_array( $routine_ids ) ? $routine_ids : array();
			if ( ! in_array( $routine_id, $routine_ids, true ) ) {
				$routine_ids[] = $routine_id;
			}
			update_post_meta(
				$product_id,
				'_resq_routine_ids',
				ResQ_Core_Registrations_Post_Meta::sanitize_int_array( $routine_ids )
			);

			++$order;
		}

		update_post_meta(
			$routine_id,
			'_resq_routine_steps',
			ResQ_Core_Registrations_Post_Meta::sanitize_routine_steps( $steps )
		);
		update_post_meta( $routine_id, '_resq_routine_audience', sanitize_key( (string) ( $data['audience'] ?? '' ) ) );

		$primary_sku = (string) ( $data['primary_for'] ?? '' );
		if ( '' !== $primary_sku && isset( $product_ids[ $primary_sku ] ) ) {
			update_post_meta( $product_ids[ $primary_sku ], '_resq_primary_routine_id', $routine_id );
		}

		ResQ_Core_Cache::bust_routine( $routine_id );

		return $routine_id;
	}

	/**
	 * @param string               $sku Bundle SKU.
	 * @param array<string, mixed> $data Bundle definition.
	 * @param array<string, int>   $product_ids Imported product IDs.
	 * @param array<string, int>   $routine_ids Imported routine IDs.
	 * @return int Bundle product ID.
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
			if ( ! isset( $product_ids[ $component_sku ] ) ) {
				continue;
			}

			$composition[] = array(
				'product_id' => (int) $product_ids[ $component_sku ],
				'qty'        => max( 1, (int) ( $component['qty'] ?? 1 ) ),
			);
		}

		update_post_meta(
			$bundle_id,
			'_resq_bundle_product_ids',
			ResQ_Core_Registrations_Post_Meta::sanitize_bundle_product_ids( $composition )
		);

		$this->assign_terms( $bundle_id, $data['audiences'] ?? array(), 'resq_audience' );
		$this->assign_terms( $bundle_id, array( (string) ( $data['zone'] ?? 'standard' ) ), 'resq_compliance_zone' );
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
	 * @param int                             $product_id Product ID.
	 * @param array<int, array<string, string>> $categories Category definitions.
	 */
	protected function assign_product_categories( int $product_id, array $categories ): void {
		$term_ids = array();

		foreach ( $categories as $category ) {
			$term_id = $this->ensure_term(
				(string) ( $category['slug'] ?? '' ),
				(string) ( $category['name'] ?? '' ),
				'product_cat'
			);
			if ( $term_id > 0 ) {
				$term_ids[] = $term_id;
			}
		}

		if ( ! empty( $term_ids ) ) {
			wp_set_object_terms( $product_id, $term_ids, 'product_cat' );
		}
	}

	/**
	 * @param int      $product_id Product ID.
	 * @param string[] $slugs Term slugs.
	 * @param string   $taxonomy Taxonomy slug.
	 */
	protected function assign_terms( int $product_id, array $slugs, string $taxonomy ): void {
		$term_ids = array();

		foreach ( $slugs as $slug ) {
			$slug = sanitize_key( (string) $slug );
			if ( '' === $slug ) {
				continue;
			}

			$term_id = $this->ensure_term( $slug, ucwords( str_replace( '-', ' ', $slug ) ), $taxonomy );
			if ( $term_id > 0 ) {
				$term_ids[] = $term_id;
			}
		}

		if ( ! empty( $term_ids ) ) {
			wp_set_object_terms( $product_id, $term_ids, $taxonomy );
		}
	}

	/**
	 * @param string $slug Term slug.
	 * @param string $name Human-readable name.
	 * @param string $taxonomy Taxonomy slug.
	 * @return int Term ID.
	 */
	protected function ensure_term( string $slug, string $name, string $taxonomy ): int {
		$slug = sanitize_title( $slug );
		if ( '' === $slug ) {
			return 0;
		}

		$term = get_term_by( 'slug', $slug, $taxonomy );
		if ( $term && ! is_wp_error( $term ) ) {
			return (int) $term->term_id;
		}

		$result = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
		if ( is_wp_error( $result ) ) {
			if ( isset( $result->error_data['term_exists'] ) ) {
				return (int) $result->error_data['term_exists'];
			}
			return 0;
		}

		return (int) $result['term_id'];
	}

	/**
	 * @param int                  $product_id Product ID.
	 * @param array<string, mixed> $meta Meta values.
	 */
	protected function update_product_meta( int $product_id, array $meta ): void {
		if ( isset( $meta['_resq_compliance_flags'] ) ) {
			$meta['_resq_compliance_flags'] = ResQ_Core_Registrations_Post_Meta::sanitize_compliance_flags( $meta['_resq_compliance_flags'] );
		}

		if ( isset( $meta['_resq_short_benefit_tags'] ) ) {
			$meta['_resq_short_benefit_tags'] = ResQ_Core_Registrations_Post_Meta::sanitize_benefit_tags( $meta['_resq_short_benefit_tags'] );
		}

		if ( isset( $meta['_resq_gateway_featured'] ) ) {
			$meta['_resq_gateway_featured'] = ResQ_Core_Registrations_Post_Meta::sanitize_gateway_featured( $meta['_resq_gateway_featured'] );
		}

		if ( isset( $meta['_resq_ingredient_profile'] ) ) {
			$meta['_resq_ingredient_profile'] = ResQ_Core_Registrations_Post_Meta::sanitize_ingredient_profile( $meta['_resq_ingredient_profile'] );
		}

		foreach ( $meta as $key => $value ) {
			update_post_meta( $product_id, $key, $value );
		}
	}

	/**
	 * @param string $sku Product SKU.
	 * @return int Product ID or 0.
	 */
	protected function get_product_id_by_sku( string $sku ): int {
		if ( function_exists( 'wc_get_product_id_by_sku' ) ) {
			return (int) wc_get_product_id_by_sku( $sku );
		}

		return 0;
	}

	/**
	 * @return int[] Fixture product IDs.
	 */
	protected function find_fixture_product_ids(): array {
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
}
