<?php
/**
 * ResQ Clean Pro — theme functions and definitions.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

define( 'RESQ_THEME_VERSION', '0.2.0' );
define( 'RESQ_THEME_DIR', get_template_directory() );
define( 'RESQ_THEME_URI', get_template_directory_uri() );

require_once RESQ_THEME_DIR . '/inc/setup.php';
require_once RESQ_THEME_DIR . '/inc/helpers.php';
require_once RESQ_THEME_DIR . '/inc/navigation.php';
require_once RESQ_THEME_DIR . '/inc/assets.php';
