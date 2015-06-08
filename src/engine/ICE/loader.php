<?php
/**
 * ICE API: loader class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @since 1.0
 */

/**
 * Check if ICE has been included already
 * @ignore
 */
if ( defined( 'ICE_PATH' ) ) {
	return;
}

/**
 * ICE API: root directory.
 */
define( 'ICE_PATH', dirname( __FILE__ ) );

/**
 * Include the core constants.
 */
require_once ICE_PATH . '/constants.php';

/**
 * Include the base class.
 */
require_once ICE_PATH . '/base/base.php';

/**
 * Make loading ICE libraries easy
 *
 * @package ICE
 */
final class ICE_Loader extends ICE_Base
{
	/**
	 * This is a static singleton.
	 */
	private function __construct() {}

	/**
	 * Initialize ICE
	 *
	 * You must tell ICE at what URL its root directory is located
	 *
	 * @param string $ice_slug The static slug/prefix
	 * @param string $ice_url The absolute URL to ICE root
	 */
	final static public function init( $ice_slug, $ice_url )
	{
		// define constants if necessary
		if ( false === defined( 'ICE_SLUG' ) ) {

			// define ICE slug
			define( 'ICE_SLUG', $ice_slug );

			// define ICE urls
			define( 'ICE_URL', $ice_url );
			define( 'ICE_ASSETS_URL', ICE_URL . '/assets' );
			define( 'ICE_CSS_URL', ICE_ASSETS_URL . '/css' );
			define( 'ICE_IMAGES_URL', ICE_ASSETS_URL . '/images' );
			define( 'ICE_JS_URL', ICE_ASSETS_URL . '/js' );

			// load really important classes
			self::load(
				'utils/files',
				'utils/enqueue'
			);

			// add init actions callback
			add_action( 'init', array( __CLASS__, 'do_init_actions' ), 9 );

			// in dashboard?
			if ( ICE_IS_ADMIN ) {
				// add theme activation callback
				add_action( 'load-themes.php', array( __CLASS__, 'do_activated_actions' ) );
				// add upgrade callback
				add_action( 'admin_init', array( __CLASS__, 'upgrade' ), 1 );
			}
		}
	}

	/**
	 * Call special ICE theme activation actions.
	 */
	final static public function do_activated_actions()
	{
		global $pagenow;

		// was i just activated?
		if ( $pagenow == 'themes.php' && isset( $_GET['activated'] ) ) {
			// exec activation hook
			do_action( 'ice_theme_activated' );
		}
	}

	/**
	 * Call special ICE init actions.
	 */
	final static public function do_init_actions()
	{
		// always do this one
		do_action( 'ice_init' );
		
		// inside /wp-admin ?
		if ( ICE_IS_ADMIN ) {
			// ajax request?
			if ( ICE_IS_AJAX ) {
				// yep, do ajax action
				do_action( 'ice_init_ajax' );
			} else {
				// nope, do dashboard action
				do_action( 'ice_init_dash' );
			}
		} else {
			// must be the blog side of things
			do_action( 'ice_init_blog' );
		}
	}

	/**
	 * Run upgrade helper.
	 */
	final static public function upgrade()
	{
		// load upgrade lib
		ICE_Loader::load_lib( 'utils/upgrade' );
		// new upgrade instance
		$upgrade = new ICE_Upgrade_1_2( 'ice_upgrade' );
		// run it
		$upgrade->run();
		// exec upgraded hook
		do_action( 'ice_theme_upgraded', $upgrade );
	}

	/**
	 * Load lib(s) via static call
	 *
	 * @param string $lib,... An unlimited number of libs to load
	 */
	final static public function load()
	{
		// handle variable number of args
		$libs = func_get_args();

		// loop through all libs
		foreach ( $libs as $lib ) {
			self::load_lib( $lib );
		}
	}

	/**
	 * Load a library file relative to ICE_PATH
	 *
	 * @param string $lib
	 */
	final public static function load_lib( $lib )
	{
		// load file
		require_once ICE_PATH . '/' . $lib . '.php';
	}

	/**
	 * Load WordPress wp-include files via static call
	 *
	 * @param string $file,... An unlimited number of files to load
	 */
	final public static function load_wp_lib()
	{
		foreach( func_get_args() as $file ) {
			require_once ABSPATH . 'wp-includes/' . $file . '.php';
		}
	}

	/**
	 * Load WordPress wp-admin/include files via static call
	 *
	 * @param string $file,... An unlimited number of files to load
	 */
	final public static function load_wpadmin_lib()
	{
		foreach( func_get_args() as $file ) {
			require_once ABSPATH . 'wp-admin/includes/' . $file . '.php';
		}
	}

}

//
// Helpers
//

/**
 * Include an arbitrary file safely in an empty scope.
 *
 * @param string $filename
 * @param boolean $once
 */
function ice_loader_safe_include( $filename, $once = true )
{
	if ( true === $once ) {
		include_once $filename;
	} else {
		include $filename;
	}
}

/**
 * Require an arbitrary file safely in an empty scope.
 *
 * @param string $filename
 * @param boolean $once
 */
function ice_loader_safe_require( $filename, $once = true )
{
	if ( true === $once ) {
		require_once $filename;
	} else {
		require $filename;
	}
}
