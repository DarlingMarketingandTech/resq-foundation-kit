<?php
/**
 * Lane draft review banner — shown on draft lanes pending legal sign-off.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $lane Lane data.
 */

defined( 'ABSPATH' ) || exit;

$lane = isset( $lane ) && is_array( $lane ) ? $lane : array();
?>
<aside class="resq-lane-draft-banner" role="note" aria-label="<?php esc_attr_e( 'Draft content notice', 'resq-clean-pro' ); ?>">
	<p class="resq-lane-draft-banner__text">
		<strong><?php esc_html_e( 'Draft lane — review required.', 'resq-clean-pro' ); ?></strong>
		<?php esc_html_e( 'This presentation copy is not approved production content. Owner/legal sign-off is required before publish.', 'resq-clean-pro' ); ?>
	</p>
</aside>
