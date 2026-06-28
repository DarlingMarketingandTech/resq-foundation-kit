<?php
/**
 * Homepage content map — pathways, concerns, favorites, proof cards.
 *
 * Marketing copy and SKU lists for the front page. Routes resolve through
 * existing gateway/lane helpers; no catalog mutations here.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_theme_get_home_favorite_skus' ) ) {
	/**
	 * Catalog SKUs for the homepage favorites shelf (ordered).
	 *
	 * @return string[]
	 */
	function resq_theme_get_home_favorite_skus(): array {
		$skus = array(
			'RQ-HUM-AIOCREAM',
			'RQ-PET-SKINCREAM',
			'RQ-KIT-HUM-WOMENS',
			'RQ-KIT-PET-HOTSPOT',
			'RQ-HUM-MANUKAHONEY',
			'RQ-KIT-HUM-MENS',
		);

		/**
		 * Filter homepage favorite product SKUs.
		 *
		 * @param string[] $skus Ordered catalog SKUs.
		 */
		return apply_filters( 'resq_theme_home_favorite_skus', $skus );
	}
}

if ( ! function_exists( 'resq_theme_get_home_favorite_product_ids' ) ) {
	/**
	 * Resolve homepage favorite SKUs to WooCommerce product IDs.
	 *
	 * @return int[]
	 */
	function resq_theme_get_home_favorite_product_ids(): array {
		$ids = array();

		foreach ( resq_theme_get_home_favorite_skus() as $sku ) {
			$product_id = null;

			if ( function_exists( 'resq_resolve_sku_to_product_id' ) ) {
				$product_id = resq_resolve_sku_to_product_id( (string) $sku );
			} elseif ( function_exists( 'wc_get_product_id_by_sku' ) ) {
				$product_id = (int) wc_get_product_id_by_sku( (string) $sku );
				$product_id = $product_id > 0 ? $product_id : null;
			}

			if ( $product_id && $product_id > 0 ) {
				$ids[] = (int) $product_id;
			}
		}

		return array_values( array_unique( $ids ) );
	}
}

if ( ! function_exists( 'resq_theme_get_home_pathway_cards' ) ) {
	/**
	 * Primary ecommerce pathway cards for the homepage.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function resq_theme_get_home_pathway_cards(): array {
		$human_gateway = function_exists( 'resq_theme_get_gateway_page_url' )
			? trailingslashit( resq_theme_get_gateway_page_url( 'page-gateway-human.php' ) )
			: trailingslashit( home_url( '/shop/human/' ) );

		$pet_gateway = function_exists( 'resq_theme_get_gateway_page_url' )
			? trailingslashit( resq_theme_get_gateway_page_url( 'page-gateway-pet.php' ) )
			: trailingslashit( home_url( '/shop/pet/' ) );

		$bundles_url = function_exists( 'resq_theme_get_gateway_page_url' )
			? resq_theme_get_gateway_page_url( 'page-gateway-bundles.php' )
			: home_url( '/shop/bundles/' );

		$resolve_lane = static function ( string $audience, string $category, string $problem, string $fallback ): string {
			if ( function_exists( 'resq_resolve_lane_url' ) ) {
				return resq_resolve_lane_url( $audience, $category, $problem );
			}
			return $fallback;
		};

		$cards = array(
			array(
				'title'       => __( 'Human Skin Care', 'resq-clean-pro' ),
				'description' => __( 'Cleanse, hydrate, and support your skin barrier with pure botanical routines.', 'resq-clean-pro' ),
				'cta'         => __( 'Shop human care', 'resq-clean-pro' ),
				'url'         => $resolve_lane( 'human', 'womens-skincare', '', $human_gateway . 'womens-skincare/' ),
				'image_slug'  => 'womens-skincare-bathroom-lifestyle',
				'image_label' => __( 'The Daily Radiance Routine', 'resq-clean-pro' ),
				'image_alt'   => __( 'Women’s skincare products styled in a clean bathroom routine setting.', 'resq-clean-pro' ),
				'variant'     => '',
			),
			array(
				'title'       => __( 'Pet Skin Care', 'resq-clean-pro' ),
				'description' => __( 'Lick-safe topical comfort for hot spots, paws, and sensitive-feeling skin.', 'resq-clean-pro' ),
				'cta'         => __( 'Shop pet care', 'resq-clean-pro' ),
				'url'         => $resolve_lane( 'pet', 'topical-skin-care', '', $pet_gateway . 'topical-skin-care/' ),
				'image_slug'  => 'dog-skin-care-gentle-effective-lifestyle',
				'image_label' => __( 'Soothed & Calm Companion', 'resq-clean-pro' ),
				'image_alt'   => __( 'A calm dog resting outdoors in a gentle topical care lifestyle scene.', 'resq-clean-pro' ),
				'variant'     => 'resq-img-placeholder--pet',
			),
			array(
				'title'       => __( 'Bundles & Savings', 'resq-clean-pro' ),
				'description' => __( 'Curated human and pet routines with bundle pricing on everyday essentials.', 'resq-clean-pro' ),
				'cta'         => __( 'Browse bundles', 'resq-clean-pro' ),
				'url'         => $bundles_url,
				'image_slug'  => 'womens-line-complete-collection',
				'image_label' => __( 'Complete Routine Sets', 'resq-clean-pro' ),
				'image_alt'   => __( 'Grouped ResQ skincare and grooming products arranged as bundle sets.', 'resq-clean-pro' ),
				'variant'     => '',
			),
		);

		$cbd_isolation = function_exists( 'resq_core_feature_enabled' )
			? resq_core_feature_enabled( 'cbd_isolation' )
			: true;

		if ( $cbd_isolation ) {
			$cards[] = array(
				'title'       => __( 'CBD & Wellness', 'resq-clean-pro' ),
				'description' => __( 'Age-gated wellness products in a separate regulated pathway.', 'resq-clean-pro' ),
				'cta'         => __( 'View CBD pathway', 'resq-clean-pro' ),
				'url'         => function_exists( 'resq_theme_get_gateway_page_url' )
					? resq_theme_get_gateway_page_url( 'page-gateway-cbd.php' )
					: home_url( '/shop/cbd/' ),
				'image_slug'  => 'full-spectrum-cbd-oil-2',
				'image_label' => __( 'Wellness Collection', 'resq-clean-pro' ),
				'image_alt'   => __( 'ResQ CBD wellness products in a separate regulated collection.', 'resq-clean-pro' ),
				'variant'     => '',
				'is_compact'  => true,
			);
		}

		/**
		 * Filter homepage pathway cards.
		 *
		 * @param array<int, array<string, mixed>> $cards Pathway card definitions.
		 */
		return apply_filters( 'resq_theme_home_pathway_cards', $cards );
	}
}

