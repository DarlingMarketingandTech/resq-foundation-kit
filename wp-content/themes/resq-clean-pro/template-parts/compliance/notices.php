<?php
/**
 * Compliance notice slot markup.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array  $notices Notice objects from resq_core_get_compliance_notices().
 * @var string $context Display context slug.
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $notices ) || ! is_array( $notices ) ) {
	return;
}
?>

<div class="resq-compliance-notices" role="region" aria-label="<?php esc_attr_e( 'Compliance notices', 'resq-clean-pro' ); ?>">
	<?php foreach ( $notices as $notice ) : ?>
		<?php if ( empty( $notice['text'] ) ) : ?>
			<?php continue; ?>
		<?php endif; ?>
		<div class="resq-compliance-notice<?php echo ! empty( $notice['dev_preview'] ) ? ' resq-compliance-notice--dev-preview' : ''; ?>">
			<?php echo esc_html( (string) $notice['text'] ); ?>
		</div>
	<?php endforeach; ?>
</div>
