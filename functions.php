<?php
/**
 * Infinity Theme: theme functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @since 1.0
 */

// DO NOT EDIT these constants for any reason
define( 'INFINITY_VERSION', '1.0b1' );
define( 'INFINITY_NAME', 'infinity' );
define( 'INFINITY_THEME_DIR', get_theme_root( INFINITY_NAME ) . DIRECTORY_SEPARATOR . INFINITY_NAME );
define( 'INFINITY_THEME_URL', get_theme_root_uri( INFINITY_NAME ) . '/' . INFINITY_NAME );
define( 'INFINITY_BASE_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'base' );
define( 'INFINITY_BASE_URL', INFINITY_THEME_URL . '/base' );
define( 'INFINITY_PIE_DIR', INFINITY_BASE_DIR . DIRECTORY_SEPARATOR . 'pie' );
define( 'INFINITY_PIE_URL', INFINITY_BASE_URL . '/pie' );
define( 'INFINITY_PIEXT_DIR', INFINITY_BASE_DIR . DIRECTORY_SEPARATOR . 'piext' );
define( 'INFINITY_PIEXT_URL', INFINITY_BASE_URL . '/piext' );
define( 'INFINITY_ADMIN_REL', 'dashboard' );
define( 'INFINITY_ADMIN_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'dashboard' );
define( 'INFINITY_ADMIN_URL', INFINITY_THEME_URL . '/dashboard' );
define( 'INFINITY_EXPORT_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'export' );
define( 'INFINITY_EXPORT_URL', INFINITY_THEME_URL . '/export' );
define( 'INFINITY_LANGUAGES_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'languages' );
define( 'INFINITY_TEXT_DOMAIN', INFINITY_NAME . '-theme' );
define( 'infinity_text_domain', INFINITY_TEXT_DOMAIN ); // for code completion
define( 'INFINITY_ADMIN_PAGE', INFINITY_NAME . '-theme' );
define( 'INFINITY_ADMIN_TPLS_REL', INFINITY_ADMIN_REL . DIRECTORY_SEPARATOR . 'templates' );
define( 'INFINITY_ADMIN_TPLS_DIR', INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'templates' );
define( 'INFINITY_ADMIN_DOCS_DIR', INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'docs' );

// load PIE and initialize
require_once( INFINITY_PIE_DIR . DIRECTORY_SEPARATOR . 'loader.php' );
Pie_Easy_Loader::init( INFINITY_PIE_URL );

// load Infinity API
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'scheme.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'sections.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'options.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'features.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'screens.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'shortcodes.php' );
require_once( INFINITY_PIEXT_DIR . DIRECTORY_SEPARATOR . 'i18n.php' );

// load theme setup
require_once( INFINITY_BASE_DIR . DIRECTORY_SEPARATOR . 'setup.php' );

// initialize scheme
infinity_scheme_init();

// initialize components
infinity_sections_init();
infinity_options_init();
infinity_screens_init();
infinity_features_init();
infinity_shortcodes_init();

if ( is_admin() ) {
	// init admin only components screens
	infinity_sections_init_screen();
	infinity_options_init_screen();
	infinity_screens_init_screen();
	// load admin functionality
	require_once( INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'loader.php' );
} else {
	// init blog components screens
	infinity_features_init_screen();
	infinity_shortcodes_init_screen();
}

?>
