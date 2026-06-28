<?php
/**
 * Lane proof block — gated/empty-safe; disabled until substantiated assets exist.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $content Lane copy.
 * @var array $lane    Lane data.
 */

defined( 'ABSPATH' ) || exit;

$content = isset( $content ) && is_array( $content ) ? $content : array();
$proof   = $content['proof'] ?? array();

if ( ! is_array( $proof ) || empty( $proof['enabled'] ) ) {
	if ( ! empty( $proof['disclaimer'] ) ) {
		?>
		<p class="resq-lane-proof__disclaimer"><?php echo esc_html( (string) $proof['disclaimer'] ); ?></p>
		<?php
	}
	return;
}
?>

<section class="resq-lane-proof" aria-labelledby="resq-lane-proof-title">
	<h2 id="resq-lane-proof-title" class="resq-lane-proof__title">
		<?php esc_html_e( 'Trusted by families like yours', 'resq-clean-pro' ); ?>
	</h2>

	<?php if ( ! empty( $proof['quote'] ) ) : ?>
		<blockquote class="resq-lane-proof__quote">
			<p><?php echo esc_html( (string) $proof['quote'] ); ?></p>
		</blockquote>
	<?php endif; ?>

	<?php if ( ! empty( $proof['disclaimer'] ) ) : ?>
		<p class="resq-lane-proof__disclaimer"><?php echo esc_html( (string) $proof['disclaimer'] ); ?></p>
	<?php endif; ?>
</section>
