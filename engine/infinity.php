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
 * Infinity slug
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
 * Infinity theme name (slug)
 */
if ( !defined( 'INFINITY_NAME' ) ) {
	define( 'INFINITY_NAME', basename( INFINITY_PATH ) );
}

/**
 * Infinity post meta key prefix
 */
if ( !defined( 'INFINITY_META_KEY_PREFIX' ) ) {
	define( 'INFINITY_META_KEY_PREFIX', '_infinity_' );
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
 * Infinity admin directory relative path
 */
define( 'INFINITY_ADMIN_DIR', 'dashboard' );

/**
 * Infinity admin directory absolute path
 */
define( 'INFINITY_ADMIN_PATH', INFINITY_THEME_PATH . '/' . INFINITY_ADMIN_DIR );

/**
 * Infinity admin directory url
 */
define( 'INFINITY_ADMIN_URL', INFINITY_THEME_URL . '/' . INFINITY_ADMIN_DIR );

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

// initialize scheme
infinity_scheme_init();
