<?php
/**
 * WP-CLI commands for lane shelf audit.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ lanes audit.
 */
class ResQ_Core_Lanes_CLI {

	/**
	 * Audit main category lane shelves (resolution, products, CBD/audience isolation).
	 *
	 * ## EXAMPLES
	 *
	 *     wp resq lanes audit
	 *
	 * @when after_wp_load
	 *
	 * @param array<int, string>    $args       Positional args.
	 * @param array<string, string> $assoc_args Associative args.
	 */
	public function audit( array $args, array $assoc_args ): void {
		unset( $args, $assoc_args );

		$result = ResQ_Core_Lane_Audit::audit();
		$rows   = $result['rows'];
		$gates  = $result['gates'];

		foreach ( $rows as $row ) {
			$status = ! empty( $row['resolved'] ) ? 'OK' : 'MISSING';

			WP_CLI::log( '' );
			WP_CLI::log( sprintf( '[%s] %s', $status, $row['lane_key'] ) );

			if ( empty( $row['resolved'] ) ) {
				WP_CLI::log( '  lane did not resolve from registry' );
				continue;
			}

			WP_CLI::log( '  url: ' . $row['url'] );
			WP_CLI::log( '  draft: ' . ( ! empty( $row['is_draft'] ) ? 'yes' : 'no' ) );
			WP_CLI::log( '  products: ' . (int) $row['product_count'] );
			WP_CLI::log( '  skus: ' . ( ! empty( $row['skus'] ) ? implode( ', ', $row['skus'] ) : '(none)' ) );
			WP_CLI::log( '  cbd_leaks: ' . (int) $row['cbd_leaks'] );
			WP_CLI::log( '  audience_leaks: ' . (int) $row['audience_leaks'] );
			WP_CLI::log( '  missing_product_images: ' . (int) $row['missing_product_images'] );

			$hero_slug = (string) $row['hero_image_slug'];
			if ( '' !== $hero_slug ) {
				WP_CLI::log(
					'  hero_image: ' . $hero_slug . ( ! empty( $row['hero_image_missing'] ) ? ' (MISSING)' : ' (ok)' )
				);
			} else {
				WP_CLI::log( '  hero_image: (no slug configured)' . ( ! empty( $row['hero_image_missing'] ) ? ' MISSING' : '' ) );
			}
		}

		WP_CLI::log( '' );
		WP_CLI::log(
			sprintf(
				'Totals — cbd_leaks=%d, audience_leaks=%d, missing_product_images=%d, missing_hero_images=%d',
				(int) $gates['cbd_leaks'],
				(int) $gates['audience_leaks'],
				(int) $gates['missing_prod_images'],
				(int) $gates['missing_hero_images']
			)
		);

		if ( ! empty( $gates['pass'] ) ) {
			WP_CLI::success(
				'All category lanes resolve, have products, and pass CBD/audience isolation (cbd_leaks=0, audience_leaks=0).'
			);
			return;
		}

		$issues = array();
		if ( empty( $gates['all_resolved'] ) ) {
			$issues[] = 'one or more lanes did not resolve';
		}
		if ( empty( $gates['all_have_products'] ) ) {
			$issues[] = 'one or more lanes have zero products';
		}
		if ( (int) $gates['cbd_leaks'] > 0 ) {
			$issues[] = 'CBD leaks detected';
		}
		if ( (int) $gates['audience_leaks'] > 0 ) {
			$issues[] = 'audience leaks detected';
		}

		WP_CLI::warning( 'Lane audit failed: ' . implode( '; ', $issues ) . '.' );
	}
}
