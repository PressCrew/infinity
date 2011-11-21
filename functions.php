<?php
/**
 * Infinity Theme: theme functions
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
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
define( 'INFINITY_NAME', 'infinity' );

/**
 * Infinity theme directory path
 */
define( 'INFINITY_THEME_DIR', realpath( get_theme_root( INFINITY_NAME ) ) . DIRECTORY_SEPARATOR . INFINITY_NAME );

/**
 * Infinity theme directory url
 */
define( 'INFINITY_THEME_URL', get_theme_root_uri( INFINITY_NAME ) . '/' . INFINITY_NAME );

/**
 * Infinity "base" (includes) directory path
 */
define( 'INFINITY_BASE_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'base' );

/**
 * Infinity "base" (includes) url
 */
define( 'INFINITY_BASE_URL', INFINITY_THEME_URL . '/base' );

/**
 * PIE directory path
 */
define( 'INFINITY_PIE_DIR', INFINITY_BASE_DIR . DIRECTORY_SEPARATOR . 'pie' );

/**
 * PIE directory URL
 */
define( 'INFINITY_PIE_URL', INFINITY_BASE_URL . '/pie' );

/**
 * Infinity's PIE implementation directory path
 */
define( 'INFINITY_PIEXT_DIR', INFINITY_BASE_DIR . DIRECTORY_SEPARATOR . 'piext' );

/**
 * Infinity's PIE implementation url
 */
define( 'INFINITY_PIEXT_URL', INFINITY_BASE_URL . '/piext' );

/**
 * Infinity admin directory relative path
 */
define( 'INFINITY_ADMIN_REL', 'dashboard' );

/**
 * Infinity admin directory absolute path
 */
define( 'INFINITY_ADMIN_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . INFINITY_ADMIN_REL );

/**
 * Infinity admin directory url
 */
define( 'INFINITY_ADMIN_URL', INFINITY_THEME_URL . '/' . INFINITY_ADMIN_REL );

/**
 * Infinity languages directory path
 */
define( 'INFINITY_LANGUAGES_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'languages' );

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
 * Infinity admin templates relative directory path
 */
define( 'INFINITY_ADMIN_TPLS_REL', INFINITY_ADMIN_REL . DIRECTORY_SEPARATOR . 'templates' );

/**
 * Infinity admin templates absolute directory path
 */
define( 'INFINITY_ADMIN_TPLS_DIR', INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'templates' );

/**
 * Infinity admin documentation directory path
 */
define( 'INFINITY_ADMIN_DOCS_DIR', INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'docs' );

/**
 * Infinity development mode
 */
if ( !defined( 'INFINITY_DEV_MODE' ) ) {
	define( 'INFINITY_DEV_MODE', false );
}
	/**
	 * PIE exports caching toggle
	 */
	if ( !defined( 'PIE_EASY_CACHE_EXPORTS' ) ) {
		define( 'PIE_EASY_CACHE_EXPORTS', !INFINITY_DEV_MODE );
	}

/**
 * Infinity error handling
 */
if ( !defined( 'INFINITY_ERROR_HANDLING' ) ) {
	define( 'INFINITY_ERROR_HANDLING', true );
}
	/**
	 * PIE error handling
	 */
	if ( !defined( 'PIE_EASY_ERROR_HANDLING' ) ) {
		define( 'PIE_EASY_ERROR_HANDLING', INFINITY_ERROR_HANDLING );
	}

/**
 * Infinity error reporting
 */
if ( !defined( 'INFINITY_ERROR_REPORTING' ) ) {
	define( 'INFINITY_ERROR_REPORTING', INFINITY_DEV_MODE );
}
	/**
	 * PIE error reporting
	 */
	if ( !defined( 'PIE_EASY_ERROR_REPORTING' ) ) {
		define( 'PIE_EASY_ERROR_REPORTING', INFINITY_ERROR_REPORTING );
	}

/**
 * Load the PIE lib loader
 */
require_once( INFINITY_PIE_DIR . DIRECTORY_SEPARATOR . 'loader.php' );

// initialize PIE
Pie_Easy_Loader::init( INFINITY_PIE_URL, 'Infinity_Exts' );

// initialize enqueuer and configure actions
if ( is_admin() ) {
	Pie_Easy_Enqueue::instance()
		->styles_on_action( 'load-toplevel_page_' . INFINITY_ADMIN_PAGE )
		->scripts_on_action( 'load-toplevel_page_' . INFINITY_ADMIN_PAGE );
} else {
	Pie_Easy_Enqueue::instance()
		->styles_on_action( 'wp_print_styles' )
		->scripts_on_action( 'wp_print_scripts' );
}

// load Infinity API
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'scheme.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'sections.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'options.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'features.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'widgets.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'screens.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'shortcodes.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'i18n.php' );

// load theme setup
require_once( INFINITY_BASE_DIR . DIRECTORY_SEPARATOR . 'setup.php' );

// initialize scheme
infinity_scheme_init();

// initialize components
infinity_sections_init();
infinity_screens_init();
infinity_features_init();
infinity_widgets_init();
infinity_shortcodes_init();
infinity_options_init();

if ( is_admin() ) {
	// init admin only components screens
	infinity_sections_init_screen();
	infinity_options_init_screen();
	infinity_screens_init_screen();
	infinity_widgets_init_screen();
	// load admin functionality
	require_once( INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'loader.php' );
} else {
	// init blog components screens
	infinity_features_init_screen();
	infinity_shortcodes_init_screen();
}

?>
