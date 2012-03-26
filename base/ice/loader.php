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
define( 'ICE_VERSION', '1.0' );
/**
 * ICE API: root directory
 */
define( 'ICE_DIR', dirname( __FILE__ ) );
/**
 * ICE API: library directory
 */
define( 'ICE_LIB_DIR', ICE_DIR . '/lib' );
/**
 * ICE API: extensions library directory
 */
define( 'ICE_LIBEXT_DIR', ICE_DIR . '/libext' );
/**
 * ICE API: vendors library directory
 */
define( 'ICE_VENDORS_DIR', ICE_LIB_DIR . '/vendors' );
/**
 * ICE API: exports caching toggle
 */
if ( !defined( 'ICE_CACHE_EXPORTS' ) ) {
	define( 'ICE_CACHE_EXPORTS', true );
}
/**
 * ICE API: error handling support toggle
 */
if ( !defined( 'ICE_ERROR_HANDLING' ) ) {
	define( 'ICE_ERROR_HANDLING', false );
}
/**
 * ICE API: error reproting support toggle
 */
if ( !defined( 'ICE_ERROR_REPORTING' ) ) {
	define( 'ICE_ERROR_REPORTING', false );
}
/**
 * ICE API: exported files sub directory name
 */
if ( !defined( 'ICE_EXPORTS_SUBDIR' ) ) {
	define( 'ICE_EXPORTS_SUBDIR', 'exports' );
}

/**
 *  load error handler if applicable
 */
if ( ICE_ERROR_HANDLING ) {
	require_once ICE_LIB_DIR . '/errors/handler.php';
}

/**
 * include the base class
 */
require_once ICE_LIB_DIR . '/base/base.php';

/**
 * Make loading ICE libraries easy
 *
 * @package ICE
 */
final class ICE_Loader extends ICE_Base
{
	/**
	 * Delimeter at which to split library paths
	 */
	const PATH_DELIM = '/';

	/**
	 * Singleton instance
	 *
	 * @var ICE_Loader
	 */
	private static $instance;

	/**
	 * Available libs
	 *
	 * @var array
	 */
	private static $pkgs = array(
		'base' => array(
			'component' => true,
			'componentable' => true,
			'configurable' => true,
			'exportable' => true,
			'factory' => true,
			'policy' => true,
			'policeable' => true,
			'recursable' => true,
			'registry' => true,
			'renderer' => true,
			'visitable' => true
		),
		'collections' => array(
			'map' => true,
			'map_iterator' => true,
			'stack' => true,
			'stack_iterator' => true
		),
		'components' => array(
			'features' => array(
				'component' => true,
				'component_bp' => true,
				'factory' => true,
				'renderer' => true,
				'policy' => true,
				'registry' => true
			),
			'options' => array(
				'component' => true,
				'factory' => true,
				'renderer' => true,
				'policy' => true,
				'registry' => true,
				'walkers' => true
			),
			'screens' => array(
				'component' => true,
				'factory' => true,
				'renderer' => true,
				'policy' => true,
				'registry' => true
			),
			'sections' => array(
				'component' => true,
				'factory' => true,
				'renderer' => true,
				'policy' => true,
				'registry' => true
			),
			'shortcodes' => array(
				'component' => true,
				'factory' => true,
				'renderer' => true,
				'policy' => true,
				'registry' => true
			),
			'widgets' => array(
				'component' => true,
				'factory' => true,
				'renderer' => true,
				'policy' => true,
				'registry' => true
			)
		),
		'init' => array(
			'data' => true,
			'directive' => true,
			'configuration' => true,
			'registry' => true
		),
		'parsers' => array(
			'less' => true,
			'markdown' => true,
			'textile' => true
		),
		'schemes' => array(
			'scheme' => true,
			'scheme_enqueue' => true
		),
		'ui' => array(
			'asset' => true,
			'cpanel' => true,
			'icon' => true,
			'iconable' => true,
			'position' => true,
			'positionable' => true,
			'script' => true,
			'scriptable' => true,
			'style' => true,
			'styleable' => true
		),
		'utils' => array(
			'ajax' => true,
			'docs' => true,
			'enqueue' => true,
			'export' => true,
			'file' => true,
			'file_cache' => true,
			'files' => true,
			'i18n' => true,
			'webfont' => true
		)
	);

	/**
	 * Available lib extensions
	 *
	 * @var array
	 */
	private static $exts = array(
		'features' => array(
			'bp' => array(
				'activity-intro' => true,
				'support' => true
			),
			'default' => true,
			'gravatar' => true,
			'header-logo' => true
		),
		'options' => array(
			'category' => true,
			'categories' => true,
			'checkbox' => true,
			'colorpicker' => true,
			'css' => array(
				'bg-color' => true,
				'bg-image' => true,
				'bg-repeat' => true,
				'border-color' => true,
				'border-width' => true,
				'custom' => true,
				'length-px' => true
			),
			'input' => true,
			'input-group' => true,
			'page' => true,
			'pages' => true,
			'post' => true,
			'posts' => true,
			'plugins' => array(
				'domain-mapping' => true
			),
			'position' => array(
				'left-right' => true,
				'left-center-right' => true,
				'top-bottom' => true
			),
			'radio' => true,
			'select' => true,
			'tag' => true,
			'tags' => true,
			'text' => true,
			'textarea' => true,
			'toggle' => array(
				'enable' => true,
				'disable' => true,
				'enable-disable' => true,
				'on' => true,
				'off' => true,
				'on-off' => true,
				'yes' => true,
				'no' => true,
				'yes-no' => true
			),
			'ui' => array(
				'font-picker' => true,
				'image-picker' => true,
				'overlay-picker' => true,
				'scroll-picker' => true,
				'slider' => true
			),
			'upload' => true,
			'wp' => array(
				'blogname' => true,
				'blogdescription' => true,
				'page-on-front' => true
			)
		),
		'screens' => array(
			'cpanel' => true
		),
		'sections' => array(
			'default' => true
		),
		'shortcodes' => array(
			'access' => true,
			'visitor' => true
		),
		'widgets' => array(
			'debugger' => true,
			'default' => true,
			'menu' => true,
			'posts-list' => true,
			'theme-picker' => true,
			'title-block' => true
		)
	);

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
			define( 'ICE_ERRORS_URL', ICE_ASSETS_URL . '/errors' );
			define( 'ICE_IMAGES_URL', ICE_ASSETS_URL . '/images' );
			define( 'ICE_JS_URL', ICE_ASSETS_URL . '/js' );

