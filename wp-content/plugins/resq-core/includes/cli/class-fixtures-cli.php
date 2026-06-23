<?php
/**
 * WP-CLI commands for Phase 7 demo fixtures.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ fixture import and reset commands.
 */
class ResQ_Core_Fixtures_CLI {

	/**
	 * Import demo fixture catalog idempotently.
	 *
	 * ## OPTIONS
	 *
	 * [--reset]
	 * : Delete existing fixture products and routines before importing.
	 *
	 * ## EXAMPLES
	 *
	 *     wp resq-fixtures import
	 *     wp resq-fixtures import --reset
	 *
	 * @when after_wp_load
	 *
	 * @param array<int, string>    $args       Positional args.
	 * @param array<string, string> $assoc_args Associative args.
	 */
	public function import( array $args, array $assoc_args ): void {
		unset( $args );

		$importer = new ResQ_Core_Fixture_Importer();

		try {
			if ( ! empty( $assoc_args['reset'] ) ) {
				$removed = $importer->reset();
				WP_CLI::log(
					sprintf(
						'Removed %d fixture products and %d fixture routines.',
						$removed['products'],
						$removed['routines']
					)
				);
			}

			WP_CLI::log( 'Importing ResQ demo fixtures...' );
			$ids = $importer->import();

			WP_CLI::log(
				sprintf(
					'Imported %d products, %d routines, and %d bundles.',
					count( $ids['products'] ),
					count( $ids['routines'] ),
					count( $ids['bundles'] )
				)
			);

			WP_CLI::success( 'Fixture import complete.' );
		} catch ( RuntimeException $exception ) {
			WP_CLI::error( $exception->getMessage() );
		}
	}

	/**
	 * Remove all fixture products and routines.
	 *
	 * ## OPTIONS
	 *
	 * [--yes]
	 * : Skip confirmation prompt.
	 *
	 * ## EXAMPLES
	 *
	 *     wp resq-fixtures reset --yes
	 *
	 * @when after_wp_load
	 *
	 * @param array<int, string>    $args       Positional args.
	 * @param array<string, string> $assoc_args Associative args.
	 */
	public function reset( array $args, array $assoc_args ): void {
		unset( $args );

		if ( empty( $assoc_args['yes'] ) ) {
			WP_CLI::confirm( 'This will permanently delete all products with SKUs prefixed "fixture-" and routines prefixed "fixture-". Continue?' );
		}

		$importer = new ResQ_Core_Fixture_Importer();

		try {
			$removed = $importer->reset();
			WP_CLI::success(
				sprintf(
					'Removed %d fixture products and %d fixture routines.',
					$removed['products'],
					$removed['routines']
				)
			);
		} catch ( RuntimeException $exception ) {
			WP_CLI::error( $exception->getMessage() );
		}
	}
}
