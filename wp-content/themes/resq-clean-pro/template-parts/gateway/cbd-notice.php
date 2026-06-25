<?php
/**
 * CBD gateway compliance notice strip.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="resq-cbd-notice" role="region" aria-label="<?php esc_attr_e( 'CBD compliance notice', 'resq-clean-pro' ); ?>">
	<?php resq_theme_render_compliance_notices( 'gateway', 0, 'cbd' ); ?>
</div>
