<?php
/**
 * Automattic WooCommerce utility stubs for IDE static analysis.
 *
 * @package ResQ_Foundation_Kit
 */

namespace Automattic\WooCommerce\Utilities;

/**
 * HPOS / feature compatibility helper (used by resq-core compat layer).
 */
class FeaturesUtil {

	/**
	 * @param string $feature_id Feature identifier.
	 * @param string $plugin_file Plugin main file path.
	 * @param bool   $positive    Whether compatibility is declared.
	 */
	public static function declare_compatibility( $feature_id, $plugin_file, $positive = true ) {}
}
