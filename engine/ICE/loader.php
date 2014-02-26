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
if ( defined( 'ICE_VERSION' ) ) {
	return;
}

/**
 * ICE API: version
 */
define( 'ICE_VERSION', '1.2a' );
/**
 * ICE API: root directory
 */
define( 'ICE_PATH', dirname( __FILE__ ) );
/**
 * ICE API: library directory (3rd party)
 */
define( 'ICE_LIB_PATH', ICE_PATH . '/lib' );
/**
 * ICE API: extensions library directory
 */
define( 'ICE_EXT_PATH', ICE_PATH . '/ext' );
/**
 * ICE API: exported files sub directory name
 */
if ( !defined( 'ICE_EXPORTS_SUBDIR' ) ) {
	define( 'ICE_EXPORTS_SUBDIR', 'exports' );
}
/**
 * ICE API: compiled themes hint
 */
if ( !defined( 'ICE_THEMES_COMPILED' ) ) {
	define( 'ICE_THEMES_COMPILED', null );
}
/**
 * ICE API: cache get_stylesheet() call for performance 
 */
define( 'ICE_ACTIVE_THEME', get_stylesheet() );
/**
 * ICE API: cache is_admin() call for performance
 */
define( 'ICE_IS_ADMIN', is_admin() );

/**
 * include the base class
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
	 * Singleton instance
	 *
	 * @var ICE_Loader
	 */
	private static $instance;

	/**
	 * This is a singleton
	 */
	private function __construct() {}

	/**
	 * Initialize ICE
	 *
	 * You must tell ICE at what URL its root directory is located
	 *
	 * @param string $ice_url The absolute URL to ICE root
	 */
	final static public function init( $ice_url )
	{
		// new instance if necessary
		if ( !self::$instance instanceof self ) {

			// define api ext prefixes
			define( 'ICE_EXT_PREFIX', 'ICE_Ext' );

			// define ICE urls
			define( 'ICE_URL', $ice_url );
			define( 'ICE_ASSETS_URL', ICE_URL . '/assets' );
			define( 'ICE_CSS_URL', ICE_ASSETS_URL . '/css' );
			define( 'ICE_IMAGES_URL', ICE_ASSETS_URL . '/images' );
			define( 'ICE_JS_URL', ICE_ASSETS_URL . '/js' );

			// is this an AJAX request?
			define( 'ICE_AJAX_REQUEST', defined( 'DOING_AJAX' ) );

			// create singleton instance
			self::$instance = new self();

			// load really important classes
			self::$instance->load(
				'collections/map',
				'collections/map_iterator',
				'collections/stack',
				'collections/stack_iterator',
				'utils/files',
				'utils/enqueue'
			);
		}
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
			self::$instance->load_lib( $lib );
		}
	}

	/**
	 * Load a library file relative to ICE_PATH
	 *
	 * @param string $lib
	 * @return true
	 */
	private function load_lib( $lib )
	{
		// load file
		require_once ICE_PATH . '/' . $lib . '.php';
		// all done
		return true;
	}

	/**
	 * Load WordPress wp-include files via static call
	 *
	 * @param string $file,... An unlimited number of files to load
	 */
	public function load_wp_lib()
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
	public function load_wpadmin_lib()
	{
		foreach( func_get_args() as $file ) {
			require_once ABSPATH . 'wp-admin/includes/' . $file . '.php';
		}
	}

}
