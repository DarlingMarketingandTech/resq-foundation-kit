<?php
/**
 * ResQ local MCP bootstrap — deploy to wp-content/mu-plugins/ on Local only.
 *
 * Site shell install (from WordPress root):
 *   mkdir wp-content\mu-plugins
 *   copy C:\dev\resq-foundation-kit\scripts\code-snippets\resq-mcp-bootstrap.php wp-content\mu-plugins\resq-mcp-bootstrap.php
 *   wp option update woocommerce_feature_mcp_integration_enabled yes
 *   wp plugin activate mcp-adapter
 *
 * Do not use wp eval-file for this — MCP hooks must load on every request.
 *
 * @package ResQ_Foundation_Kit
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'woocommerce_mcp_allow_insecure_transport',
	static function ( bool $allow, $request ): bool {
		if ( defined( 'WP_ENVIRONMENT_TYPE' ) && 'local' === WP_ENVIRONMENT_TYPE ) {
			return true;
		}
		return $allow;
	},
	10,
	2
);

add_action(
	'wp_abilities_api_init',
	static function (): void {
		if ( ! function_exists( 'wp_register_ability' ) ) {
			return;
		}

		wp_register_ability(
			'resq/mcp-health',
			array(
				'label'               => 'ResQ MCP health check',
				'description'         => 'Returns site name, URL, and plugin versions for MCP connectivity tests.',
				'category'            => 'site',
				'input_schema'        => array( 'type' => 'object' ),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'site_name'   => array( 'type' => 'string' ),
						'site_url'    => array( 'type' => 'string' ),
						'resq_core'   => array( 'type' => 'string' ),
						'resq_theme'  => array( 'type' => 'string' ),
						'woocommerce' => array( 'type' => 'string' ),
					),
				),
				'execute_callback'    => static function (): array {
					return array(
						'site_name'   => (string) get_bloginfo( 'name' ),
						'site_url'    => (string) home_url( '/' ),
						'resq_core'   => defined( 'RESQ_CORE_VERSION' ) ? RESQ_CORE_VERSION : 'inactive',
						'resq_theme'  => wp_get_theme( 'resq-clean-pro' )->get( 'Version' ) ?: 'inactive',
						'woocommerce' => defined( 'WC_VERSION' ) ? WC_VERSION : 'inactive',
					);
				},
				'permission_callback' => static function (): bool {
					return current_user_can( 'read' );
				},
				'meta'                => array( 'mcp' => array( 'public' => true ) ),
			)
		);

		wp_register_ability(
			'resq/catalog-product-count',
			array(
				'label'               => 'ResQ catalog product count',
				'description'         => 'Count published WooCommerce products with RQ- SKUs.',
				'category'            => 'site',
				'input_schema'        => array( 'type' => 'object' ),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'rq_product_count' => array( 'type' => 'integer' ),
					),
				),
				'execute_callback'    => static function (): array {
					if ( ! function_exists( 'wc_get_products' ) ) {
						return array( 'rq_product_count' => 0 );
					}
					$ids   = wc_get_products( array( 'limit' => -1, 'return' => 'ids', 'status' => 'publish' ) );
					$count = 0;
					foreach ( $ids as $id ) {
						$product = wc_get_product( $id );
						if ( $product && str_starts_with( (string) $product->get_sku(), 'RQ-' ) ) {
							++$count;
						}
					}
					return array( 'rq_product_count' => $count );
				},
				'permission_callback' => static function (): bool {
					return current_user_can( 'manage_woocommerce' );
				},
				'meta'                => array( 'mcp' => array( 'public' => true ) ),
			)
		);
	}
);