if ( ! function_exists( 'resq_theme_get_home_concern_groups' ) ) {
	/**
	 * Concern finder groups keyed by audience.
	 *
	 * @param string $human_base Human gateway base URL (trailing slash).
	 * @param string $pet_base   Pet gateway base URL (trailing slash).
	 * @return array<int, array<string, mixed>>
	 */
	function resq_theme_get_home_concern_groups( string $human_base, string $pet_base ): array {
		$human_base = trailingslashit( $human_base );
		$pet_base   = trailingslashit( $pet_base );

		$resolve_lane = static function ( string $audience, string $category, string $problem, string $fallback ): string {
			if ( function_exists( 'resq_resolve_lane_url' ) ) {
				return resq_resolve_lane_url( $audience, $category, $problem );
			}
			return $fallback;
		};

		return array(
			array(
				'label'    => __( 'For you', 'resq-clean-pro' ),
				'concerns' => array(
					array(
						'label'      => __( 'Dry & sensitive skin', 'resq-clean-pro' ),
						'url'        => $resolve_lane( 'human', 'therapeutic-skin-care', '', $human_base . 'therapeutic-skin-care/' ),
						'image_slug' => 'ingredients-cream-honey-aloe-triptych',
						'image'      => __( 'Comfort Care', 'resq-clean-pro' ),
						'alt'        => __( 'Macro texture of cream smoothing over dry skin.', 'resq-clean-pro' ),
						'variant'    => '',
					),
					array(
						'label'      => __( 'Razor burn', 'resq-clean-pro' ),
						'url'        => $resolve_lane( 'human', 'mens-grooming', 'razor-burn', $human_base . 'mens-grooming/' ),
						'image_slug' => 'mens-skin-care-cream-hero-splash',
						'image'      => __( 'Post-Shave Comfort', 'resq-clean-pro' ),
						'alt'        => __( 'Men’s grooming bottles on dark, wet slate.', 'resq-clean-pro' ),
						'variant'    => 'resq-img-placeholder--dark',
					),
					array(
						'label'      => __( 'Baby diaper rash', 'resq-clean-pro' ),
						'url'        => $resolve_lane( 'human', 'baby-infant-care', 'diaper-rash', $human_base . 'baby-infant-care/' ),
						'image_slug' => 'baby-skin-treatment-lifestyle',
						'image'      => __( 'Gentle Barrier Care', 'resq-clean-pro' ),
						'alt'        => __( 'Gentle cream application on delicate infant skin.', 'resq-clean-pro' ),
						'variant'    => '',
					),
					array(
						'label'      => __( 'Scalp care', 'resq-clean-pro' ),
						'url'        => $resolve_lane( 'human', 'hair-scalp-care', '', $human_base . 'hair-scalp-care/' ),
						'image_slug' => 'womens-line-complete-collection',
						'image'      => __( 'Hair & Scalp Ritual', 'resq-clean-pro' ),
						'alt'        => __( 'Shampoo and conditioner duo styled for scalp comfort routines.', 'resq-clean-pro' ),
						'variant'    => '',
					),
				),
			),
			array(
				'label'    => __( 'For pets', 'resq-clean-pro' ),
				'concerns' => array(
					array(
						'label'      => __( 'Hot spots', 'resq-clean-pro' ),
						'url'        => $resolve_lane( 'pet', 'topical-skin-care', '', $pet_base . 'topical-skin-care/' ),
						'image_slug' => 'pet-topical-care-hyperkeratosis-lifestyle',
						'image'      => __( 'Topical Comfort', 'resq-clean-pro' ),
						'alt'        => __( 'Dog receiving gentle topical skin care.', 'resq-clean-pro' ),
						'variant'    => 'resq-img-placeholder--pet',
					),
					array(
						'label'      => __( 'Dry paws & cracked nose', 'resq-clean-pro' ),
						'url'        => $resolve_lane( 'pet', 'topical-skin-care', 'hyperkeratosis', $pet_base . 'topical-skin-care/' ),
						'image_slug' => 'pet-topical-care-hyperkeratosis-lifestyle',
						'image'      => __( 'Snout & Paw Ritual', 'resq-clean-pro' ),
						'alt'        => __( 'Dog with comfortable-looking nose and paw pads.', 'resq-clean-pro' ),
						'variant'    => 'resq-img-placeholder--pet',
					),
					array(
						'label'      => __( 'Itchy skin', 'resq-clean-pro' ),
						'url'        => $resolve_lane( 'pet', 'coat-grooming', 'seasonal-grass-itch', $pet_base . 'coat-grooming/' ),
						'image_slug' => 'dog-skin-care-gentle-effective-lifestyle',
						'image'      => __( 'Coat Comfort', 'resq-clean-pro' ),
						'alt'        => __( 'Dog outdoors after a gentle grooming routine.', 'resq-clean-pro' ),
						'variant'    => 'resq-img-placeholder--pet',
					),
					array(
						'label'      => __( 'Coat care', 'resq-clean-pro' ),
						'url'        => $resolve_lane( 'pet', 'coat-grooming', '', $pet_base . 'coat-grooming/' ),
						'image_slug' => 'dog-skin-care-gentle-effective-lifestyle',
						'image'      => __( 'Grooming Duo', 'resq-clean-pro' ),
						'alt'        => __( 'Pet shampoo and conditioner for everyday coat maintenance.', 'resq-clean-pro' ),
						'variant'    => 'resq-img-placeholder--pet',
					),
				),
			),
		);
	}
}

