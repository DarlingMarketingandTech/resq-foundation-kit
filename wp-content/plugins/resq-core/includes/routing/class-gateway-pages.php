<?php
/**
 * Gateway page registry — expected WP pages for storefront routes.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Gateway_Pages
 */
class ResQ_Core_Gateway_Pages {

	/**
	 * Expected gateway / learn pages (child of WooCommerce shop where noted).
	 *
	 * @return array<int, array<string, string>>
	 */
	public static function get_specs(): array {
		return array(
			array(
				'title'    => 'Shop For Humans',
				'slug'     => 'human',
				'template' => 'page-gateway-human.php',
				'parent'   => 'shop',
			),
			array(
				'title'    => 'Shop For Pets',
				'slug'     => 'pet',
				'template' => 'page-gateway-pet.php',
				'parent'   => 'shop',
			),
			array(
				'title'    => 'Bundles & Savings',
				'slug'     => 'bundles',
				'template' => 'page-gateway-bundles.php',
				'parent'   => 'shop',
			),
			array(
				'title'    => 'CBD & Wellness',
				'slug'     => 'cbd',
				'template' => 'page-gateway-cbd.php',
				'parent'   => 'shop',
			),
			array(
				'title'    => 'Learn',
				'slug'     => 'learn',
				'template' => 'page-learn-index.php',
				'parent'   => '',
			),
		);
	}

	/**
	 * Canonical fallback paths keyed by page template (theme contract).
	 *
	 * @return array<string, string>
	 */
	public static function get_fallback_paths(): array {
		return array(
			'page-gateway-human.php'   => '/shop/human/',
			'page-gateway-pet.php'     => '/shop/pet/',
			'page-gateway-bundles.php' => '/shop/bundles/',
			'page-gateway-cbd.php'     => '/shop/cbd/',
			'page-learn-index.php'     => '/learn/',
		);
	}

	/**
	 * Audit published gateway pages against expected templates and paths.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function audit(): array {
		$results    = array();
		$fallbacks  = self::get_fallback_paths();
		$shop_id    = function_exists( 'wc_get_page_id' ) ? (int) wc_get_page_id( 'shop' ) : 0;
		$shop_ok    = $shop_id > 0 && 'publish' === get_post_status( $shop_id );

		foreach ( self::get_specs() as $spec ) {
			$template = (string) $spec['template'];
			$page_id  = self::find_page_id_by_template( $template );
			$expected = $fallbacks[ $template ] ?? '/';
			$row      = array(
				'template'      => $template,
				'title'         => (string) $spec['title'],
				'expected_path' => $expected,
				'page_id'       => $page_id,
				'status'        => 'missing',
				'permalink'     => '',
				'issues'        => array(),
			);

			if ( ! $page_id ) {
				$row['issues'][] = 'No published page uses this template.';
				$results[]       = $row;
				continue;
			}

			$row['permalink'] = (string) get_permalink( $page_id );
			$row['status']    = 'ok';

			$expected_url = home_url( $expected );
			if ( untrailingslashit( $row['permalink'] ) !== untrailingslashit( $expected_url ) ) {
				$row['status']     = 'mismatch';
				$row['issues'][]   = sprintf(
					'Permalink %s does not match expected %s',
					$row['permalink'],
					$expected_url
				);
			}

			if ( '' !== (string) $spec['parent'] && 'shop' === $spec['parent'] ) {
				if ( ! $shop_ok ) {
					$row['status']   = 'mismatch';
					$row['issues'][] = 'WooCommerce shop page is missing; gateway children should live under /shop/.';
				} elseif ( (int) wp_get_post_parent_id( $page_id ) !== $shop_id ) {
					$row['status']   = 'mismatch';
					$row['issues'][] = 'Page is not a child of the WooCommerce shop page.';
				}
			}

			$results[] = $row;
		}

		return $results;
	}

	/**
	 * Create or update gateway pages to match specs (idempotent).
	 *
	 * @return array{created: int, updated: int, skipped: int}
	 */
	public static function ensure_pages(): array {
		$counts = array(
			'created' => 0,
			'updated' => 0,
			'skipped' => 0,
		);

		$shop_id = function_exists( 'wc_get_page_id' ) ? (int) wc_get_page_id( 'shop' ) : 0;
		if ( $shop_id <= 0 || 'publish' !== get_post_status( $shop_id ) ) {
			throw new RuntimeException( 'WooCommerce shop page is not published. Run: wp wc tool run install_pages --user=1' );
		}

		foreach ( self::get_specs() as $spec ) {
			$template  = (string) $spec['template'];
			$page_id   = self::find_page_id_by_template( $template );
			$parent_id = '';

			if ( '' !== (string) $spec['parent'] && 'shop' === $spec['parent'] ) {
				$parent_id = $shop_id;
			}

			$postarr = array(
				'post_title'   => (string) $spec['title'],
				'post_name'    => sanitize_title( (string) $spec['slug'] ),
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_parent'  => (int) $parent_id,
				'post_content' => '',
			);

			if ( $page_id ) {
				$postarr['ID'] = $page_id;
				$needs_update  = false;

				foreach ( array( 'post_title', 'post_name', 'post_parent' ) as $field ) {
					$current = get_post_field( $field, $page_id );
					if ( (string) $current !== (string) $postarr[ $field ] ) {
						$needs_update = true;
						break;
					}
				}

				if ( $needs_update ) {
					$result = wp_update_post( $postarr, true );
					if ( is_wp_error( $result ) ) {
						throw new RuntimeException( $result->get_error_message() );
					}
					++$counts['updated'];
				} else {
					++$counts['skipped'];
				}
			} else {
				$page_id = wp_insert_post( $postarr, true );
				if ( is_wp_error( $page_id ) ) {
					throw new RuntimeException( $page_id->get_error_message() );
				}
				++$counts['created'];
			}

			update_post_meta( (int) $page_id, '_wp_page_template', $template );
		}

		if ( class_exists( 'ResQ_Core_Lane_Routing' ) ) {
			ResQ_Core_Lane_Routing::flush_rewrite_rules();
		} else {
			flush_rewrite_rules();
		}

		return $counts;
	}

	/**
	 * Find a published page ID by template filename.
	 *
	 * @param string $template Page template filename.
	 * @return int
	 */
	private static function find_page_id_by_template( string $template ): int {
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'meta_key'       => '_wp_page_template',
				'meta_value'     => $template,
				'fields'         => 'ids',
			)
		);

		return ! empty( $pages ) ? (int) $pages[0] : 0;
	}
}
