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
define( 'BP_TASTY_VERSION', '1.0' );
define( 'BP_TASTY_NAME', 'bp-tasty' );
define( 'BP_TASTY_THEME_DIR', STYLESHEETPATH );
define( 'BP_TASTY_THEME_URL', get_stylesheet_directory_uri() );
define( 'BP_TASTY_API_DIR', BP_TASTY_THEME_DIR . '/api' );
define( 'BP_TASTY_API_URL', BP_TASTY_THEME_URL . '/api' );
define( 'BP_TASTY_PIE_DIR', BP_TASTY_API_DIR . '/pie' );
define( 'BP_TASTY_PIE_URL', BP_TASTY_API_URL . '/pie' );
define( 'BP_TASTY_CONF_DIR', BP_TASTY_THEME_DIR . '/config' );
define( 'BP_TASTY_ADMIN_DIR', BP_TASTY_THEME_DIR . '/dashboard' );
define( 'BP_TASTY_ADMIN_URL', BP_TASTY_THEME_URL . '/dashboard' );
define( 'BP_TASTY_EXTRAS_DIR',  get_theme_root() . '/bp-tasty-extras' );
define( 'BP_TASTY_TEXT_DOMAIN',  BP_TASTY_NAME );

// load PIE and initialize
require_once( BP_TASTY_PIE_DIR . '/loader.php' );
Pie_Easy_Loader::init( BP_TASTY_PIE_URL );

// load Tasty API
require_once( BP_TASTY_API_DIR . '/options.php' );
require_once( BP_TASTY_API_DIR . '/l10n.php' );
require_once( BP_TASTY_API_DIR . '/skinning.php' );

// initialize registry (just in case)
bp_tasty_options_registry_init();

// only load admin functionality if the dashboard is actually loaded
if ( is_admin() ) {
	require_once( BP_TASTY_ADMIN_DIR . '/loader.php' );
}

?>
