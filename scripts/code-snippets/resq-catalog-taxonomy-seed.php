<?php
/**
 * ResQ Catalog Taxonomy Seed — paste into Code Snippets (PHP, Admin only).
 *
 * Prerequisites: WooCommerce + resq-core active.
 *
 * How to run (pick one):
 * 1. WP Admin → Tools → ResQ Catalog Seed → "Run seed now"
 * 2. WP-CLI: wp eval-file wp-content/.../resq-catalog-taxonomy-seed.php (if copied to site)
 *
 * Safe to re-run: idempotent term creation (skips existing slugs).
 * Does NOT create products — terms and Woo category structure only.
 *
 * @package ResQ_Foundation_Kit
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ensure a taxonomy term exists; return term ID.
 *
 * @param string $slug     Term slug.
 * @param string $name     Display name.
 * @param string $taxonomy Taxonomy slug.
 * @param int    $parent   Parent term ID.
 * @param string $description Optional description.
 * @return int
 */
function resq_seed_ensure_term( string $slug, string $name, string $taxonomy, int $parent = 0, string $description = '' ): int {
	$slug = sanitize_title( $slug );
	if ( '' === $slug || ! taxonomy_exists( $taxonomy ) ) {
		return 0;
	}

	$existing = get_term_by( 'slug', $slug, $taxonomy );
	if ( $existing && ! is_wp_error( $existing ) ) {
		if ( $parent > 0 && (int) $existing->parent !== $parent ) {
			wp_update_term(
				(int) $existing->term_id,
				$taxonomy,
				array( 'parent' => $parent )
			);
		}
		return (int) $existing->term_id;
	}

	$args = array( 'slug' => $slug );
	if ( $parent > 0 ) {
		$args['parent'] = $parent;
	}
	if ( '' !== $description ) {
		$args['description'] = $description;
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
 * Run the full catalog term seed.
 *
 * @return array<string, int> Counts per group.
 */
function resq_seed_catalog_taxonomies(): array {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return array( 'error' => 0 );
	}

	$counts = array(
		'resq_audience'        => 0,
		'resq_concern'         => 0,
		'resq_ingredient'      => 0,
		'resq_product_role'    => 0,
		'resq_compliance_zone' => 0,
		'product_cat'          => 0,
	);

	// ── resq_audience ─────────────────────────────────────────────
	foreach (
		array(
			'human' => 'Human',
			'pet'   => 'Pet',
		) as $slug => $label
	) {
		if ( resq_seed_ensure_term( $slug, $label, 'resq_audience' ) ) {
			++$counts['resq_audience'];
		}
	}

	// ── resq_concern (hierarchical) ───────────────────────────────
	$human_skincare = resq_seed_ensure_term(
		'human-skincare',
		'Human Skincare',
		'resq_concern',
		0,
		'Problem-led discovery for human topical and wash routines.'
	);
	if ( $human_skincare ) {
		++$counts['resq_concern'];
	}

	foreach (
		array(
			'dry-skin'       => 'Dry Skin',
			'sensitive-skin' => 'Sensitive Skin',
			'scalp-care'     => 'Scalp Care',
			'anti-aging'     => 'Anti-Aging',
		) as $slug => $label
	) {
		if ( resq_seed_ensure_term( $slug, $label, 'resq_concern', $human_skincare ) ) {
			++$counts['resq_concern'];
		}
	}

	$pet_topical = resq_seed_ensure_term(
		'pet-topical',
		'Pet Topical',
		'resq_concern',
		0,
		'Problem-led discovery for pet skin and coat support.'
	);
	if ( $pet_topical ) {
		++$counts['resq_concern'];
	}

	foreach (
		array(
			'hot-spots'         => 'Hot Spots',
			'itchy-skin'        => 'Itchy Skin',
			'coat-care'         => 'Coat Care',
			'wounds-abrasions'  => 'Wounds & Abrasions',
		) as $slug => $label
	) {
		if ( resq_seed_ensure_term( $slug, $label, 'resq_concern', $pet_topical ) ) {
			++$counts['resq_concern'];
		}
	}

	// ── resq_ingredient ───────────────────────────────────────────
	foreach (
		array(
			'manuka-honey' => 'Manuka Honey',
			'aloe-vera'    => 'Aloe Vera',
			'coconut-oil'  => 'Coconut Oil',
			'vitamin-e'    => 'Vitamin E',
		) as $slug => $label
	) {
		if ( resq_seed_ensure_term( $slug, $label, 'resq_ingredient' ) ) {
			++$counts['resq_ingredient'];
		}
	}

	// ── resq_product_role ───────────────────────────────────────────
	foreach (
		array(
			'cleanser'      => 'Cleanser',
			'treatment'     => 'Treatment',
			'moisturizer'   => 'Moisturizer',
			'restorer'      => 'Restorer',
			'add-on'        => 'Add-On',
			'replenishment' => 'Replenishment',
		) as $slug => $label
	) {
		if ( resq_seed_ensure_term( $slug, $label, 'resq_product_role' ) ) {
			++$counts['resq_product_role'];
		}
	}

	// ── resq_compliance_zone ────────────────────────────────────────
	foreach (
		array(
			'standard'   => 'Standard — general catalog; default cross-sell pool.',
			'cbd'        => 'CBD — isolated gateway, notices, and restricted cross-sells.',
			'baby'       => 'Baby — infant-safe lines; no generic cart upsells.',
			'pet-health' => 'Pet Health — pet topical/coats; species-specific rules.',
		) as $slug => $description
	) {
		$label = ucwords( str_replace( '-', ' ', $slug ) );
		if ( 'Cbd' === $label ) {
			$label = 'CBD';
		}
		if ( resq_seed_ensure_term( $slug, $label, 'resq_compliance_zone', 0, $description ) ) {
			++$counts['resq_compliance_zone'];
		}
	}

	// ── WooCommerce product_cat (shop paths — not resq_brand) ─────
	// Gateway URLs (/human/, /cbd/) are WP pages; categories organize the catalog.
	$shop_humans = resq_seed_ensure_term(
		'shop-for-humans',
		'Shop For Humans',
		'product_cat',
		0,
		'Primary human catalog branch. Gateway: /human/'
	);
	if ( $shop_humans ) {
		++$counts['product_cat'];
	}

	if ( resq_seed_ensure_term(
		'cbd-wellness',
		'CBD & Wellness',
		'product_cat',
		$shop_humans,
		'Isolated CBD line. Route target: /shop/human/cbd-wellness or /cbd/ gateway.'
	) ) {
		++$counts['product_cat'];
	}

	if ( resq_seed_ensure_term(
		'shop-for-pets',
		'Shop For Pets',
		'product_cat',
		0,
		'Primary pet catalog branch. Gateway: /pets/'
	) ) {
		++$counts['product_cat'];
	}

	if ( resq_seed_ensure_term(
		'bundles-savings',
		'Bundles & Savings',
		'product_cat',
		0,
		'Kits and value bundles. Gateway: /bundles/'
	) ) {
		++$counts['product_cat'];
	}

	// ── Badge + proof reference (product meta, not taxonomies) ──────
	$merch = get_option( 'resq_core_merchandising', array() );
	if ( ! is_array( $merch ) ) {
		$merch = array();
	}

	$merch['badge_type_reference'] = array(
		'new'            => 'New',
		'bundle'         => 'Bundle',
		'sensitive-skin' => 'Sensitive Skin',
		'sulfate-free'   => 'Sulfate-Free',
		'custom'         => 'Custom',
	);

	$merch['default_badge_config'] = array(
		array(
			'condition' => 'is_bundle',
			'label'     => 'Bundle',
			'type'      => 'bundle',
			'priority'  => 20,
		),
		array(
			'condition' => 'on_sale',
			'label'     => 'Sale',
			'type'      => 'sale',
			'priority'  => 30,
		),
	);

	update_option( 'resq_core_merchandising', $merch );

	// Proof / reviews: gated by _resq_compliance_flags slug "proof" per resq-core schema.
	$compliance = get_option( 'resq_core_compliance', array() );
	if ( ! is_array( $compliance ) ) {
		$compliance = array();
	}
	$compliance['proof_reviews_require_flag'] = true;
	$compliance['proof_flag_slug']            = 'proof';
	update_option( 'resq_core_compliance', $compliance );

	update_option( 'resq_catalog_seed_version', '1.0.0' );
	update_option( 'resq_catalog_seed_at', current_time( 'mysql' ) );

	return $counts;
}

// ── Code Snippets: admin UI + one-click run ─────────────────────────

add_action(
	'admin_menu',
	static function (): void {
		add_management_page(
			'ResQ Catalog Seed',
			'ResQ Catalog Seed',
			'manage_woocommerce',
			'resq-catalog-seed',
			'resq_seed_catalog_admin_page'
		);
	}
);

/**
 * Admin page callback.
 */
function resq_seed_catalog_admin_page(): void {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		wp_die( esc_html__( 'You do not have permission to run this seed.', 'resq-core' ) );
	}

	$ran    = false;
	$counts = array();

	if ( isset( $_POST['resq_run_catalog_seed'] ) ) {
		check_admin_referer( 'resq_run_catalog_seed' );
		$counts = resq_seed_catalog_taxonomies();
		$ran    = true;
	}

	$version = get_option( 'resq_catalog_seed_version', '' );
	$seed_at = get_option( 'resq_catalog_seed_at', '' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'ResQ Catalog Taxonomy Seed', 'resq-core' ); ?></h1>
		<p><?php esc_html_e( 'Creates ResQ taxonomy terms and WooCommerce product categories. Does not create products.', 'resq-core' ); ?></p>
		<?php if ( $version ) : ?>
			<p>
				<strong><?php esc_html_e( 'Last seed:', 'resq-core' ); ?></strong>
				<?php echo esc_html( $version . ( $seed_at ? ' @ ' . $seed_at : '' ) ); ?>
			</p>
		<?php endif; ?>
		<?php if ( $ran && ! isset( $counts['error'] ) ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Catalog terms seeded successfully.', 'resq-core' ); ?></p></div>
			<ul>
				<?php foreach ( $counts as $group => $count ) : ?>
					<li><code><?php echo esc_html( (string) $group ); ?></code>: <?php echo esc_html( (string) $count ); ?> term operations</li>
				<?php endforeach; ?>
			</ul>
		<?php elseif ( $ran ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'WooCommerce must be active.', 'resq-core' ); ?></p></div>
		<?php endif; ?>
		<form method="post">
			<?php wp_nonce_field( 'resq_run_catalog_seed' ); ?>
			<p>
				<button type="submit" name="resq_run_catalog_seed" class="button button-primary">
					<?php esc_html_e( 'Run seed now', 'resq-core' ); ?>
				</button>
			</p>
		</form>
		<h2><?php esc_html_e( 'What this creates', 'resq-core' ); ?></h2>
		<ul style="list-style:disc;margin-left:1.5em;">
			<li><strong>resq_audience:</strong> human, pet</li>
			<li><strong>resq_concern:</strong> human-skincare → dry-skin, sensitive-skin, scalp-care, anti-aging; pet-topical → hot-spots, itchy-skin, coat-care, wounds-abrasions</li>
			<li><strong>resq_ingredient:</strong> manuka-honey, aloe-vera, coconut-oil, vitamin-e</li>
			<li><strong>resq_product_role:</strong> cleanser, treatment, moisturizer, restorer, add-on, replenishment</li>
			<li><strong>resq_compliance_zone:</strong> standard, cbd, baby, pet-health</li>
			<li><strong>product_cat:</strong> Shop For Humans (+ CBD &amp; Wellness child), Shop For Pets, Bundles &amp; Savings</li>
			<li><strong>Options:</strong> badge type reference + default bundle/sale badge rules; proof-review flag reference</li>
		</ul>
		<p><?php esc_html_e( 'Per-product badges use _resq_badge_label and _resq_badge_type meta when you add products.', 'resq-core' ); ?></p>
	</div>
	<?php
}
