<?php
/**
 * Infinity Theme: application loader
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity-api
 * @since 1.0
 */

//
// Read only onfiguration constants
//
// DO NOT EDIT these constants for any reason
//

/**
 * Infinity version number
 */
define( 'INFINITY_VERSION', '1.2a' );

/**
 * Infinity slug.
 *
 * IMPORTANT:
 * Do not use this constant for formatting file paths!
 * Use the INFINITY_NAME constant instead.
 */
define( 'INFINITY_SLUG', 'infinity' );

/**
 * Infinity "engine" directory path
 */
if ( !defined( 'INFINITY_ENGINE_PATH' ) ) {
	define( 'INFINITY_ENGINE_PATH', dirname( __FILE__ ) );
}

/**
 * Infinity "engine" directory name
 */
if ( !defined( 'INFINITY_ENGINE_DIR' ) ) {
	define( 'INFINITY_ENGINE_DIR', basename( INFINITY_ENGINE_PATH ) );
}

/**
 * Set the Infinity path
 */
if ( !defined( 'INFINITY_PATH' ) ) {
	define( 'INFINITY_PATH', dirname( INFINITY_ENGINE_PATH ) );
}

/**
 * Infinity theme name.
 *
 * The value is determined dynamically based on the path to the file in which it is defined.
 *
 * IMPORTANT:
 * This is the theme directory name *as currently installed* under /themes.
 * It *is* safe to use this constant for formatting file paths.
 */
if ( !defined( 'INFINITY_NAME' ) ) {
	define( 'INFINITY_NAME', basename( INFINITY_PATH ) );
}

/**
 * Infinity theme directory path
 */
define( 'INFINITY_THEME_PATH', realpath( get_theme_root( INFINITY_NAME ) . '/' . INFINITY_NAME ) );

/**
 * Infinity theme directory url
 */
define( 'INFINITY_THEME_URL', get_theme_root_uri( INFINITY_NAME ) . '/' . INFINITY_NAME );

/**
 * Infinity "base" (includes) url
 */
define( 'INFINITY_ENGINE_URL', INFINITY_THEME_URL . '/' . INFINITY_ENGINE_DIR );

/**
 * ICE directory path
 */
define( 'INFINITY_ICE_PATH', INFINITY_ENGINE_PATH . '/ICE' );

/**
 * ICE directory URL
 */
define( 'INFINITY_ICE_URL', INFINITY_ENGINE_URL . '/ICE' );

/**
 * Infinity application interface directory path
 */
define( 'INFINITY_API_PATH', INFINITY_ENGINE_PATH . '/api' );

/**
 * Infinity config directory path
 */
define( 'INFINITY_CONFIG_PATH', INFINITY_ENGINE_PATH . '/config' );

/**
 * Infinity includes directory path
 */
define( 'INFINITY_INC_PATH', INFINITY_ENGINE_PATH . '/includes' );

/**
 * Infinity plugins compat directory path
 */
define( 'INFINITY_PLUGINS_PATH', INFINITY_ENGINE_PATH . '/plugins' );

/**
 * Infinity dashboard setup directory path
 */
define( 'INFINITY_DASHBOARD_PATH', INFINITY_ENGINE_PATH . '/dashboard' );

/**
 * Infinity custom setup directory path
 */
define( 'INFINITY_CUSTOM_PATH', INFINITY_ENGINE_PATH . '/custom' );

/**
 * Infinity AJAX url
 */
define( 'INFINITY_AJAX_URL', admin_url( 'admin-ajax.php' ) );

/**
 * Infinity languages directory path
 */
define( 'INFINITY_LANGUAGES_PATH', INFINITY_THEME_PATH . '/languages' );

/**
 * Infinity admin page name
 */
define( 'INFINITY_ADMIN_PAGE', INFINITY_SLUG . '-theme' );

/**
 * Infinity development mode
 */
if ( !defined( 'INFINITY_DEV_MODE' ) ) {
	define( 'INFINITY_DEV_MODE', false );
}

/**
 * Load the ICE lib loader
 */
require_once INFINITY_ICE_PATH . '/loader.php';

// initialize ICE
ICE_Loader::init( INFINITY_SLUG, INFINITY_ICE_URL );
ICE_Enqueue::init( 'load-appearance_page_' . INFINITY_ADMIN_PAGE );

// load Infinity API
require_once INFINITY_API_PATH . '/loader.php';

// load theme includes
require_once INFINITY_ENGINE_PATH . '/includes.php';

// load theme plugins compat
require_once INFINITY_ENGINE_PATH . '/plugins.php';

// load dashboard setup
require_once INFINITY_ENGINE_PATH . '/dashboard.php';

// load custom setup
require_once INFINITY_ENGINE_PATH . '/custom.php';

// initialize scheme
infinity_scheme_init();

// infinity has been completely loaded
do_action( 'infinity_engine_loaded' );
