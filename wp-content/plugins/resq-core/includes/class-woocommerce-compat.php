<?php
/**
 * WooCommerce compatibility and dependency check.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Woocommerce_Compat — checks WooCommerce availability.
 *
 * When WooCommerce is inactive the plugin still loads (no fatal errors)
 * but all helpers return empty-safe defaults and an admin notice is shown.
 * See docs/01-THEME-PLUGIN-CONTRACT.md § WooCommerce Deactivated.
 */
class ResQ_Core_Woocommerce_Compat {

	/**
	 * Whether WooCommerce is active.
	 *
	 * Checks the `woocommerce/woocommerce.php` key in the active plugins
	 * list. This works without requiring WooCommerce files to be loaded.
	 *
	 * @return bool
	 */
	public static function is_woocommerce_active(): bool {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( function_exists( 'is_plugin_active' ) ) {
			return is_plugin_active( 'woocommerce/woocommerce.php' );
		}

		return class_exists( 'WooCommerce' );
	}

	/**
	 * Declare compatibility with WooCommerce features (HPOS, etc.).
	 *
	 * Must be called on `before_woocommerce_init`. Without this,
	 * WooCommerce shows an "incompatible plugins" warning.
	 */
	public static function declare_feature_compatibility(): void {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
				'custom_order_tables',
				RESQ_CORE_FILE,
				true
			);
		}
	}

	/**
	 * Register admin notice for missing WooCommerce.
	 */
	public static function register_admin_notice(): void {
		add_action( 'admin_notices', array( __CLASS__, 'render_missing_woo_notice' ) );
	}

	/**
	 * Render the admin notice.
	 */
	public static function render_missing_woo_notice(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		?>
		<div class="notice notice-warning is-dismissible">
			<p>
				<strong><?php esc_html_e( 'ResQ Core:', 'resq-core' ); ?></strong>
				<?php esc_html_e( 'WooCommerce is required for full functionality. Please install and activate WooCommerce.', 'resq-core' ); ?>
			</p>
		</div>
		<?php
	}
}
