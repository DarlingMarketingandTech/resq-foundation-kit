<?php
/**
 * WP-CLI commands for real catalog import.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ catalog import, reset, and CSV export commands.
 */
class ResQ_Core_Catalog_CLI {

	/**
	 * Import real catalog idempotently.
	 *
	 * ## OPTIONS
	 *
	 * [--reset]
	 * : Delete existing RQ- SKU products and rq- routines before importing.
	 *
	 * ## EXAMPLES
	 *
	 *     wp resq-catalog import
	 *     wp resq-catalog import --reset
	 *
	 * @when after_wp_load
	 *
	 * @param array<int, string>    $args       Positional args.
	 * @param array<string, string> $assoc_args Associative args.
	 */
	public function import( array $args, array $assoc_args ): void {
		unset( $args );

		$importer = new ResQ_Core_Catalog_Importer();

		try {
			if ( ! empty( $assoc_args['reset'] ) ) {
				$removed = $importer->reset();
				WP_CLI::log(
					sprintf(
						'Removed %d catalog products and %d catalog routines.',
						$removed['products'],
						$removed['routines']
					)
				);
			}

			WP_CLI::log( 'Importing ResQ real catalog...' );
			$ids = $importer->import();

			WP_CLI::log(
				sprintf(
					'Imported %d product SKUs, %d routines, and %d bundles.',
					count( $ids['products'] ),
					count( $ids['routines'] ),
					count( $ids['bundles'] )
				)
			);

			WP_CLI::success( 'Catalog import complete.' );
		} catch ( RuntimeException $exception ) {
			WP_CLI::error( $exception->getMessage() );
		}
	}

	/**
	 * Remove all catalog products (RQ- SKUs) and routines (rq- slugs).
	 *
	 * ## OPTIONS
	 *
	 * [--yes]
	 * : Skip confirmation prompt.
	 *
	 * ## EXAMPLES
	 *
	 *     wp resq-catalog reset --yes
	 *
	 * @when after_wp_load
	 *
	 * @param array<int, string>    $args       Positional args.
	 * @param array<string, string> $assoc_args Associative args.
	 */
	public function reset( array $args, array $assoc_args ): void {
		unset( $args );

		if ( empty( $assoc_args['yes'] ) ) {
			WP_CLI::confirm( 'This will permanently delete all products with SKUs prefixed "RQ-" and routines prefixed "rq-". Continue?' );
		}

		$importer = new ResQ_Core_Catalog_Importer();

		try {
			$removed = $importer->reset();
			WP_CLI::success(
				sprintf(
					'Removed %d catalog products and %d catalog routines.',
					$removed['products'],
					$removed['routines']
				)
			);
		} catch ( RuntimeException $exception ) {
			WP_CLI::error( $exception->getMessage() );
		}
	}

	/**
	 * Export catalog rows to a WooCommerce-compatible CSV reference file.
	 *
	 * ## OPTIONS
	 *
	 * [--file=<path>]
	 * : Output CSV path. Defaults to repo docs path when available.
	 *
	 * ## EXAMPLES
	 *
	 *     wp resq-catalog export-csv
	 *     wp resq-catalog export-csv --file=/tmp/resq-catalog-import.csv
	 *
	 * @when after_wp_load
	 *
	 * @param array<int, string>    $args       Positional args.
	 * @param array<string, string> $assoc_args Associative args.
	 */
	public function export_csv( array $args, array $assoc_args ): void {
		unset( $args );

		$default = dirname( RESQ_CORE_DIR, 3 ) . '/docs/Product Data and Strategy/resq-catalog-import.csv';
		$file    = (string) ( $assoc_args['file'] ?? $default );

		$importer = new ResQ_Core_Catalog_Importer();
		$rows     = $importer->export_csv_rows();

		if ( empty( $rows ) ) {
			WP_CLI::error( 'No catalog rows to export.' );
		}

		$headers = array(
			'Type',
			'SKU',
			'Name',
			'Published',
			'Regular price',
			'Categories',
			'Short description',
			'Parent',
			'Attribute 1 name',
			'Attribute 1 value(s)',
		);

		$dir = dirname( $file );
		if ( ! is_dir( $dir ) && ! wp_mkdir_p( $dir ) ) {
			WP_CLI::error( sprintf( 'Could not create directory: %s', $dir ) );
		}

		$handle = fopen( $file, 'w' );
		if ( false === $handle ) {
			WP_CLI::error( sprintf( 'Could not open file for writing: %s', $file ) );
		}

		fputcsv( $handle, $headers );
		foreach ( $rows as $row ) {
			$line = array();
			foreach ( $headers as $header ) {
				$line[] = (string) ( $row[ $header ] ?? '' );
			}
			fputcsv( $handle, $line );
		}
		fclose( $handle );

		WP_CLI::success( sprintf( 'Exported %d rows to %s', count( $rows ), $file ) );
	}
}
