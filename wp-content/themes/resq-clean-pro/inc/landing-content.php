<?php
/**
 * Landing-page content map.
 *
 * Single source for gateway marketing copy + section definitions, keyed by
 * gateway slug. Copy is compliance-adapted per docs/05 and docs/24. Templates
 * read this map; they must not hardcode marketing copy inline.
 *
 * All copy here is DRAFT presentation copy (comfort/cosmetic language) and is
 * not owner/legal-approved production copy.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_theme_resolve_landing_url' ) ) {
	/**
	 * Resolve a landing content path against a gateway base URL.
	 *
	 * - Paths beginning with '#' are returned as-is (in-page anchors).
	 * - Absolute URLs (http/https) are returned as-is.
	 * - Empty paths return the base URL.
	 * - Otherwise the path is appended to the trailing-slashed base.
	 *
	 * @param string $path     Relative path, anchor, or absolute URL.
	 * @param string $base_url Gateway base URL.
	 * @return string
	 */
	function resq_theme_resolve_landing_url( string $path, string $base_url ): string {
		$path = trim( $path );

		if ( '' === $path ) {
			return $base_url;
		}

		if ( '#' === $path[0] || 0 === strpos( $path, 'http://' ) || 0 === strpos( $path, 'https://' ) ) {
			return $path;
		}

		return trailingslashit( $base_url ) . ltrim( $path, '/' );
	}
}

