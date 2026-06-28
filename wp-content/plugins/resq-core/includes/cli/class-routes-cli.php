<?php
/**
 * WP-CLI commands for storefront route / gateway page audit.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ routes audit and gateway page provisioning.
 */
class ResQ_Core_Routes_CLI {

	/**
	 * Audit gateway page templates, permalinks, and expected paths.
	 *
	 * ## EXAMPLES
	 *
	 *     wp resq routes audit
	 *
	 * @when after_wp_load
	 *
	 * @param array<int, string>    $args       Positional args.
	 * @param array<string, string> $assoc_args Associative args.
	 */
	public function audit( array $args, array $assoc_args ): void {
		unset( $args, $assoc_args );

		$rows   = ResQ_Core_Gateway_Pages::audit();
		$issues = 0;

		foreach ( $rows as $row ) {
			$status = (string) $row['status'];
			if ( 'ok' !== $status ) {
				++$issues;
			}

			WP_CLI::log(
				sprintf(
					'[%s] %s — %s (page_id=%s)',
					strtoupper( $status ),
					$row['template'],
					$row['permalink'] ?: 'missing',
					$row['page_id'] ?: '0'
				)
			);

			if ( ! empty( $row['issues'] ) ) {
				foreach ( $row['issues'] as $issue ) {
					WP_CLI::log( '  - ' . $issue );
				}
			}

			WP_CLI::log( '  expected: ' . home_url( (string) $row['expected_path'] ) );
		}

		if ( function_exists( 'resq_resolve_lane_url' ) ) {
			WP_CLI::log( '' );
			WP_CLI::log( 'Sample lane URL: ' . resq_resolve_lane_url( 'human', 'womens-skincare' ) );
		}

		if ( $issues > 0 ) {
			WP_CLI::warning( sprintf( '%d gateway route issue(s) found. Run: wp resq routes ensure_pages', $issues ) );
		} else {
			WP_CLI::success( 'All gateway routes match expected paths.' );
		}
	}

	/**
	 * Create or update gateway pages under the WooCommerce shop page.
	 *
	 * ## EXAMPLES
	 *
	 *     wp resq routes ensure_pages
	 *     wp resq routes ensure-pages
	 *
	 * @when after_wp_load
	 *
	 * @param array<int, string>    $args       Positional args.
	 * @param array<string, string> $assoc_args Associative args.
	 */
	public function ensure_pages( array $args, array $assoc_args ): void {
		self::run_ensure_pages( $args, $assoc_args );
	}

	/**
	 * Hyphenated alias for ensure_pages.
	 *
	 * ## EXAMPLES
	 *
	 *     wp resq routes ensure-pages
	 *
	 * @subcommand ensure-pages
	 *
	 * @when after_wp_load
	 *
	 * @param array<int, string>    $args       Positional args.
	 * @param array<string, string> $assoc_args Associative args.
	 */
	public function ensure_pages_hyphen( array $args, array $assoc_args ): void {
		self::run_ensure_pages( $args, $assoc_args );
	}

	/**
	 * Shared handler for ensure_pages / ensure-pages.
	 *
	 * @param array<int, string>    $args       Positional args.
	 * @param array<string, string> $assoc_args Associative args.
	 */
	private static function run_ensure_pages( array $args, array $assoc_args ): void {
		unset( $args, $assoc_args );

		try {
			$counts = ResQ_Core_Gateway_Pages::ensure_pages();
			WP_CLI::success(
				sprintf(
					'Gateway pages ensured (created=%d, updated=%d, skipped=%d).',
					$counts['created'],
					$counts['updated'],
					$counts['skipped']
				)
			);
		} catch ( Throwable $exception ) {
			WP_CLI::error( $exception->getMessage() );
		}
	}
}