if ( ! function_exists( 'resq_theme_get_home_proof_cards' ) ) {
	/**
	 * Manuka / formulation proof cards for the ingredient section.
	 *
	 * @return array<int, array<string, string>>
	 */
	function resq_theme_get_home_proof_cards(): array {
		return array(
			array(
				'icon'  => '&#127855;',
				'title' => __( 'Raw Manuka honey', 'resq-clean-pro' ),
				'body'  => __( 'UMF-certified honey anchors every formula as a botanical moisture base.', 'resq-clean-pro' ),
			),
			array(
				'icon'  => '&#127807;',
				'title' => __( '5.5 pH balanced', 'resq-clean-pro' ),
				'body'  => __( 'Engineered to mirror skin’s natural balance for gentle everyday use.', 'resq-clean-pro' ),
			),
			array(
				'icon'  => '&#10024;',
				'title' => __( 'Fragrance-free', 'resq-clean-pro' ),
				'body'  => __( 'No synthetic fragrance or fillers — comfort-first for shared household routines.', 'resq-clean-pro' ),
			),
		);
	}
}

if ( ! function_exists( 'resq_theme_get_home_manuka_product_url' ) ) {
	/**
	 * Permalink for the flagship Manuka honey product (Learn/product bridge).
	 *
	 * @return string
	 */
	function resq_theme_get_home_manuka_product_url(): string {
		$product_id = null;

		if ( function_exists( 'resq_resolve_sku_to_product_id' ) ) {
			$product_id = resq_resolve_sku_to_product_id( 'RQ-HUM-MANUKAHONEY' );
		}

		if ( $product_id && $product_id > 0 ) {
			$url = get_permalink( $product_id );
			return $url ? $url : home_url( '/product/umf-medical-grade-manuka-honey/' );
		}

		return home_url( '/product/umf-medical-grade-manuka-honey/' );
	}
}
