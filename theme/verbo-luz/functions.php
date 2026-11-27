<?php
/**
 * Verbo Luz: arranque del tema.
 */
defined( 'ABSPATH' ) || exit;

define( 'VERBO_LUZ_VERSION', '0.1.0' );
define( 'VERBO_LUZ_DIR', get_template_directory() );
define( 'VERBO_LUZ_URI', get_template_directory_uri() );

require_once VERBO_LUZ_DIR . '/inc/setup.php';
require_once VERBO_LUZ_DIR . '/inc/assets.php';
require_once VERBO_LUZ_DIR . '/inc/post-types.php';