if ( ! function_exists( 'resq_theme_get_landing_content' ) ) {
	/**
	 * Return the landing content map for a gateway slug.
	 *
	 * Paths in `primary_cta` and segment `path` are relative to the gateway
	 * base URL (resolved by the template) unless they begin with '#' (in-page
	 * anchor) or 'http' (absolute).
	 *
	 * @param string $slug Gateway slug: human, pet, bundles, cbd.
	 * @return array<string, mixed> Empty array when no map exists for the slug.
	 */
	function resq_theme_get_landing_content( string $slug ): array {
		$slug = sanitize_key( $slug );

		$map = array(
			'human' => array(
				'eyebrow'     => __( 'ResQ Organics — Human Collection', 'resq-clean-pro' ),
				'headline'    => __( 'Botanical care for everyday skin comfort.', 'resq-clean-pro' ),
				'subcopy'     => __( 'Built around raw Manuka honey and pure aloe vera, our collections work with your skin’s natural 5.5 pH balance to soothe the feel of irritation, support lasting moisture, and care for your skin barrier — without synthetic fragrance or fillers.', 'resq-clean-pro' ),
				'primary_cta' => array(
					'label' => __( 'Shop all human skincare', 'resq-clean-pro' ),
					'path'  => 'therapeutic-skin-care/',
				),
				'image'       => __( 'The Daily Radiance Routine', 'resq-clean-pro' ),
				'image_alt'   => __( 'A premium flat-lay of women’s skincare beside raw honeycomb and fresh aloe.', 'resq-clean-pro' ),
				'image_slug'  => 'womens-skincare-bathroom-lifestyle',
				'segments'    => array(
					array(
						'label'         => __( 'Women’s Skincare', 'resq-clean-pro' ),
						'description'   => __( 'Everyday care to balance the look of tone, gently clear impurities, and nourish for a smoother, hydrated appearance.', 'resq-clean-pro' ),
						'image'         => __( 'The Daily Radiance Routine', 'resq-clean-pro' ),
						'image_slug'    => 'womens-line-complete-collection',
						'image_variant' => '',
						'cta_label'     => __( 'Explore women’s lines', 'resq-clean-pro' ),
						'path'          => 'womens-skincare/',
					),
					array(
						'label'         => __( 'Men’s Grooming', 'resq-clean-pro' ),
						'description'   => __( 'Purposeful, stripped-down skincare to support post-shave comfort, hydrate coarse skin, and care for your barrier.', 'resq-clean-pro' ),
						'image'         => __( 'Precision Grooming Trio', 'resq-clean-pro' ),
						'image_slug'    => 'mens-line-complete-collection',
						'image_variant' => 'resq-img-placeholder--dark',
						'cta_label'     => __( 'Explore men’s lines', 'resq-clean-pro' ),
						'path'          => 'mens-grooming/',
					),
					array(
						'label'         => __( 'Baby & Infant Care', 'resq-clean-pro' ),
						'description'   => __( 'Hyper-gentle, fragrance-free formulas made for delicate skin; patch test and follow label directions.', 'resq-clean-pro' ),
						'image'         => __( 'Pure Comfort Bath', 'resq-clean-pro' ),
						'image_slug'    => 'baby-skin-treatment-lifestyle',
						'image_variant' => '',
						'cta_label'     => __( 'Explore baby care', 'resq-clean-pro' ),
						'path'          => 'baby-infant-care/',
					),
				),
			),

			'pet' => array(
				'eyebrow'     => __( 'ResQ Organics — Pet Care', 'resq-clean-pro' ),
				'headline'    => __( 'Gentle topical care for your companions.', 'resq-clean-pro' ),
				'subcopy'     => __( 'Clean, non-toxic comfort for the animals you love. Our lick-safe, fragrance-free topical formulas help soothe the look and feel of hot spots, dry paws, and sensitive skin as part of a gentle care routine.', 'resq-clean-pro' ),
				'primary_cta' => array(
					'label' => __( 'Shop all pet care', 'resq-clean-pro' ),
					'path'  => 'topical-skin-care/',
				),
				'image'       => __( 'Soothed & Calm Companion', 'resq-clean-pro' ),
				'image_alt'   => __( 'A close-up of a relaxed dog with a soft, glossy-looking coat.', 'resq-clean-pro' ),
				'image_slug'  => 'dog-skin-care-gentle-effective-lifestyle',
				'image_variant' => 'resq-img-placeholder--pet',
				'segments'    => array(
					array(
						'label'         => __( 'Topical Skin Care', 'resq-clean-pro' ),
						'description'   => __( 'Gentle relief for the look and feel of raw hot spots, itchy patches, and irritated skin.', 'resq-clean-pro' ),
						'image'         => __( 'Soothed & Calm Companion', 'resq-clean-pro' ),
						'image_slug'    => 'pet-topical-care-hyperkeratosis-lifestyle',
						'image_variant' => 'resq-img-placeholder--pet',
						'cta_label'     => __( 'Explore topical relief', 'resq-clean-pro' ),
						'path'          => 'topical-skin-care/',
					),
					array(
						'label'         => __( 'Coat & Grooming', 'resq-clean-pro' ),
						'description'   => __( 'Manuka-infused shampoos and conditioners that leave coats soft, shiny, and tangle-free.', 'resq-clean-pro' ),
						'image'         => __( 'Premium Coat Wash', 'resq-clean-pro' ),
						'image_slug'    => 'dog-skin-care-gentle-effective-lifestyle',
						'image_variant' => '',
						'cta_label'     => __( 'Explore coat care', 'resq-clean-pro' ),
						'path'          => 'coat-grooming/',
					),
					array(
						'label'         => __( 'Treats & Dietary Care', 'resq-clean-pro' ),
						'description'   => __( 'Low-glycemic, pure-ingredient treats made for dogs on special dietary routines.', 'resq-clean-pro' ),
						'image'         => __( 'Wholesome Treats', 'resq-clean-pro' ),
						'image_slug'    => 'diabetic-dog-treats-2',
						'image_variant' => 'resq-img-placeholder--pet',
						'cta_label'     => __( 'Explore dietary care', 'resq-clean-pro' ),
						'path'          => 'treats-diabetic-care/',
					),
				),
				'mission'     => array(
					'badge'     => __( 'Giving back', 'resq-clean-pro' ),
					'headline'  => __( 'The ResQ rescue pledge.', 'resq-clean-pro' ),
					'body'      => __( 'A portion of every pet-care purchase supports animal shelters and rescue groups nationwide — helping rescued animals get the gentle care they need.', 'resq-clean-pro' ),
					'cta_label' => __( 'See our rescue partners', 'resq-clean-pro' ),
				),
			),

			'bundles' => array(
				'eyebrow'      => __( 'Routine kits', 'resq-clean-pro' ),
				'headline'     => __( 'Complete household routines. Built-in savings.', 'resq-clean-pro' ),
				'subcopy'      => __( 'Group corresponding step-by-step products into one routine and save versus buying each on its own. Every bundle’s savings reflect live pricing shown at checkout.', 'resq-clean-pro' ),
				'primary_cta'  => array(
					'label' => __( 'View savings kits', 'resq-clean-pro' ),
					'path'  => '#resq-gateway-shelf-heading',
				),
				'image'        => __( 'The Shared Cabinet Lifestyle', 'resq-clean-pro' ),
				'image_alt'    => __( 'A styled shelf of grouped ResQ human and pet care routines.', 'resq-clean-pro' ),
				'image_slug'   => 'mens-line-complete-collection',
				'value_points' => array(
					array(
						'title' => __( 'Grouped routines', 'resq-clean-pro' ),
						'body'  => __( 'Cleanse, nourish, and restore steps curated to work together.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Save versus standalone', 'resq-clean-pro' ),
						'body'  => __( 'Kit pricing is lower than buying each product on its own.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'One simple checkout', 'resq-clean-pro' ),
						'body'  => __( 'Add a full routine to your cart in a single step.', 'resq-clean-pro' ),
					),
				),
			),

			'cbd' => array(
				'eyebrow'     => __( 'Isolated wellness collection', 'resq-clean-pro' ),
				'headline'    => __( 'High-purity botanical balance, isolated for transparency.', 'resq-clean-pro' ),
				'subcopy'     => __( 'A separate, age-gated collection of wellness drops, gummies, and topicals — formulated in a regulated environment and kept fully isolated from our standard storefront.', 'resq-clean-pro' ),
				'primary_cta' => array(
					'label' => __( 'View collection', 'resq-clean-pro' ),
					'path'  => '#resq-gateway-shelf-heading',
				),
				'image'       => __( 'The Botanical Wellness Ritual', 'resq-clean-pro' ),
				'image_alt'   => __( 'Studio photograph of isolated wellness drops on a clean surface.', 'resq-clean-pro' ),
				'image_slug'  => 'full-spectrum-cbd-oil-2',
				'purity'      => array(
					array(
						'title' => __( 'Third-party tested', 'resq-clean-pro' ),
						'body'  => __( 'Batch Certificates of Analysis available for transparency.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Low / zero-THC thresholds', 'resq-clean-pro' ),
						'body'  => __( 'Verified trace-extraction isolation per published lab results.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Clean oil carriers', 'resq-clean-pro' ),
						'body'  => __( 'Infused into organic coconut and hemp-seed oil bases.', 'resq-clean-pro' ),
					),
				),
			),
		);

		$content = $map[ $slug ] ?? array();

		/**
		 * Filter the landing content map for a gateway slug.
		 *
		 * @param array<string, mixed> $content Resolved content array.
		 * @param string               $slug    Gateway slug.
		 */
		return apply_filters( 'resq_theme_landing_content', $content, $slug );
	}
}
