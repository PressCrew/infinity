<?php
/**
 * BP Tasty Theme functions
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
define( 'TASTY_THEME_DIR', STYLESHEETPATH );
define( 'TASTY_THEME_URL', get_stylesheet_directory_uri() );
define( 'TASTY_API_DIR', TASTY_THEME_DIR . '/api' );
define( 'TASTY_API_URL', TASTY_THEME_URL . '/api' );
define( 'TASTY_PIE_DIR', TASTY_API_DIR . '/pie' );
define( 'TASTY_PIE_URL', TASTY_API_URL . '/pie' );
define( 'TASTY_CONF_DIR', TASTY_THEME_DIR . '/config' );
define( 'TASTY_ADMIN_DIR', TASTY_THEME_DIR . '/dashboard' );
define( 'TASTY_ADMIN_URL', TASTY_THEME_URL . '/dashboard' );
define( 'TASTY_EXTRAS_DIR',  get_theme_root() . '/tasty-extras' );
define( 'TASTY_EXTRAS_URL', get_theme_root_uri() . '/tasty-extras' );
define( 'TASTY_EXTRAS_SKIN_DIR', TASTY_EXTRAS_DIR . '/skins' );
define( 'TASTY_EXTRAS_SKIN_URL', TASTY_EXTRAS_URL . '/skins' );
define( 'TASTY_TEXT_DOMAIN',  TASTY_NAME );

// load PIE and initialize
require_once( TASTY_PIE_DIR . '/loader.php' );
Pie_Easy_Loader::init( TASTY_PIE_URL );

// load Tasty API
require_once( TASTY_API_DIR . '/options.php' );
require_once( TASTY_API_DIR . '/l10n.php' );
require_once( TASTY_API_DIR . '/skinning.php' );

// load skin functions
tasty_skin_load_functions();

if ( is_admin() ) {
	// only load admin functionality if the dashboard is actually loaded
	require_once( TASTY_ADMIN_DIR . '/loader.php' );
} else {
	// enqueue skin assets
	tasty_skin_enqueue_assets();
	// initialize global registry
	tasty_options_registry_init();
}

?>
