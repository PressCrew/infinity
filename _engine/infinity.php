<?php
/**
 * Infinity Theme: application loader
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage infinity-app
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
define( 'INFINITY_VERSION', '1.0b3' );

/**
 * Infinity version id number
 *
 * Examples:
 *  1.0   = 100
 *	1.5.3 = 153
 *  2.6   = 260
 */
define( 'INFINITY_VERSION_ID', 100 );

/**
 * Infinity theme name (slug)
 */
if ( !defined( 'INFINITY_NAME' ) ) {
	define( 'INFINITY_NAME', 'infinity' );
}

/**
 * Infinity theme directory path
 */
define( 'INFINITY_THEME_PATH', realpath( get_theme_root( INFINITY_NAME ) ) . '/' . INFINITY_NAME );

/**
 * Infinity theme directory url
 */
define( 'INFINITY_THEME_URL', get_theme_root_uri( INFINITY_NAME ) . '/' . INFINITY_NAME );

/**
 * Infinity "engine" directory name
 */
if ( !defined( 'INFINITY_ENGINE_DIR' ) ) {
	define( 'INFINITY_ENGINE_DIR', '_engine' );
}

/**
 * Infinity "engine" directory path
 */
define( 'INFINITY_ENGINE_PATH', INFINITY_THEME_PATH . '/' . INFINITY_ENGINE_DIR );

/**
 * Infinity "base" (includes) url
 */
define( 'INFINITY_ENGINE_URL', INFINITY_THEME_URL . '/' . INFINITY_ENGINE_DIR );

/**
 * ICE directory path
 */
define( 'INFINITY_ICE_PATH', INFINITY_ENGINE_PATH . '/ice' );

/**
 * ICE directory URL
 */
define( 'INFINITY_ICE_URL', INFINITY_ENGINE_URL . '/ice' );

/**
 * Infinity application directory path
 */
define( 'INFINITY_APP_PATH', INFINITY_ENGINE_PATH . '/app' );

/**
 * Infinity includes directory path
 */
define( 'INFINITY_INC_PATH', INFINITY_ENGINE_PATH . '/inc' );

/**
 * Infinity templates directory name
 */
define( 'INFINITY_SUBTPLS_DIR', 'templates' );

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
 * Infinity text domain
 */
define( 'INFINITY_TEXT_DOMAIN', INFINITY_NAME . '-theme' );

/**
 * Infinity text domain alias (for code completion)
 */
define( 'infinity_text_domain', INFINITY_TEXT_DOMAIN );

/**
 * Infinity admin page name
 */
define( 'INFINITY_ADMIN_PAGE', INFINITY_NAME . '-theme' );

/**
 * Infinity admin documentation directory path
 */
define( 'INFINITY_ADMIN_DOCS_PATH', INFINITY_ADMIN_PATH . '/docs' );

/**
 * Infinity development mode
 */
if ( !defined( 'INFINITY_DEV_MODE' ) ) {
	define( 'INFINITY_DEV_MODE', false );
}
	/**
	 * ICE exports caching toggle
	 */
	if ( !defined( 'ICE_CACHE_EXPORTS' ) ) {
		define( 'ICE_CACHE_EXPORTS', !INFINITY_DEV_MODE );
	}

/**
 * Infinity error handling
 */
if ( !defined( 'INFINITY_ERROR_HANDLING' ) ) {
	define( 'INFINITY_ERROR_HANDLING', false );
}
	/**
	 * ICE error handling
	 */
	if ( !defined( 'ICE_ERROR_HANDLING' ) ) {
		define( 'ICE_ERROR_HANDLING', INFINITY_ERROR_HANDLING );
	}

/**
 * Infinity error reporting
 */
if ( !defined( 'INFINITY_ERROR_REPORTING' ) ) {
	define( 'INFINITY_ERROR_REPORTING', INFINITY_ERROR_HANDLING );
}
	/**
	 * ICE error reporting
	 */
	if ( !defined( 'ICE_ERROR_REPORTING' ) ) {
		define( 'ICE_ERROR_REPORTING', INFINITY_ERROR_REPORTING );
	}

/**
 * Load the ICE lib loader
 */
require_once( INFINITY_ICE_PATH . '/loader.php' );

// initialize ICE
ICE_Loader::init( INFINITY_ICE_URL );

// initialize enqueuer and configure actions
if ( is_admin() ) {
	ICE_Enqueue::instance()
		->styles_on_action( 'load-toplevel_page_' . INFINITY_ADMIN_PAGE )
		->scripts_on_action( 'load-toplevel_page_' . INFINITY_ADMIN_PAGE );
} else {
	ICE_Enqueue::instance()
		->styles_on_action( 'wp_enqueue_scripts' )
		->scripts_on_action( 'wp_enqueue_scripts' );
}

// load Infinity API
require_once( INFINITY_APP_PATH . '/scheme.php' );
require_once( INFINITY_APP_PATH . '/sections.php' );
require_once( INFINITY_APP_PATH . '/options.php' );
require_once( INFINITY_APP_PATH . '/features.php' );
require_once( INFINITY_APP_PATH . '/widgets.php' );
require_once( INFINITY_APP_PATH . '/screens.php' );
require_once( INFINITY_APP_PATH . '/shortcodes.php' );
require_once( INFINITY_APP_PATH . '/i18n.php' );

// initialize scheme
infinity_scheme_init();

// initialize components
infinity_sections_init();
infinity_options_init();
infinity_screens_init();
infinity_features_init();
infinity_widgets_init();
infinity_shortcodes_init();

// finalize scheme
infinity_scheme_finalize();

if ( is_admin() ) {
	// init admin only components screens
	infinity_sections_init_screen();
	infinity_options_init_screen();
	infinity_screens_init_screen();
	infinity_widgets_init_screen();
	// load admin functionality
	require_once( INFINITY_ADMIN_PATH . '/loader.php' );
} else {
	// init blog components screens
	infinity_features_init_screen();
	infinity_shortcodes_init_screen();
}

// load local theme setup if applicable
if ( is_readable( INFINITY_INC_PATH . '/setup.php' ) ) {
	require_once( INFINITY_INC_PATH . '/setup.php' );
}

?>