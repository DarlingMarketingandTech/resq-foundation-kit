<?php
/**
 * Theme helper functions.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_theme_get_asset_url' ) ) {
	/**
	 * Return a versioned theme asset URL.
	 *
	 * @param string $path Relative path from theme root, e.g. assets/css/tokens.css.
	 * @return string
	 */
	function resq_theme_get_asset_url( string $path ): string {
		$path = ltrim( $path, '/' );
		return add_query_arg( 'ver', RESQ_THEME_VERSION, RESQ_THEME_URI . '/' . $path );
	}
}

if ( ! function_exists( 'resq_theme_render_image' ) ) {
	/**
	 * Render a Media Library image by attachment slug, with placeholder fallback.
	 *
	 * @param string $slug Attachment post_name, usually the upload filename without extension.
	 * @param array  $args {
	 *     @type string       $class               Wrapper class.
	 *     @type string       $image_class         Image class.
	 *     @type string|array $size                WordPress image size.
	 *     @type string       $alt                 Image alt text.
	 *     @type string       $label               Placeholder label.
	 *     @type string       $placeholder_variant Existing placeholder modifier class.
	 *     @type string       $loading             Image loading attribute.
	 *     @type string       $decoding            Image decoding attribute.
	 * }
	 */
	function resq_theme_render_image( string $slug, array $args = array() ): void {
		$defaults = array(
			'class'               => 'resq-image',
			'image_class'         => '',
			'size'                => 'large',
			'alt'                 => '',
			'label'               => '',
			'placeholder_variant' => '',
			'loading'             => 'lazy',
			'decoding'            => 'async',
		);

		$args = wp_parse_args( $args, $defaults );

		$class       = trim( (string) $args['class'] );
		$image_class = trim( (string) $args['image_class'] );
		$alt         = (string) $args['alt'];
		$label       = (string) $args['label'];
		$variant     = trim( (string) $args['placeholder_variant'] );

		$attachment_id = function_exists( 'resq_core_get_attachment_id_by_slug' )
			? resq_core_get_attachment_id_by_slug( $slug )
			: 0;

		if ( $attachment_id > 0 ) {
			$attrs = array(
				'alt'      => $alt,
				'loading'  => (string) $args['loading'],
				'decoding' => (string) $args['decoding'],
			);

			if ( '' !== $image_class ) {
				$attrs['class'] = $image_class;
			}

			printf(
				'<div class="%1$s">%2$s</div>',
				esc_attr( $class ),
				wp_get_attachment_image( $attachment_id, $args['size'], false, $attrs )
			);
			return;
		}

		$placeholder_class = trim( $class . ' resq-img-placeholder ' . $variant );
		printf(
			'<div class="%1$s" role="img" aria-label="%2$s">',
			esc_attr( $placeholder_class ),
			esc_attr( $alt )
		);

		if ( '' !== $label ) {
			printf(
				'<span class="resq-img-placeholder__label">%s</span>',
				esc_html( $label )
			);
		}

		echo '</div>';
	}
}

if ( ! function_exists( 'resq_theme_get_site_logo_attachment_id' ) ) {
	/**
	 * Resolve the site logo attachment from Customizer or a known Media Library slug.
	 *
	 * @return int Attachment ID, or 0 when no logo is available.
	 */
	function resq_theme_get_site_logo_attachment_id(): int {
		$custom_logo_id = (int) get_theme_mod( 'custom_logo', 0 );
		if ( $custom_logo_id > 0 ) {
			return $custom_logo_id;
		}

		if ( ! function_exists( 'resq_core_get_attachment_id_by_slug' ) ) {
			return 0;
		}

		$logo_slugs = array(
			'pet_resq-organics-brand_brand_resq-logo-bold-transparent_01',
			'pet_resq-organics-brand_brand_resq-organics-cbd-logo_01',
			'resq-organics-cbd-logo-768x453',
		);

		foreach ( $logo_slugs as $slug ) {
			$attachment_id = resq_core_get_attachment_id_by_slug( $slug );
			if ( $attachment_id > 0 ) {
				return $attachment_id;
			}
		}

		return 0;
	}
}

