<?php
/**
 * My Account page shell.
 *
 * Wraps WooCommerce account navigation and content with theme classes.
 * Account logic, endpoints, and forms remain fully WooCommerce-owned.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-MyAccount resq-my-account">

	<?php do_action( 'woocommerce_account_navigation' ); ?>

	<div class="woocommerce-MyAccount-content resq-my-account__content">
		<?php do_action( 'woocommerce_account_content' ); ?>
	</div>

</div>
