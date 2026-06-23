<?php
/**
 * Generate resq-catalog-import.csv from catalog data (no WordPress required).
 *
 * Usage: php scripts/generate-catalog-csv.php
 */

define( 'ABSPATH', true );

$root = dirname( __DIR__ );
require_once $root . '/wp-content/plugins/resq-core/includes/catalog/data/catalog.php';

$catalog = resq_catalog_get_data();
$rows    = array();

foreach ( $catalog['products'] as $sku => $data ) {
	$type = (string) ( $data['type'] ?? 'simple' );
	$cats = array_map(
		static fn( $c ) => (string) ( $c['name'] ?? '' ),
		(array) ( $data['categories'] ?? array() )
	);

	if ( 'variable' === $type ) {
		$rows[] = array(
			'Type'              => 'variable',
			'SKU'               => $sku,
			'Name'              => (string) $data['name'],
			'Published'         => '1',
			'Regular price'     => (string) ( $data['price'] ?? '' ),
			'Categories'        => implode( ' > ', $cats ),
			'Short description' => (string) ( $data['card_sub'] ?? '' ),
			'Parent'            => '',
			'Attribute 1 name'  => '',
			'Attribute 1 value(s)' => '',
		);
		foreach ( (array) ( $data['variations'] ?? array() ) as $variation ) {
			$attrs = (array) ( $variation['attributes'] ?? array() );
			$first = array_key_first( $attrs );
			$rows[] = array(
				'Type'                 => 'variation',
				'SKU'                  => (string) ( $variation['sku'] ?? '' ),
				'Name'                 => (string) $data['name'],
				'Published'            => '1',
				'Regular price'        => (string) ( $variation['price'] ?? '' ),
				'Categories'           => '',
				'Short description'    => '',
				'Parent'               => $sku,
				'Attribute 1 name'     => $first ? ucwords( (string) $first ) : '',
				'Attribute 1 value(s)' => $first ? (string) $attrs[ $first ] : '',
			);
		}
	} else {
		$rows[] = array(
			'Type'              => 'simple',
			'SKU'               => $sku,
			'Name'              => (string) $data['name'],
			'Published'         => '1',
			'Regular price'     => (string) ( $data['price'] ?? '' ),
			'Categories'        => implode( ' > ', $cats ),
			'Short description' => (string) ( $data['card_sub'] ?? '' ),
			'Parent'            => '',
			'Attribute 1 name'  => '',
			'Attribute 1 value(s)' => '',
		);
	}
}

foreach ( $catalog['bundles'] as $sku => $data ) {
	$rows[] = array(
		'Type'              => 'simple',
		'SKU'               => $sku,
		'Name'              => (string) $data['name'],
		'Published'         => '1',
		'Regular price'     => (string) ( $data['price'] ?? '' ),
		'Categories'        => 'Bundles & Savings',
		'Short description' => '',
		'Parent'            => '',
		'Attribute 1 name'  => '',
		'Attribute 1 value(s)' => '',
	);
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

$out = $root . '/docs/Product Data and Strategy/resq-catalog-import.csv';
$dir = dirname( $out );
if ( ! is_dir( $dir ) ) {
	mkdir( $dir, 0777, true );
}

$handle = fopen( $out, 'w' );
if ( false === $handle ) {
	fwrite( STDERR, "Could not write {$out}\n" );
	exit( 1 );
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

echo "Wrote " . count( $rows ) . " rows to {$out}\n";
