<?php
/**
 * Tasty Theme functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package functions
 * @since 1.0
 */

// DO NOT EDIT these constants for any reason
define( 'TASTY_VERSION', '1.0' );
define( 'TASTY_NAME', 'tasty' );
define( 'TASTY_THEME_DIR', TEMPLATEPATH );
define( 'TASTY_THEME_URL', get_template_directory_uri() );
define( 'TASTY_API_DIR', TASTY_THEME_DIR . '/api' );
define( 'TASTY_API_URL', TASTY_THEME_URL . '/api' );
define( 'TASTY_PIE_DIR', TASTY_API_DIR . '/pie' );
define( 'TASTY_PIE_URL', TASTY_API_URL . '/pie' );
define( 'TASTY_CONF_DIR', TASTY_THEME_DIR . '/config' );
define( 'TASTY_ADMIN_DIR', TASTY_THEME_DIR . '/dashboard' );
define( 'TASTY_ADMIN_URL', TASTY_THEME_URL . '/dashboard' );
define( 'TASTY_EXTRAS_DIR', get_theme_root() . '/tasty-extras' );
define( 'TASTY_EXTRAS_URL', get_theme_root_uri() . '/tasty-extras' );
define( 'TASTY_TEXT_DOMAIN', TASTY_NAME );

// load PIE and initialize
require_once( TASTY_PIE_DIR . '/loader.php' );
Pie_Easy_Loader::init( TASTY_PIE_URL );

// load Tasty API
require_once( TASTY_API_DIR . '/scheme.php' );
require_once( TASTY_API_DIR . '/options.php' );
require_once( TASTY_API_DIR . '/features.php' );
require_once( TASTY_API_DIR . '/l10n.php' );

// initialize scheme
tasty_scheme_init();

if ( is_admin() ) {
	// only load admin functionality if the dashboard is actually loaded
	require_once( TASTY_ADMIN_DIR . '/loader.php' );
} else {
	// ???
}

?>