if ( ! function_exists( 'resq_theme_render_site_logo' ) ) {
	/**
	 * Render the site logo image or the text fallback.
	 *
	 * @param string $class Optional wrapper class.
	 */
	function resq_theme_render_site_logo( string $class = 'site-branding__logo' ): void {
		$attachment_id = resq_theme_get_site_logo_attachment_id();

		if ( $attachment_id > 0 ) {
			printf(
				'<span class="%1$s">%2$s</span>',
				esc_attr( $class ),
				wp_get_attachment_image(
					$attachment_id,
					'medium',
					false,
					array(
						'class'   => $class . '-image',
						'alt'     => get_bloginfo( 'name', 'display' ),
						'loading' => 'eager',
						'decoding' => 'async',
					)
				)
			);
			return;
		}

		echo esc_html__( 'ResQ', 'resq-clean-pro' );
	}
}

if ( ! function_exists( 'resq_theme_template_part' ) ) {
	/**
	 * Load a template part with optional args.
	 *
	 * @param string $slug Template slug under template-parts/.
	 * @param string $name Optional name suffix.
	 * @param array  $args Variables exposed in the part scope.
	 */
	function resq_theme_template_part( string $slug, string $name = '', array $args = array() ): void {
		if ( ! empty( $args ) ) {
			// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
			extract( $args, EXTR_SKIP );
		}

		$templates = array();
		if ( '' !== $name ) {
			$templates[] = "template-parts/{$slug}-{$name}.php";
		}
		$templates[] = "template-parts/{$slug}.php";

		$located = locate_template( $templates, false, false );
		if ( '' !== $located ) {
			require $located;
		}
	}
}

if ( ! function_exists( 'resq_theme_class' ) ) {
	/**
	 * Build a BEM-style class string.
	 *
	 * @param string $base       Base class name.
	 * @param array  $modifiers  Modifier suffixes appended as base--modifier.
	 * @return string
	 */
	function resq_theme_class( string $base, array $modifiers = array() ): string {
		$classes = array( $base );

		foreach ( $modifiers as $modifier ) {
			$modifier = sanitize_html_class( (string) $modifier );
			if ( '' !== $modifier ) {
				$classes[] = $base . '--' . $modifier;
			}
		}

		return implode( ' ', $classes );
	}
}

if ( ! function_exists( 'resq_theme_wc_active' ) ) {
	/**
	 * Whether WooCommerce is active.
	 *
	 * @return bool
	 */
	function resq_theme_wc_active(): bool {
		return class_exists( 'WooCommerce' ) && function_exists( 'WC' );
	}
}

if ( ! function_exists( 'resq_theme_get_cart_count' ) ) {
	/**
	 * Return cart item count for header display.
	 *
	 * @return int
	 */
	function resq_theme_get_cart_count(): int {
		if ( ! resq_theme_wc_active() || ! function_exists( 'WC' ) ) {
			return 0;
		}

		$woocommerce = WC();
		if ( ! $woocommerce || ! isset( $woocommerce->cart ) || ! $woocommerce->cart ) {
			return 0;
		}

		return (int) $woocommerce->cart->get_cart_contents_count();
	}
}

if ( ! function_exists( 'resq_theme_render_compliance_notices' ) ) {
	/**
	 * Render compliance notice slot via plugin helper when available.
	 *
	 * Pass $zone (with $product_id left at 0) for context-level slots that are
	 * scoped by compliance zone rather than a specific product — e.g. the CBD
	 * gateway disclaimer strip.
	 *
	 * @param string $context    Display context.
	 * @param int    $product_id Optional product ID (product-scoped slots).
	 * @param string $zone       Optional compliance zone slug (zone-scoped slots).
	 */
	function resq_theme_render_compliance_notices( string $context, int $product_id = 0, string $zone = '' ): void {
		if ( ! function_exists( 'resq_core_get_compliance_notices' ) ) {
			return;
		}

		$notices = resq_core_get_compliance_notices( $context, $product_id, $zone );
		if ( empty( $notices ) ) {
			return;
		}

		resq_theme_template_part(
			'compliance/notices',
			'',
			array(
				'notices' => $notices,
				'context' => $context,
			)
		);
	}
}

if ( ! function_exists( 'resq_theme_render_badge' ) ) {
	/**
	 * Render product badge markup from plugin data.
	 *
	 * @param int $product_id Product ID.
	 */
	function resq_theme_render_badge( int $product_id ): void {
		if ( $product_id <= 0 || ! function_exists( 'resq_get_product_badges' ) ) {
			return;
		}

		$badges = resq_get_product_badges( $product_id );
		if ( empty( $badges ) ) {
			return;
		}

		$badge = $badges[0];
		printf(
			'<span class="resq-badge resq-badge--%1$s">%2$s</span>',
			esc_attr( sanitize_html_class( (string) ( $badge['type'] ?? 'default' ) ) ),
			esc_html( (string) ( $badge['label'] ?? '' ) )
		);
	}
}
