<?php
/**
 * Gateway page hero — WP title and editorial intro.
 *
 * @package ResQ_Clean_Pro
 *
 * @var string $gateway_slug Gateway slug passed from parent template.
 * @var array  $context    Resolved product context from plugin helper.
 */

defined( 'ABSPATH' ) || exit;

$gateway_slug = isset( $gateway_slug ) ? sanitize_key( (string) $gateway_slug ) : '';
$context      = isset( $context ) && is_array( $context ) ? $context : array();
?>

<header class="resq-gateway__hero">
	<h1 class="resq-gateway__hero-title"><?php the_title(); ?></h1>

	<?php if ( get_the_content() ) : ?>
		<div class="resq-gateway__hero-intro entry-content">
			<?php the_content(); ?>
		</div>
	<?php endif; ?>
</header>
