<?php
/**
 * Public storefront branding helpers.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_core_get_brand_name' ) ) {
	/**
	 * Merchant-facing brand name for titles, alt text, and meta.
	 *
	 * @return string
	 */
	function resq_core_get_brand_name(): string {
		return 'ResQ Organics';
	}
}

if ( ! function_exists( 'resq_core_normalize_public_brand_text' ) ) {
	/**
	 * Replace dev/staging site labels with the public brand name.
	 *
	 * @param string $text Raw text from options, titles, or attachment alt.
	 * @return string
	 */
	function resq_core_normalize_public_brand_text( string $text ): string {
		$text = trim( $text );
		if ( '' === $text ) {
			return $text;
		}

		$patterns = array(
			'/resq[\s\-_]*foundation[\s\-_]*kit/i',
			'/resq[\s\-_]*foundation/i',
		);

		$normalized = preg_replace( $patterns, resq_core_get_brand_name(), $text );

		return is_string( $normalized ) ? $normalized : $text;
	}
}

if ( ! function_exists( 'resq_core_get_concern_root_slug_for_audience' ) ) {
	/**
	 * Top-level resq_concern parent slug for an audience gateway/lane.
	 *
	 * @param string $audience Audience slug: human or pet.
	 * @return string Empty when unknown.
	 */
	function resq_core_get_concern_root_slug_for_audience( string $audience ): string {
		$map = array(
			'human' => 'human-skincare',
			'pet'   => 'pet-topical',
		);

		return $map[ sanitize_key( $audience ) ] ?? '';
	}
}

if ( ! function_exists( 'resq_core_filter_concern_terms_for_audience' ) ) {
	/**
	 * Limit concern filter terms to the audience-scoped branch.
	 *
	 * @param WP_Term[] $terms    Concern terms from get_terms().
	 * @param string    $audience Audience slug from gateway/lane context.
	 * @return WP_Term[]
	 */
	function resq_core_filter_concern_terms_for_audience( array $terms, string $audience ): array {
		$root_slug = resq_core_get_concern_root_slug_for_audience( $audience );
		if ( '' === $root_slug || empty( $terms ) ) {
			return $terms;
		}

		$root = get_term_by( 'slug', $root_slug, 'resq_concern' );
		if ( ! $root || is_wp_error( $root ) ) {
			return $terms;
		}

		$root_id = (int) $root->term_id;

		return array_values(
			array_filter(
				$terms,
				static function ( $term ) use ( $root_id ): bool {
					if ( ! $term instanceof WP_Term ) {
						return false;
					}

					return (int) $term->parent === $root_id;
				}
			)
		);
	}
}
