<?php
/**
 * Header template.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text" href="#primary-content"><?php esc_html_e( 'Skip to content', 'resq-clean-pro' ); ?></a>

<?php get_template_part( 'template-parts/global/header', 'navigation' ); ?>
