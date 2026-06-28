<?php
/**
 * WP-CLI commands for bundle merchandising audit.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ bundles audit.
 */
class ResQ_Core_Bundles_CLI {

	/**
	 * Audit bundle images, savings, and routine-ladder upgrade prompts.
	 *
	 * ## EXAMPLES
	 *
	 *     wp resq bundles audit
	 *
	 * @when after_wp_load
	 *
	 * @param array<int, string>    $args       Positional args.
	 * @param array<string, string> $assoc_args Associative args.
	 */
	public function audit( array $args, array $assoc_args ): void {
		unset( $args, $assoc_args );

		$result  = ResQ_Core_Bundle_Audit::audit();
		$rows    = $result['rows'];
		$summary = $result['summary'];

		foreach ( $rows as $row ) {
			WP_CLI::log( '' );
			WP_CLI::log(
				sprintf(
					'[%d] %s (%s)',
					(int) $row['product_id'],
					(string) $row['title'],
					'' !== (string) $row['sku'] ? (string) $row['sku'] : 'no-sku'
				)
			);

			WP_CLI::log( '  url: ' . (string) $row['url'] );
			WP_CLI::log( '  price: ' . (string) $row['price'] );
			WP_CLI::log(
				'  featured_image: ' . ( ! empty( $row['has_featured_image'] ) ? 'yes' : 'no' )
				. ( ! empty( $row['is_wc_placeholder'] ) ? ' (woocommerce placeholder)' : '' )
			);
			WP_CLI::log(
				'  component_skus: ' . ( ! empty( $row['component_skus'] ) ? implode( ', ', $row['component_skus'] ) : '(none)' )
			);
			WP_CLI::log(
				'  component_usable_images: ' . (int) ( $row['component_usable_images'] ?? 0 )
			);
			WP_CLI::log(
				'  fallback_mode: ' . (string) ( $row['fallback_mode'] ?? 'none' )
				. ( ! empty( $row['can_component_fallback'] ) ? ' (public fallback available)' : '' )
			);

			if ( null !== $row['parts_total'] ) {
				WP_CLI::log( '  parts_total: ' . number_format( (float) $row['parts_total'], 2, '.', '' ) );
			}

			WP_CLI::log(
				'  composition_savings: ' . number_format( (float) ( $row['computed_savings'] ?? 0 ), 2, '.', '' )
			);

			if ( null !== $row['ladder_steps_total'] ) {
				WP_CLI::log( '  ladder_steps_total: ' . number_format( (float) $row['ladder_steps_total'], 2, '.', '' ) );
				WP_CLI::log( '  ladder_savings: ' . number_format( (float) ( $row['ladder_savings'] ?? 0 ), 2, '.', '' ) );
			} else {
				WP_CLI::log( '  ladder_savings: (no routine steps)' );
			}

			if ( ! empty( $row['savings_mismatch'] ) ) {
				WP_CLI::warning( '  savings_mismatch: composition vs routine-ladder totals differ' );
			}

			if ( ! empty( $row['self_upgrade_issue'] ) ) {
				WP_CLI::warning( '  self_upgrade_issue: routine ladder links back to this bundle' );
			}
		}

		WP_CLI::log( '' );
		WP_CLI::log(
			sprintf(
				'Summary — total=%d, missing_featured_images=%d, component_image_fallback=%d, savings_mismatches=%d, self_upgrade_issues=%d',
				(int) $summary['total'],
				(int) $summary['missing_featured_images'],
				(int) $summary['component_image_fallback'],
				(int) $summary['savings_mismatches'],
				(int) $summary['self_upgrade_issues']
			)
		);

		if ( (int) $summary['savings_mismatches'] > 0 ) {
			WP_CLI::log(
				'Recommendation: use resq_get_bundle_savings_breakdown() / composition totals as the canonical PDP savings source; align routine-ladder savings to bundle composition when a routine is linked.'
			);
		}

		$warnings = (int) $summary['missing_featured_images']
			+ (int) $summary['savings_mismatches']
			+ (int) $summary['self_upgrade_issues'];

		if ( 0 === (int) $summary['total'] ) {
			WP_CLI::warning( 'No bundle products found.' );
			return;
		}

		if ( $warnings > 0 ) {
			WP_CLI::warning( 'Bundle audit completed with merchandising warnings. Missing featured images can use public collage/branded fallbacks where noted.' );
			return;
		}

		WP_CLI::success( 'All bundles have featured images, aligned savings, and no self-upgrade issues.' );
	}
}
