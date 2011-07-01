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
define( 'INFINITY_API_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'api' );
define( 'INFINITY_API_URL', INFINITY_THEME_URL . '/api' );
define( 'INFINITY_PIE_DIR', INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'pie' );
define( 'INFINITY_PIE_URL', INFINITY_API_URL . '/pie' );
define( 'INFINITY_ADMIN_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'dashboard' );
define( 'INFINITY_ADMIN_URL', INFINITY_THEME_URL . '/dashboard' );
define( 'INFINITY_EXPORT_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'export' );
define( 'INFINITY_EXPORT_URL', INFINITY_THEME_URL . '/export' );
define( 'INFINITY_INCLUDES_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'includes' );
define( 'INFINITY_LANGUAGES_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'languages' );
define( 'INFINITY_TEXT_DOMAIN', INFINITY_NAME . '-theme' );
define( 'infinity_text_domain', INFINITY_TEXT_DOMAIN ); // for code completion
define( 'INFINITY_ADMIN_PAGE', INFINITY_NAME . '-theme' );
define( 'INFINITY_ADMIN_TPLS_DIR', INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'templates' );
define( 'INFINITY_ADMIN_DOCS_DIR', INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'docs' );

// setup i18n
load_theme_textdomain( INFINITY_TEXT_DOMAIN, INFINITY_LANGUAGES_DIR );

// load PIE and initialize
require_once( INFINITY_PIE_DIR . DIRECTORY_SEPARATOR . 'loader.php' );
Pie_Easy_Loader::init( INFINITY_PIE_URL );

// load Infinity API
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'scheme.php' );
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'sections.php' );
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'options.php' );
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'features.php' );
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'screens.php' );
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'shortcodes.php' );
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'i18n.php' );

// load theme requirements
require_once( INFINITY_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'setup.php');
require_once( INFINITY_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'comments.php');
require_once( INFINITY_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'templatetags.php');

// set up buddypress functionality
if ( defined( 'BP_VERSION' ) ) {
	require_once(
		INFINITY_INCLUDES_DIR .
		DIRECTORY_SEPARATOR . 'buddypress' .
		DIRECTORY_SEPARATOR . 'setup.php'
	);
}

// initialize scheme
infinity_scheme_init();
infinity_options_init();
infinity_features_init();

if ( is_admin() ) {
	// only load admin functionality if the dashboard is actually loaded
	require_once( INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'loader.php' );
} else {
	// some features need screen initialization
	infinity_features_init_screen();
}

//
// Actions & Filters
//

/**
 * Sets up theme
 */
function infinity_setup()
{
	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( INFINITY_TEXT_DOMAIN, INFINITY_THEME_DIR . '/languages' );

	// format language file path
	$locale_file = sprintf( '%s/languages/%s.php', INFINITY_THEME_DIR, get_locale() );

	// language file readable?
	if ( is_readable( $locale_file ) ) {
		// load it
		require_once( $locale_file );
	}
}
add_action( 'after_setup_theme', 'infinity_setup' );

?>