			// is this an AJAX request?
			define( 'ICE_AJAX_REQUEST', defined( 'DOING_AJAX' ) );

			// create singleton instance
			self::$instance = new self();

			// load really important classes
			self::$instance->load(
				'collections',
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
	 * Load library extension(s) via static call
	 *
	 * @param string $ext,... An unlimited number of lib exts to load
	 * @return string Name of class that was loaded
	 */
	final static public function load_ext()
	{
		// handle variable number of args
		$exts = func_get_args();

		// loop through all exts
		foreach ( $exts as $ext ) {
			self::$instance->load_libext( $ext );
		}
	}

	/**
	 * Load a single lib
	 *
	 * @param string $lib
	 * @return true|void
	 */
	private function load_lib( $lib )
	{
		// set files
		$files = is_array( $lib ) ? $lib : explode( self::PATH_DELIM, $lib );

		// files can't be empty
		if ( count( $files ) ) {
			// base pkgs
			$pkgs = self::$pkgs;
			// check all libs
			foreach ( $files as $file ) {
				// is file a pkg key?
				if ( isset( $pkgs[$file] ) ) {
					// is it an array?
					if ( is_array( $pkgs[$file] ) ) {
						// its a pkg, go to next level
						$pkgs = $pkgs[$file];
					} elseif ( true === $pkgs[$file] ) {
						// its a file, load it
						return $this->load_lib_file( array_pop( $files ), $files );
					} else {
						throw new Exception(
							sprintf( 'The library path "%s" is not valid.', $lib ) );
					}
				}
			}
		} else {
			throw new Exception( 'The library path is empty.' );
		}

		// loading entire pkg
		foreach( array_keys( $pkgs ) as $pkg_file ) {
			$this->load_lib_file( $pkg_file, $files );
		}
	}

	/**
	 * Load a library file
	 *
	 * @param string $file
	 * @param array|string $pkg
	 * @return true
	 */
	private function load_lib_file( $file, $pkg )
	{
		// build up file path
		$path =
			ICE_LIB_DIR .
			'/' . implode( '/', $pkg ) .
			'/' . $file . '.php';

		// load it
		require_once $path;
		return true;
	}

	/**
	 * Load ONE lib extension
	 *
	 * @param string $ext
	 * @return string Name of class that was loaded
	 */
	final static public function load_libext( $ext )
	{
		// set files
		$files = is_array( $ext ) ? $ext : explode( self::PATH_DELIM, $ext );

		// files can't be empty
		if ( count( $files ) ) {
			// base extension
			$exts = self::$exts;
			// check all libs
			foreach ( $files as $file ) {
				// is file a pkg key?
				if ( isset( $exts[$file] ) ) {
					// is it an array?
					if ( is_array( $exts[$file] ) ) {
						// its a pkg, go to next level
						$exts = $exts[$file];
					} elseif ( true === $exts[$file] ) {
						// its a file
						return self::load_libext_file( $files );
					}
				}
			}
		} else {
			throw new Exception( 'The extension path is empty.' );
		}

		// ext not found
		return null;
	}

	/**
	 * Load a library extension file
	 *
	 * @param array $files
	 * @return true
	 */
	private static function load_libext_file( $files )
	{
		// chomp plurals
		$class = $files;
		$class[0] = rtrim( $class[0], 's' );

		// determine class name
		$class_name =
			ICE_Files::file_to_class( implode( '_', $class ), ICE_EXT_PREFIX );

		// if class already exists, just return it
		if ( class_exists( $class_name ) ) {
			// already loaded, woot
			return $class_name;
		}

		// relative file
		$file = implode( '/', $files );
		
		// build up file path
		$path = ICE_LIBEXT_DIR . '/' . $file . '/class.php';

		// make sure it exists
		if ( ICE_Files::cache($path)->is_readable() ) {
			// load it
			require_once $path;
		} else {
			// not good
			throw new Exception( sprintf( 'The extension "%s" does not exist. Please check your config file!', $file ) );
		}

		// did the file we just loaded define the class we were expecting?
		if ( class_exists( $class_name ) ) {
			// return class name
			return $class_name;
		} else {
			throw new Exception( sprintf( 'The class "%s" does not exist', $class_name ) );
		}
	}

	/**
	 * Load WordPress wp-include files via static call
	 *
	 * @param string $file,... An unlimited number of files to load
	 */
	public function load_wp_lib()
	{
		foreach( func_get_args() as $file ) {
			require_once( ABSPATH . 'wp-includes/' . $file . '.php' );
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
			require_once( ABSPATH . 'wp-admin/includes/' . $file . '.php' );
		}
	}

}

?>
