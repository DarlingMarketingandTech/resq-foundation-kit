<?php
/**
 * Main plugin class.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Plugin — singleton bootstrap for the ResQ Core plugin.
 *
 * Initializes options, checks WooCommerce availability, loads helpers,
 * and registers activation/deactivation hooks. Does not register
 * taxonomies, CPTs, admin UI, or front-end markup in Phase 2B.
 */
class ResQ_Core_Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var self|null
	 */
	private static ?self $instance = null;

	/**
	 * Whether the plugin has been fully initialized.
	 *
	 * @var bool
	 */
	private bool $initialized = false;

	/**
	 * Get or create the singleton instance.
	 *
	 * @return self
	 */
	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor — runs once.
	 */
	private function __construct() {
		$this->register_hooks();
		$this->init();
	}

	/**
	 * Prevent cloning.
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization.
	 */
	public function __wakeup() {
		throw new \LogicException( 'Cannot unserialize singleton.' );
	}

	/**
	 * Register activation and deactivation hooks.
	 */
	private function register_hooks(): void {
		register_activation_hook( RESQ_CORE_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( RESQ_CORE_FILE, array( $this, 'deactivate' ) );

		add_action(
			'before_woocommerce_init',
			array( 'ResQ_Core_Woocommerce_Compat', 'declare_feature_compatibility' )
		);
	}

	/**
	 * Initialize the plugin on every load.
	 */
	private function init(): void {
		ResQ_Core_Options::maybe_set_defaults();

		if ( ! ResQ_Core_Woocommerce_Compat::is_woocommerce_active() ) {
			ResQ_Core_Woocommerce_Compat::register_admin_notice();
		}

		$this->initialized = true;
	}

	/**
	 * Plugin activation callback.
	 */
	public function activate(): void {
		ResQ_Core_Options::set_defaults();
	}

	/**
	 * Plugin deactivation callback.
	 *
	 * Clears transients. Preserves options, post meta, and term meta
	 * per docs/01-THEME-PLUGIN-CONTRACT.md.
	 */
	public function deactivate(): void {
		$this->clear_transients();
	}

	/**
	 * Whether the plugin finished initialization.
	 *
	 * @return bool
	 */
	public function is_initialized(): bool {
		return $this->initialized;
	}

	/**
	 * Delete plugin-owned transients.
	 */
	private function clear_transients(): void {
		global $wpdb;

		if ( ! isset( $wpdb ) ) {
			return;
		}

		$wpdb->query(
			"DELETE FROM {$wpdb->options}
			 WHERE option_name LIKE '_transient_resq_core_%'
			    OR option_name LIKE '_transient_timeout_resq_core_%'"
		);
	}
}
