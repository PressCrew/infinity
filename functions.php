<?php
/**
 * Infinity Theme functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package functions
 * @since 1.0
 */

// DO NOT EDIT these constants for any reason
define( 'INFINITY_VERSION', '1.0' );
define( 'INFINITY_NAME', 'infinity' );
define( 'INFINITY_THEME_DIR', TEMPLATEPATH );
define( 'INFINITY_THEME_URL', get_template_directory_uri() );
define( 'INFINITY_API_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'api' );
define( 'INFINITY_API_URL', INFINITY_THEME_URL . '/api' );
define( 'INFINITY_PIE_DIR', INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'pie' );
define( 'INFINITY_PIE_URL', INFINITY_API_URL . '/pie' );
define( 'INFINITY_CONF_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'config' );
define( 'INFINITY_ADMIN_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'dashboard' );
define( 'INFINITY_ADMIN_URL', INFINITY_THEME_URL . '/dashboard' );
define( 'INFINITY_EXPORT_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'export' );
define( 'INFINITY_EXPORT_URL', INFINITY_THEME_URL . '/export' );
define( 'INFINITY_EXTRAS_DIR', get_theme_root() . DIRECTORY_SEPARATOR . 'infinity-extras' );
define( 'INFINITY_EXTRAS_URL', get_theme_root_uri() . '/infinity-extras' );
define( 'INFINITY_TEXT_DOMAIN', INFINITY_NAME );
define( 'INFINITY_ADMIN_PAGE', 'infinity-theme' );
define( 'INFINITY_ADMIN_TPLS_DIR', INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'templates' );
define( 'INFINITY_ADMIN_DOCS_DIR', INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'docs' );

// load PIE and initialize
require_once( INFINITY_PIE_DIR . DIRECTORY_SEPARATOR . 'loader.php' );
Pie_Easy_Loader::init( INFINITY_PIE_URL );

// load Infinity API
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'scheme.php' );
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'options.php' );
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'features.php' );
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'l10n.php' );

// initialize scheme
infinity_scheme_init();

if ( is_admin() ) {
	// only load admin functionality if the dashboard is actually loaded
	require_once( INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'loader.php' );
} else {
	// some features need initialization
	Infinity_Features::init();
}

?>
