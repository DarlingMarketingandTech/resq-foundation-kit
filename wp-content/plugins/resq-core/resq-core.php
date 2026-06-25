<?php
/**
 * Plugin Name: ResQ Core
 * Plugin URI:
 * Description: Business logic, settings, and WooCommerce integrations for the ResQ storefront.
 * Version: 0.5.0
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * Author:
 * Author URI:
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: resq-core
 * Domain Path: /languages
 *
 * WC requires at least: 8.0
 * WC tested up to: 9.0
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

if ( defined( 'RESQ_CORE_VERSION' ) ) {
	return;
}

define( 'RESQ_CORE_VERSION', '0.5.0' );
define( 'RESQ_CORE_FILE', __FILE__ );
define( 'RESQ_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'RESQ_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'RESQ_CORE_INCLUDES', RESQ_CORE_DIR . 'includes/' );

require_once RESQ_CORE_INCLUDES . 'helpers/infrastructure.php';
require_once RESQ_CORE_INCLUDES . 'class-options.php';
require_once RESQ_CORE_INCLUDES . 'class-cache.php';
require_once RESQ_CORE_INCLUDES . 'registrations/class-post-meta.php';
require_once RESQ_CORE_INCLUDES . 'registrations/class-term-meta.php';
require_once RESQ_CORE_INCLUDES . 'registrations/class-taxonomies.php';
require_once RESQ_CORE_INCLUDES . 'registrations/class-cpt.php';
require_once RESQ_CORE_INCLUDES . 'registrations/class-registrations.php';
require_once RESQ_CORE_INCLUDES . 'class-woocommerce-compat.php';
require_once RESQ_CORE_INCLUDES . 'helpers/internal.php';
require_once RESQ_CORE_INCLUDES . 'helpers/storefront.php';
require_once RESQ_CORE_INCLUDES . 'class-product-sync.php';
require_once RESQ_CORE_INCLUDES . 'woocommerce/class-merchandising-hooks.php';
require_once RESQ_CORE_INCLUDES . 'woocommerce/class-product-filters.php';
require_once RESQ_CORE_INCLUDES . 'class-plugin.php';

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once RESQ_CORE_INCLUDES . 'fixtures/data/catalog.php';
	require_once RESQ_CORE_INCLUDES . 'fixtures/class-fixture-importer.php';
	require_once RESQ_CORE_INCLUDES . 'cli/class-fixtures-cli.php';
	WP_CLI::add_command( 'resq-fixtures', 'ResQ_Core_Fixtures_CLI' );

	require_once RESQ_CORE_INCLUDES . 'catalog/data/catalog.php';
	require_once RESQ_CORE_INCLUDES . 'catalog/class-catalog-importer.php';
	require_once RESQ_CORE_INCLUDES . 'cli/class-catalog-cli.php';
	WP_CLI::add_command( 'resq-catalog', 'ResQ_Core_Catalog_CLI' );
}

ResQ_Core_Plugin::instance();
