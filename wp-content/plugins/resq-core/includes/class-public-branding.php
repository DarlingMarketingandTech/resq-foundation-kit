<?php
/**
 * Public-facing brand normalization for titles and image alt text.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * ResQ_Core_Public_Branding
 */
class ResQ_Core_Public_Branding {

	/**
	 * Register presentation filters.
	 */
	public static function init(): void {
		add_filter( 'document_title_parts', array( __CLASS__, 'filter_document_title_parts' ) );
		add_filter( 'bloginfo', array( __CLASS__, 'filter_bloginfo' ), 10, 2 );
		add_filter( 'wp_get_attachment_image_attributes', array( __CLASS__, 'filter_attachment_image_attributes' ), 10, 3 );
	}

	/**
	 * Normalize title tag segments.
	 *
	 * @param array<string, string> $parts Title parts.
	 * @return array<string, string>
	 */
	public static function filter_document_title_parts( array $parts ): array {
		foreach ( array( 'title', 'site', 'tagline' ) as $key ) {
			if ( ! empty( $parts[ $key ] ) && is_string( $parts[ $key ] ) ) {
				$parts[ $key ] = resq_core_normalize_public_brand_text( $parts[ $key ] );
			}
		}

		return $parts;
	}

	/**
	 * Normalize public site name output.
	 *
	 * @param string $output Bloginfo value.
	 * @param string $show   Bloginfo field.
	 * @return string
	 */
	public static function filter_bloginfo( string $output, string $show ): string {
		if ( 'name' !== $show || '' === $output ) {
			return $output;
		}

		return resq_core_normalize_public_brand_text( $output );
	}

	/**
	 * Normalize attachment alt attributes on the storefront.
	 *
	 * @param array<string, string> $attr       Image attributes.
	 * @param WP_Post               $attachment Attachment post.
	 * @param string|int[]          $size       Image size.
	 * @return array<string, string>
	 */
	public static function filter_attachment_image_attributes( array $attr, WP_Post $attachment, $size ): array {
		unset( $attachment, $size );

		if ( ! empty( $attr['alt'] ) && is_string( $attr['alt'] ) ) {
			$attr['alt'] = resq_core_normalize_public_brand_text( $attr['alt'] );
		}

		return $attr;
	}
}
