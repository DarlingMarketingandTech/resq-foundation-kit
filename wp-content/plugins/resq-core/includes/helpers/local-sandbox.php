<?php
/**
 * Local sandbox helpers — dev-only visibility for compliance surfaces.
 *
 * On local/development environments, empty owner-sign-off strings must not hide
 * built UI. Production/staging keeps owner-gated silence until copy is approved.
 *
 * @package ResQ_Core
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_core_is_local_sandbox' ) ) {
	/**
	 * Whether the site is a local development sandbox.
	 *
	 * Detection order: `resq_core_is_local_sandbox` filter, WP environment type
	 * (`local` / `development`), then common dev host suffixes (.local, .test).
	 *
	 * @return bool
	 */
	function resq_core_is_local_sandbox(): bool {
		$filtered = apply_filters( 'resq_core_is_local_sandbox', null );
		if ( null !== $filtered ) {
			return (bool) $filtered;
		}

		if ( function_exists( 'wp_get_environment_type' ) ) {
			$env = wp_get_environment_type();
			if ( in_array( $env, array( 'local', 'development' ), true ) ) {
				return true;
			}
		}

		$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
		if ( '' !== $host ) {
			foreach ( array( '.local', '.test', '.localhost' ) as $suffix ) {
				if ( str_ends_with( $host, $suffix ) ) {
					return true;
				}
			}
		}

		return false;
	}
}

if ( ! function_exists( 'resq_core_dev_compliance_notice_text' ) ) {
	/**
	 * Claim-safe placeholder notice copy for a compliance zone (local sandbox only).
	 *
	 * @param string $zone Compliance zone slug.
	 * @return string Empty when no dev placeholder exists for the zone.
	 */
	function resq_core_dev_compliance_notice_text( string $zone ): string {
		if ( ! resq_core_is_local_sandbox() ) {
			return '';
		}

		$placeholders = array(
			'cbd'        => __( '[Dev preview] CBD products are intended for adults 21+. Hemp-derived products; final disclaimer copy pending owner/legal review.', 'resq-core' ),
			'baby'       => __( '[Dev preview] For delicate skin; follow label directions. Final baby notice pending owner/legal review.', 'resq-core' ),
			'pet-health' => __( '[Dev preview] Not a substitute for veterinary care. Consult your veterinarian for persistent symptoms. Final notice pending owner/legal review.', 'resq-core' ),
			'standard'   => __( '[Dev preview] General product information; final notice pending owner review.', 'resq-core' ),
		);

		$zone = sanitize_key( $zone );

		return $placeholders[ $zone ] ?? '';
	}
}

if ( ! function_exists( 'resq_core_dev_cbd_disclosure' ) ) {
	/**
	 * Placeholder COA / THC disclosure for CBD PDP slot (local sandbox only).
	 *
	 * @return array{coa_url: string, thc_disclosure: string, dev_preview: bool}
	 */
	function resq_core_dev_cbd_disclosure(): array {
		return array(
			'coa_url'         => '#dev-coa-placeholder',
			'thc_disclosure'  => __( '< 0.3% THC (dev placeholder)', 'resq-core' ),
			'dev_preview'     => true,
		);
	}
}

if ( ! function_exists( 'resq_core_compliance_enforcement_active' ) ) {
	/**
	 * Whether checkout/cart compliance enforcement should run.
	 *
	 * Disabled on local sandbox so mixed carts and checkout flows are testable.
	 *
	 * @return bool
	 */
	function resq_core_compliance_enforcement_active(): bool {
		if ( resq_core_is_local_sandbox() ) {
			return false;
		}

		return true;
	}
}
