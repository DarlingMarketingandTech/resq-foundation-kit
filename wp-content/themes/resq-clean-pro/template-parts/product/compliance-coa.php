<?php
/**
 * PDP CBD compliance slot — Certificate of Analysis link + THC disclosure.
 *
 * Phase 10 A1. Renders only for CBD products that have COA/THC data populated
 * (resolved by the plugin helper). Pure presentation; the plugin owns the
 * feature flag, the CBD-lane decision, and the data.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if (
	! function_exists( 'resq_core_is_active' )
	|| ! resq_core_is_active()
	|| ! function_exists( 'resq_get_product_cbd_disclosure' )
) {
	return;
}

global $product;

if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
	return;
}

$disclosure = resq_get_product_cbd_disclosure( (int) $product->get_id() );

if ( empty( $disclosure ) ) {
	return;
}

$coa_url = (string) ( $disclosure['coa_url'] ?? '' );
$thc     = (string) ( $disclosure['thc_disclosure'] ?? '' );
$is_dev  = ! empty( $disclosure['dev_preview'] );
?>
<section class="resq-cbd-disclosure<?php echo $is_dev ? ' resq-cbd-disclosure--dev-preview' : ''; ?>" aria-labelledby="resq-cbd-disclosure-heading">
	<h3 id="resq-cbd-disclosure-heading" class="resq-cbd-disclosure__title">
		<?php esc_html_e( 'Lab Testing & Disclosure', 'resq-clean-pro' ); ?>
	</h3>

	<?php if ( $is_dev ) : ?>
		<p class="resq-cbd-disclosure__dev-note"><?php esc_html_e( 'Dev preview — replace with owner-approved COA and THC values before production.', 'resq-clean-pro' ); ?></p>
	<?php endif; ?>

	<ul class="resq-cbd-disclosure__list">
		<?php if ( '' !== $thc ) : ?>
			<li class="resq-cbd-disclosure__item resq-cbd-disclosure__item--thc">
				<span class="resq-cbd-disclosure__label"><?php esc_html_e( 'THC content:', 'resq-clean-pro' ); ?></span>
				<span class="resq-cbd-disclosure__value"><?php echo esc_html( $thc ); ?></span>
			</li>
		<?php endif; ?>

		<?php if ( '' !== $coa_url ) : ?>
			<li class="resq-cbd-disclosure__item resq-cbd-disclosure__item--coa">
				<a
					class="resq-cbd-disclosure__coa-link"
					href="<?php echo esc_url( $coa_url ); ?>"
					target="_blank"
					rel="noopener noreferrer"
				>
					<?php esc_html_e( 'View Certificate of Analysis (COA)', 'resq-clean-pro' ); ?>
					<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'resq-clean-pro' ); ?></span>
				</a>
			</li>
		<?php endif; ?>
	</ul>
</section>
