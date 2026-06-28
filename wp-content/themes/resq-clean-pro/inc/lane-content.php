<?php
/**
 * Target Problem Lane & category landing copy map.
 *
 * Keyed by copy_key from resq_get_lane_registry(). All copy is DRAFT
 * presentation copy (comfort/cosmetic language) per docs/05 and docs/24.
 * Not owner/legal-approved production copy.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_theme_get_lane_content' ) ) {
	/**
	 * Return lane marketing copy for a copy_key.
	 *
	 * @param string $copy_key Registry copy_key.
	 * @return array<string, mixed>
	 */
	function resq_theme_get_lane_content( string $copy_key ): array {
		$copy_key = sanitize_key( str_replace( array( '/', ' ' ), '-', $copy_key ) );

		$map = array(
			// ── Human categories ─────────────────────────────────────
			'human-womens-skincare' => array(
				'eyebrow'   => __( 'Women’s skincare', 'resq-clean-pro' ),
				'headline'  => __( 'Everyday radiance, gently restored.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Gentle washes, clarifying scrubs, and restorative serums for a smoother, hydrated appearance — without synthetic fragrance.', 'resq-clean-pro' ),
				'image'     => __( 'The Daily Radiance Routine', 'resq-clean-pro' ),
				'image_alt' => __( 'Premium women’s skincare flat-lay beside raw honeycomb.', 'resq-clean-pro' ),
			),
			'human-mens-grooming' => array(
				'eyebrow'   => __( 'Men’s grooming', 'resq-clean-pro' ),
				'headline'  => __( 'Purposeful care for active routines.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Multi-use formulas engineered for post-shave comfort, coarse-skin hydration, and a clean barrier feel — without greasy residue.', 'resq-clean-pro' ),
				'image'     => __( 'Precision Grooming Trio', 'resq-clean-pro' ),
				'image_alt' => __( 'Men’s grooming bottles on dark slate.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--dark',
			),
			'human-therapeutic-skin-care' => array(
				'eyebrow'   => __( 'Intensive skin care', 'resq-clean-pro' ),
				'headline'  => __( 'Focused comfort for dry, sensitive-feeling skin.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Non-greasy, fragrance-free formulas made to support lasting moisture and everyday skin comfort as part of a gentle care routine.', 'resq-clean-pro' ),
				'image'     => __( 'Comfort Care Grid', 'resq-clean-pro' ),
				'image_alt' => __( 'White cream smoothing over dry, rough skin texture.', 'resq-clean-pro' ),
				'cta_label' => __( 'Shop all-in-one skin cream', 'resq-clean-pro' ),
			),
			'human-hair-scalp-care' => array(
				'eyebrow'   => __( 'Hair & scalp care', 'resq-clean-pro' ),
				'headline'  => __( 'Manuka-powered hair and scalp routines.', 'resq-clean-pro' ),
				'subcopy'   => __( 'pH-balanced shampoos and conditioners that cleanse buildup while supporting a comfortable, balanced-looking scalp and softer strands.', 'resq-clean-pro' ),
				'image'     => __( 'Premium Hair Duo', 'resq-clean-pro' ),
				'image_alt' => __( 'Manuka honey shampoo and conditioner bottles.', 'resq-clean-pro' ),
			),
			'human-baby-infant-care' => array(
				'eyebrow'   => __( 'Baby & infant care', 'resq-clean-pro' ),
				'headline'  => __( 'Gentle comfort for delicate skin.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Fragrance-free bath and skin essentials made for delicate skin. Patch test and follow label directions.', 'resq-clean-pro' ),
				'image'     => __( 'Pure Comfort Bath', 'resq-clean-pro' ),
				'image_alt' => __( 'Parent gently applying cream to infant skin.', 'resq-clean-pro' ),
			),
			'human-cbd-wellness' => array(
				'eyebrow'   => __( 'Isolated wellness collection', 'resq-clean-pro' ),
				'headline'  => __( 'High-purity botanical balance, isolated for transparency.', 'resq-clean-pro' ),
				'subcopy'   => __( 'A separate, age-gated collection kept fully isolated from our standard storefront. DRAFT — compliance review required.', 'resq-clean-pro' ),
				'image'     => __( 'CBD Collection', 'resq-clean-pro' ),
				'image_alt' => __( 'Isolated CBD wellness products.', 'resq-clean-pro' ),
			),
			// ── Pet categories ─────────────────────────────────────────
			'pet-topical-skin-care' => array(
				'eyebrow'   => __( 'Topical skin care', 'resq-clean-pro' ),
				'headline'  => __( 'Gentle topical comfort for your companions.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Lick-safe, fragrance-free formulas to help soothe the look and feel of hot spots, dry paws, and sensitive skin as part of a gentle care routine.', 'resq-clean-pro' ),
				'image'     => __( 'Soothed & Calm Companion', 'resq-clean-pro' ),
				'image_alt' => __( 'Dog resting with comfortable-looking paw pads.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
			),
			'pet-coat-grooming' => array(
				'eyebrow'   => __( 'Coat & grooming', 'resq-clean-pro' ),
				'headline'  => __( 'Manuka-infused coat care.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Shampoos and conditioners that leave coats soft, shiny, and easier to manage — without harsh soaping agents.', 'resq-clean-pro' ),
				'image'     => __( 'Premium Coat Wash', 'resq-clean-pro' ),
				'image_alt' => __( 'Freshly groomed dog with a shiny coat.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
			),
			'pet-treats-diabetic-care' => array(
				'eyebrow'   => __( 'Treats & dietary care', 'resq-clean-pro' ),
				'headline'  => __( 'Clean treats for special dietary routines.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Low-glycemic, pure-ingredient rewards made for dogs on special dietary routines. DRAFT — medical-adjacent review required.', 'resq-clean-pro' ),
				'image'     => __( 'Wholesome Treats', 'resq-clean-pro' ),
				'image_alt' => __( 'Low-glycemic dog treats in a natural setting.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
			),
			'pet-cbd-wellness' => array(
				'eyebrow'   => __( 'Pet CBD & calming', 'resq-clean-pro' ),
				'headline'  => __( 'Isolated pet wellness collection.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Pet-specific drops and calming formats kept fully isolated from standard pet care. DRAFT — compliance review required.', 'resq-clean-pro' ),
				'image'     => __( 'Pet CBD Collection', 'resq-clean-pro' ),
				'image_alt' => __( 'Isolated pet CBD products.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
			),
			'pet-horse-care' => array(
				'eyebrow'   => __( 'Horse & large-animal care', 'resq-clean-pro' ),
				'headline'  => __( 'Gentle grooming for pasture companions.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Manuka-infused cleansers made to soften mud and crust for grooming, remove pasture debris, and support a gentle lower-leg wash routine.', 'resq-clean-pro' ),
				'image'     => __( 'Pasture Grooming Ritual', 'resq-clean-pro' ),
				'image_alt' => __( 'Horse lower legs being gently washed after pasture time.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
			),
			// ── Problem lanes ────────────────────────────────────────
			'human-mens-grooming-razor-burn' => array(
				'eyebrow'   => __( 'Post-shave comfort', 'resq-clean-pro' ),
				'headline'  => __( 'Support comfort after every shave.', 'resq-clean-pro' ),
				'subcopy'   => __( 'A lightweight moisturizer made to support post-shave comfort, cooling feel, and everyday hydration — with a zero-grease finish.', 'resq-clean-pro' ),
				'image'     => __( 'Post-Shave Comfort Care', 'resq-clean-pro' ),
				'image_alt' => __( 'Grooming bottles on wet slate after shaving.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--dark',
				'cta_label' => __( 'Shop men’s moisturizer', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Cooling feel', 'resq-clean-pro' ),
						'body'  => __( 'Lightweight hydration that feels cool and refreshing after shaving.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Zero-grease finish', 'resq-clean-pro' ),
						'body'  => __( 'Absorbs quickly so skin feels comfortable, not slick.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Routine support', 'resq-clean-pro' ),
						'body'  => __( 'Pairs with your daily cleanse-and-shave routine.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Individual results vary. Comfort-focused routine support only.', 'resq-clean-pro' ),
				),
			),
			'human-baby-infant-care-diaper-rash' => array(
				'eyebrow'   => __( 'Diaper-area comfort', 'resq-clean-pro' ),
				'headline'  => __( 'Gentle support for diaper-area skin.', 'resq-clean-pro' ),
				'subcopy'   => __( 'A fragrance-free cream made for delicate diaper-area comfort and chafing friction support. Patch test and follow label directions.', 'resq-clean-pro' ),
				'image'     => __( 'Gentle Barrier Care', 'resq-clean-pro' ),
				'image_alt' => __( 'Parent gently applying protective cream to delicate infant skin.', 'resq-clean-pro' ),
				'cta_label' => __( 'Shop baby skin cream', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Gentle comfort', 'resq-clean-pro' ),
						'body'  => __( 'Made for delicate skin with a gentle-use intent.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Chafing support', 'resq-clean-pro' ),
						'body'  => __( 'Helps support everyday diaper-area comfort as part of a routine.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Fragrance-free', 'resq-clean-pro' ),
						'body'  => __( 'No synthetic fragrance — just clean botanical care.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Baby/infant copy requires legal review before publish. Not a drug or treatment claim.', 'resq-clean-pro' ),
				),
			),
			'human-hair-scalp-care-post-color-irritation' => array(
				'eyebrow'   => __( 'Post-color scalp comfort', 'resq-clean-pro' ),
				'headline'  => __( 'Comfort-focused cleansing after color treatments.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Manuka honey shampoo made to remove buildup and support a comfortable, balanced-looking scalp feel with color-safe cleansing.', 'resq-clean-pro' ),
				'image'     => __( 'Scalp Comfort Wash', 'resq-clean-pro' ),
				'image_alt' => __( 'Shampoo lather on wet hair in a salon setting.', 'resq-clean-pro' ),
				'cta_label' => __( 'Shop Manuka shampoo', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Buildup removal', 'resq-clean-pro' ),
						'body'  => __( 'Gently cleanses residue and everyday buildup from hair and scalp.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Dry-scalp comfort feel', 'resq-clean-pro' ),
						'body'  => __( 'Supports a comfortable, balanced-looking scalp feel.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Color-safe cleanse', 'resq-clean-pro' ),
						'body'  => __( 'Made for routine washing without harsh stripping agents.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Cosmetic comfort language only — not a scalp disease treatment.', 'resq-clean-pro' ),
				),
			),
			'pet-topical-skin-care-hyperkeratosis' => array(
				'eyebrow'   => __( 'Rough nose & paw calluses', 'resq-clean-pro' ),
				'headline'  => __( 'Softening comfort for rough nose and paw calluses.', 'resq-clean-pro' ),
				'subcopy'   => __( 'A lick-safe topical cream made to soften the look and feel of rough nose and paw calluses and support crack comfort as part of regular care.', 'resq-clean-pro' ),
				'image'     => __( 'The Snout & Paw Ritual', 'resq-clean-pro' ),
				'image_alt' => __( 'Dog with comfortable-looking nose and paw pads.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
				'cta_label' => __( 'Shop pet skin cream', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Callus softening', 'resq-clean-pro' ),
						'body'  => __( 'Helps soften the look and feel of rough nose and paw calluses.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Crack comfort', 'resq-clean-pro' ),
						'body'  => __( 'Supports moisture barrier comfort for dry, cracked areas.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Lick-safe care', 'resq-clean-pro' ),
						'body'  => __( 'Botanical topical made for everyday companion care routines.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Consult your veterinarian for persistent symptoms. Not a veterinary treatment substitute.', 'resq-clean-pro' ),
				),
			),
			'human-mens-grooming-beard-and-odor-control' => array(
				'eyebrow'   => __( 'Beard & cleansing routine', 'resq-clean-pro' ),
				'headline'  => __( 'Cleanse, soften, and freshen after activity.', 'resq-clean-pro' ),
				'subcopy'   => __( 'A dual-use face and body wash made to cleanse sweat and oil, soften beard hair, and support shave prep with a fresh, comfortable feel.', 'resq-clean-pro' ),
				'image'     => __( 'Active Grooming Wash', 'resq-clean-pro' ),
				'image_alt' => __( 'Men’s wash lathered in hands before shaving.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--dark',
				'cta_label' => __( 'Shop men’s wash', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Deep cleanse', 'resq-clean-pro' ),
						'body'  => __( 'Removes sweat, oil, and surface buildup from skin and beard.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Beard softening', 'resq-clean-pro' ),
						'body'  => __( 'Helps beard hair feel softer and easier to manage.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Shave prep', 'resq-clean-pro' ),
						'body'  => __( 'Dual-use formula supports your shave-and-go routine.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Cosmetic cleansing language only — not an antimicrobial or drug claim.', 'resq-clean-pro' ),
				),
			),
			'human-womens-skincare-makeup-removal-detox' => array(
				'eyebrow'   => __( 'Makeup removal & cleanse', 'resq-clean-pro' ),
				'headline'  => __( 'Gentle cleansing for makeup and surface impurities.', 'resq-clean-pro' ),
				'subcopy'   => __( 'A restoring wash made to gently remove makeup, cleanse surface impurities, and leave skin with a moisture-balanced clean feel.', 'resq-clean-pro' ),
				'image'     => __( 'The Evening Cleanse', 'resq-clean-pro' ),
				'image_alt' => __( 'Creamy cleanser removing makeup at a vanity.', 'resq-clean-pro' ),
				'cta_label' => __( 'Shop restoring wash', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Makeup removal', 'resq-clean-pro' ),
						'body'  => __( 'Gently lifts makeup as part of your evening routine.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Surface cleanse', 'resq-clean-pro' ),
						'body'  => __( 'Helps wash away pollutant and dust residue from the day.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Balanced feel', 'resq-clean-pro' ),
						'body'  => __( 'Leaves skin feeling clean without a tight, stripped feel.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Cosmetic cleansing only — not a medical detoxification claim.', 'resq-clean-pro' ),
				),
			),
			'human-womens-skincare-exfoliation-texture' => array(
				'eyebrow'   => __( 'Texture & polishing', 'resq-clean-pro' ),
				'headline'  => __( 'Physical polishing for smoother-looking texture.', 'resq-clean-pro' ),
				'subcopy'   => __( 'A microdermabrasion-style scrub made to soften rough patches and support a smoother-looking texture on elbows, knees, and face.', 'resq-clean-pro' ),
				'image'     => __( 'Texture Refinement Scrub', 'resq-clean-pro' ),
				'image_alt' => __( 'Fine scrub granules on fingertips.', 'resq-clean-pro' ),
				'cta_label' => __( 'Shop microdermabrasion scrub', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Physical polish', 'resq-clean-pro' ),
						'body'  => __( 'Gently buffs away rough-feeling surface texture.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Rough-patch softening', 'resq-clean-pro' ),
						'body'  => __( 'Targets elbows, knees, and other uneven-feeling areas.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Smoother look', 'resq-clean-pro' ),
						'body'  => __( 'Supports a smoother-looking appearance with regular use.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Cosmetic texture support only — not a dermatological treatment.', 'resq-clean-pro' ),
				),
			),
			'human-womens-skincare-overnight-repair' => array(
				'eyebrow'   => __( 'Overnight appearance support', 'resq-clean-pro' ),
				'headline'  => __( 'Evening cosmetic support while you rest.', 'resq-clean-pro' ),
				'subcopy'   => __( 'An age-defying night serum made to support the appearance of tone uniformity, fine-line look refinement, and overnight hydration feel.', 'resq-clean-pro' ),
				'image'     => __( 'Night Restore Serum', 'resq-clean-pro' ),
				'image_alt' => __( 'Serum dropper beside a bedside lamp.', 'resq-clean-pro' ),
				'cta_label' => __( 'Shop night serum', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Evening hydration feel', 'resq-clean-pro' ),
						'body'  => __( 'Lightweight serum supports overnight moisture feel.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Tone appearance', 'resq-clean-pro' ),
						'body'  => __( 'Made to support the look of more uniform tone over time.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Fine-line look', 'resq-clean-pro' ),
						'body'  => __( 'Cosmetic support for the appearance of fine lines.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Cosmetic appearance language only — not an anti-wrinkle drug claim.', 'resq-clean-pro' ),
				),
			),
			'human-hair-scalp-care-split-end-defense' => array(
				'eyebrow'   => __( 'Split-end smoothing', 'resq-clean-pro' ),
				'headline'  => __( 'Cuticle smoothing and detangling slip.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Manuka honey conditioner made to smooth the cuticle, improve detangling slip, reduce heat-styling stress, and seal in moisture for added shine.', 'resq-clean-pro' ),
				'image'     => __( 'Moisture Seal Conditioner', 'resq-clean-pro' ),
				'image_alt' => __( 'Conditioner combed through wet hair ends.', 'resq-clean-pro' ),
				'cta_label' => __( 'Shop Manuka conditioner', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Cuticle smoothing', 'resq-clean-pro' ),
						'body'  => __( 'Helps strands feel smoother along the hair shaft.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Detangling slip', 'resq-clean-pro' ),
						'body'  => __( 'Adds glide to reduce tugging during comb-through.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Heat-styling support', 'resq-clean-pro' ),
						'body'  => __( 'Seals moisture to help reduce the look of styling stress.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Cosmetic hair care only — not a structural hair restoration claim.', 'resq-clean-pro' ),
				),
			),
			'pet-topical-skin-care-feline-dermatitis' => array(
				'eyebrow'   => __( 'Chin & fold comfort', 'resq-clean-pro' ),
				'headline'  => __( 'Gentle comfort for chin and muzzle folds.', 'resq-clean-pro' ),
				'subcopy'   => __( 'A lick-safe topical cream made for chin and muzzle fold comfort as part of a gentle, non-toxic botanical care routine.', 'resq-clean-pro' ),
				'image'     => __( 'Feline Fold Comfort', 'resq-clean-pro' ),
				'image_alt' => __( 'Cat receiving gentle cream application on chin folds.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
				'cta_label' => __( 'Shop pet skin cream', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Fold comfort', 'resq-clean-pro' ),
						'body'  => __( 'Supports the look and feel of chin and muzzle fold areas.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Gentle botanical care', 'resq-clean-pro' ),
						'body'  => __( 'Non-toxic formula made for everyday companion routines.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Lick-safe application', 'resq-clean-pro' ),
						'body'  => __( 'Suitable for areas your cat may groom.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Consult your veterinarian for persistent symptoms. Not a veterinary treatment for diagnosed dermatitis.', 'resq-clean-pro' ),
				),
			),
			'pet-coat-grooming-seasonal-grass-itch' => array(
				'eyebrow'   => __( 'Seasonal coat wash', 'resq-clean-pro' ),
				'headline'  => __( 'Wash away pollen, grass, and outdoor debris.', 'resq-clean-pro' ),
				'subcopy'   => __( 'An all-natural Manuka honey pet shampoo made to wash away pollen, grass, and debris while delivering a cooling feel on an irritated-looking coat.', 'resq-clean-pro' ),
				'image'     => __( 'Outdoor Rinse Ritual', 'resq-clean-pro' ),
				'image_alt' => __( 'Dog being rinsed after outdoor play.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
				'cta_label' => __( 'Shop pet shampoo', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Debris removal', 'resq-clean-pro' ),
						'body'  => __( 'Gently washes away pollen, grass, and outdoor buildup.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Cooling feel', 'resq-clean-pro' ),
						'body'  => __( 'Supports a cooling feel on coat during seasonal routines.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Gentle cleanse', 'resq-clean-pro' ),
						'body'  => __( 'Made without harsh soaping agents.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Not a cure for allergies or systemic immune response. Consult your veterinarian when symptoms persist.', 'resq-clean-pro' ),
				),
			),
			'pet-coat-grooming-undercoat-friction-static' => array(
				'eyebrow'   => __( 'Mat & static glide', 'resq-clean-pro' ),
				'headline'  => __( 'Glide through mats, tangles, and static.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Manuka honey pet conditioner made to improve mat and tangle glide, reduce static, seal cuticle moisture, and support deshedding slip.', 'resq-clean-pro' ),
				'image'     => __( 'Detangle & Glide', 'resq-clean-pro' ),
				'image_alt' => __( 'Brush gliding through a conditioned coat.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
				'cta_label' => __( 'Shop pet conditioner', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Mat & tangle glide', 'resq-clean-pro' ),
						'body'  => __( 'Adds slip so brushing feels easier on thick coats.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Static reduction', 'resq-clean-pro' ),
						'body'  => __( 'Helps reduce static for a smoother-feeling coat.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Cuticle moisture', 'resq-clean-pro' ),
						'body'  => __( 'Seals moisture along the hair shaft for softer feel.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Grooming comfort only — not a parasite or anti-fungal treatment claim.', 'resq-clean-pro' ),
				),
			),
			'pet-horse-care-mud-fever-support' => array(
				'eyebrow'   => __( 'Mud & pasture debris cleanse', 'resq-clean-pro' ),
				'headline'  => __( 'Gentle lower-leg wash after pasture time.', 'resq-clean-pro' ),
				'subcopy'   => __( 'All-natural Manuka honey pet shampoo made to soften mud and crust for grooming, remove pasture debris, and support a gentle lower-leg cleanse routine.', 'resq-clean-pro' ),
				'image'     => __( 'Lower-Leg Grooming Wash', 'resq-clean-pro' ),
				'image_alt' => __( 'Horse lower legs being gently washed.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
				'cta_label' => __( 'Shop pet shampoo', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array(
						'title' => __( 'Mud softening', 'resq-clean-pro' ),
						'body'  => __( 'Helps soften mud and crust for easier grooming.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Debris removal', 'resq-clean-pro' ),
						'body'  => __( 'Washes away pasture debris from lower legs.', 'resq-clean-pro' ),
					),
					array(
						'title' => __( 'Gentle cleanse', 'resq-clean-pro' ),
						'body'  => __( 'Botanical formula for routine large-animal care.', 'resq-clean-pro' ),
					),
				),
				'proof' => array(
					'enabled' => false,
					'disclaimer' => __( 'Grooming support only — not a cure for pastern dermatitis or mud fever. Consult your veterinarian for persistent leg conditions.', 'resq-clean-pro' ),
				),
			),
			// ── Expansion matrix stubs (briefs pending — docs/05) ────────
			'human-therapeutic-skin-care-skin-chafing' => array(
				'eyebrow'   => __( 'Skin chafing comfort', 'resq-clean-pro' ),
				'headline'  => __( 'Friction comfort for chafed-feeling skin.', 'resq-clean-pro' ),
				'subcopy'   => __( 'All-in-one intensive cream made to support everyday comfort where skin rubs or feels irritated from friction — as part of a gentle care routine.', 'resq-clean-pro' ),
				'image'     => __( 'Friction Comfort Care', 'resq-clean-pro' ),
				'image_alt' => __( 'Cream applied to an area of chafed-feeling skin.', 'resq-clean-pro' ),
				'cta_label' => __( 'Shop all-in-one skin cream', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array( 'title' => __( 'Friction support', 'resq-clean-pro' ), 'body' => __( 'Helps support comfort where skin experiences rubbing.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Lasting moisture', 'resq-clean-pro' ), 'body' => __( 'Made to support hydrated-feeling skin throughout the day.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Gentle routine', 'resq-clean-pro' ), 'body' => __( 'Fragrance-free formula for everyday use.', 'resq-clean-pro' ) ),
				),
				'proof' => array( 'enabled' => false, 'disclaimer' => __( 'Expansion lane — brief pending. Not a disease treatment claim.', 'resq-clean-pro' ) ),
			),
			'human-baby-infant-care-cradle-cap-comfort' => array(
				'eyebrow'   => __( 'Cradle cap comfort', 'resq-clean-pro' ),
				'headline'  => __( 'Gentle scalp comfort for delicate skin.', 'resq-clean-pro' ),
				'subcopy'   => __( 'A fragrance-free cream made to support gentle scalp and skin comfort as part of a baby care routine. Patch test and follow label directions.', 'resq-clean-pro' ),
				'image'     => __( 'Gentle Scalp Comfort', 'resq-clean-pro' ),
				'image_alt' => __( 'Parent gently caring for baby scalp.', 'resq-clean-pro' ),
				'cta_label' => __( 'Shop intensive skin cream', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array( 'title' => __( 'Delicate skin', 'resq-clean-pro' ), 'body' => __( 'Made for gentle-use intent on infant skin.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Scalp comfort', 'resq-clean-pro' ), 'body' => __( 'Supports everyday scalp-area comfort as part of a routine.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Fragrance-free', 'resq-clean-pro' ), 'body' => __( 'No synthetic fragrance added.', 'resq-clean-pro' ) ),
				),
				'proof' => array( 'enabled' => false, 'disclaimer' => __( 'Baby lane — legal review required. Expansion brief pending.', 'resq-clean-pro' ) ),
			),
			'human-mens-grooming-shaving-bumps' => array(
				'eyebrow'   => __( 'Shaving bump comfort', 'resq-clean-pro' ),
				'headline'  => __( 'Post-shave comfort for bump-prone areas.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Lightweight moisturizer made to support post-shave comfort and everyday hydration where skin feels irritated after shaving.', 'resq-clean-pro' ),
				'image'     => __( 'Bump-Prone Shave Care', 'resq-clean-pro' ),
				'image_alt' => __( 'Moisturizer applied after shaving.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--dark',
				'cta_label' => __( 'Shop men’s moisturizer', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array( 'title' => __( 'Post-shave feel', 'resq-clean-pro' ), 'body' => __( 'Supports comfort after shaving.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Lightweight hydration', 'resq-clean-pro' ), 'body' => __( 'Absorbs without a greasy finish.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'CBD excluded', 'resq-clean-pro' ), 'body' => __( 'Standard grooming lane — no CBD cross-sell.', 'resq-clean-pro' ) ),
				),
				'proof' => array( 'enabled' => false, 'disclaimer' => __( 'Not a treatment for pseudofolliculitis or ingrown hairs. Expansion brief pending.', 'resq-clean-pro' ) ),
			),
			'human-hair-scalp-care-excess-sebum-control' => array(
				'eyebrow'   => __( 'Excess sebum balance', 'resq-clean-pro' ),
				'headline'  => __( 'Cleanse buildup for a balanced-looking scalp.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Manuka honey shampoo made to remove buildup and support the appearance of balanced sebum and oil on scalp and hair.', 'resq-clean-pro' ),
				'image'     => __( 'Scalp Balance Wash', 'resq-clean-pro' ),
				'image_alt' => __( 'Shampoo lather on oily-prone scalp.', 'resq-clean-pro' ),
				'cta_label' => __( 'Shop Manuka shampoo', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array( 'title' => __( 'Buildup removal', 'resq-clean-pro' ), 'body' => __( 'Gently cleanses residue from hair and scalp.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Oil balance appearance', 'resq-clean-pro' ), 'body' => __( 'Supports a balanced-looking scalp feel.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Routine cleanse', 'resq-clean-pro' ), 'body' => __( 'Color-safe, sulfate-free formula.', 'resq-clean-pro' ) ),
				),
				'proof' => array( 'enabled' => false, 'disclaimer' => __( 'Cosmetic scalp care only — not a scalp disease treatment. Expansion brief pending.', 'resq-clean-pro' ) ),
			),
			'pet-topical-skin-care-senior-dog-elbow-calluses' => array(
				'eyebrow'   => __( 'Senior elbow calluses', 'resq-clean-pro' ),
				'headline'  => __( 'Comfort for rough senior elbow calluses.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Lick-safe topical cream made to soften the look and feel of rough elbow calluses on senior dogs as part of regular care.', 'resq-clean-pro' ),
				'image'     => __( 'Senior Elbow Comfort', 'resq-clean-pro' ),
				'image_alt' => __( 'Senior dog with cream applied to elbow callus.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
				'cta_label' => __( 'Shop pet skin cream', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array( 'title' => __( 'Callus softening', 'resq-clean-pro' ), 'body' => __( 'Helps soften rough-feeling elbow calluses.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Senior comfort', 'resq-clean-pro' ), 'body' => __( 'Made for everyday companion care routines.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Lick-safe', 'resq-clean-pro' ), 'body' => __( 'Botanical topical for areas pets may groom.', 'resq-clean-pro' ) ),
				),
				'proof' => array( 'enabled' => false, 'disclaimer' => __( 'Consult your veterinarian for persistent symptoms. Expansion brief pending.', 'resq-clean-pro' ) ),
			),
			'pet-topical-skin-care-skin-fold-dermatitis' => array(
				'eyebrow'   => __( 'Skin fold comfort', 'resq-clean-pro' ),
				'headline'  => __( 'Gentle comfort for skin folds.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Lick-safe cream made to support the look and feel of skin fold areas as part of a gentle topical care routine.', 'resq-clean-pro' ),
				'image'     => __( 'Fold Area Comfort', 'resq-clean-pro' ),
				'image_alt' => __( 'Dog skin fold being gently cared for.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
				'cta_label' => __( 'Shop pet skin cream', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array( 'title' => __( 'Fold comfort', 'resq-clean-pro' ), 'body' => __( 'Supports everyday fold-area comfort.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Moisture barrier', 'resq-clean-pro' ), 'body' => __( 'Helps skin feel cared for in friction-prone areas.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Lick-safe care', 'resq-clean-pro' ), 'body' => __( 'Non-toxic botanical formula.', 'resq-clean-pro' ) ),
				),
				'proof' => array( 'enabled' => false, 'disclaimer' => __( 'Not a treatment for diagnosed dermatitis. Expansion brief pending.', 'resq-clean-pro' ) ),
			),
			'pet-treats-diabetic-care-diabetic-training-rewards' => array(
				'eyebrow'   => __( 'Training rewards routine', 'resq-clean-pro' ),
				'headline'  => __( 'Low-glycemic treats for training routines.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Clean, low-glycemic rewards made for dogs on special dietary routines — suitable for high-frequency training use when aligned with your vet’s guidance.', 'resq-clean-pro' ),
				'image'     => __( 'Training Reward Bites', 'resq-clean-pro' ),
				'image_alt' => __( 'Small dog treats for training sessions.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
				'cta_label' => __( 'Shop dietary treats', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array( 'title' => __( 'Low-glycemic', 'resq-clean-pro' ), 'body' => __( 'Made for dogs on special dietary routines.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Training-sized', 'resq-clean-pro' ), 'body' => __( 'Sized for frequent reward use during training.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Clean ingredients', 'resq-clean-pro' ), 'body' => __( 'Pure-ingredient recipe with no fillers listed on label.', 'resq-clean-pro' ) ),
				),
				'proof' => array( 'enabled' => false, 'disclaimer' => __( 'Medical-adjacent lane — no diagnostic diabetic claims. Legal review required.', 'resq-clean-pro' ) ),
			),
			'pet-treats-diabetic-care-weight-management-rewards' => array(
				'eyebrow'   => __( 'Weight-management rewards', 'resq-clean-pro' ),
				'headline'  => __( 'Portion-friendly treats for dietary routines.', 'resq-clean-pro' ),
				'subcopy'   => __( 'Low-glycemic treats made to fit weight-management and special dietary reward routines — follow your veterinarian’s portion guidance.', 'resq-clean-pro' ),
				'image'     => __( 'Portion-Control Treats', 'resq-clean-pro' ),
				'image_alt' => __( 'Measured treat portions beside a food scale.', 'resq-clean-pro' ),
				'image_variant' => 'resq-img-placeholder--pet',
				'cta_label' => __( 'Shop dietary treats', 'resq-clean-pro' ),
				'symptom_matrix' => array(
					array( 'title' => __( 'Routine rewards', 'resq-clean-pro' ), 'body' => __( 'Fits into a measured treat routine.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Low-glycemic', 'resq-clean-pro' ), 'body' => __( 'Made for special dietary routines.', 'resq-clean-pro' ) ),
					array( 'title' => __( 'Pet-only lane', 'resq-clean-pro' ), 'body' => __( 'Isolated from human checkout cross-sell.', 'resq-clean-pro' ) ),
				),
				'proof' => array( 'enabled' => false, 'disclaimer' => __( 'Not a weight-loss or medical treatment claim. Medical-adjacent review required.', 'resq-clean-pro' ) ),
			),
		);

		$image_slugs = array(
			'human-womens-skincare'                              => 'womens-skincare-bathroom-lifestyle',
			'human-mens-grooming'                                => 'mens-skin-care-cream-hero-splash',
			'human-therapeutic-skin-care'                        => 'ingredients-cream-honey-aloe-triptych',
			'human-hair-scalp-care'                              => 'womens-hair-loss-shampoo-conditioner-bundle',
			'human-baby-infant-care'                             => 'baby-skin-treatment-lifestyle',
			'human-cbd-wellness'                                 => 'full-spectrum-cbd-oil-2',
			'pet-topical-skin-care'                              => 'dog-skin-care-gentle-effective-lifestyle',
			'pet-coat-grooming'                                  => 'pet-topical-care-hyperkeratosis-lifestyle',
			'pet-treats-diabetic-care'                           => 'diabetic-dog-treats-2',
			'pet-cbd-wellness'                                   => 'cbd-for-pets-natural',
			'pet-horse-care'                                     => 'horse-skin-care-natural-healing-lifestyle',
			'human-mens-grooming-razor-burn'                     => 'mens-skin-care-cream-hero-splash',
			'human-baby-infant-care-diaper-rash'                 => 'baby-skin-treatment-lifestyle',
			'human-hair-scalp-care-post-color-irritation'        => 'womens-hair-loss-shampoo-conditioner-bundle',
			'pet-topical-skin-care-hyperkeratosis'               => 'pet-topical-care-hyperkeratosis-lifestyle',
			'human-mens-grooming-beard-and-odor-control'         => 'mens-face-body-wash',
			'human-womens-skincare-makeup-removal-detox'         => 'womens-face-body-wash',
			'human-womens-skincare-exfoliation-texture'          => 'womens-microderm-scrub',
			'human-womens-skincare-overnight-repair'             => 'womens-night-serum',
			'human-hair-scalp-care-split-end-defense'            => 'womens-hair-loss-prevention-conditioner',
			'pet-topical-skin-care-feline-dermatitis'            => 'cat-skin-care-gentle-effective-lifestyle',
			'pet-coat-grooming-seasonal-grass-itch'              => 'dog-skin-care-conditions-lifestyle',
			'pet-coat-grooming-undercoat-friction-static'        => 'dog-conditioner-manuka-honey-16oz',
			'pet-horse-care-mud-fever-support'                   => 'horse-skin-care-wound-treatment-lifestyle',
			'human-therapeutic-skin-care-skin-chafing'           => 'womens-ultimate-face-body-cream',
			'human-baby-infant-care-cradle-cap-comfort'          => 'baby-face-body-wash',
			'human-mens-grooming-shaving-bumps'                  => 'mens-face-body-moisturizer-2',
			'human-hair-scalp-care-excess-sebum-control'         => 'mens-hair-loss-prevention-shampoo',
			'pet-topical-skin-care-senior-dog-elbow-calluses'    => 'dog-skin-care-conditions-lifestyle',
			'pet-topical-skin-care-skin-fold-dermatitis'         => 'cat-skin-care-complete-relief-lifestyle',
			'pet-treats-diabetic-care-diabetic-training-rewards' => 'diabetic-dog-treats-2',
			'pet-treats-diabetic-care-weight-management-rewards' => 'diabetic-dog-treats-alt',
		);

		$content = $map[ $copy_key ] ?? array();
		if ( ! empty( $content ) && ! isset( $content['image_slug'] ) && isset( $image_slugs[ $copy_key ] ) ) {
			$content['image_slug'] = $image_slugs[ $copy_key ];
		}

		return apply_filters( 'resq_theme_lane_content', $content, $copy_key );
	}
}
