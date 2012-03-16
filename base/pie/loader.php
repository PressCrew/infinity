<?php
/**
 * PIE API: loader class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @since 1.0
 */

/**
 * Check if PIE has been included already
 * @ignore
 */
if ( defined( 'PIE_EASY_VERSION' ) ) {
	return;
}

/**
 * PIE API: version
 */
define( 'PIE_EASY_VERSION', '1.0' );
/**
 * PIE API: root directory
 */
define( 'PIE_EASY_DIR', dirname( __FILE__ ) );
/**
 * PIE API: library directory
 */
define( 'PIE_EASY_LIB_DIR', PIE_EASY_DIR . '/lib' );
/**
 * PIE API: extensions library directory
 */
define( 'PIE_EASY_LIBEXT_DIR', PIE_EASY_DIR . '/libext' );
/**
 * PIE API: text domain
 */
define( 'PIE_EASY_TEXT_DOMAIN', 'pie-easy-api' );
/**
 * PIE API: text domain shorthand (for code completion purposes)
 */
define( 'pie_easy_text_domain', PIE_EASY_TEXT_DOMAIN );
/**
 * PIE API: languages directory
 */
define( 'PIE_EASY_LANGUAGES_DIR', PIE_EASY_DIR . '/languages' );
/**
 * PIE API: vendors library directory
 */
define( 'PIE_EASY_VENDORS_DIR', PIE_EASY_LIB_DIR . '/vendors' );
/**
 * PIE API: exports caching toggle
 */
if ( !defined( 'PIE_EASY_CACHE_EXPORTS' ) ) {
	define( 'PIE_EASY_CACHE_EXPORTS', true );
}
/**
 * PIE API: error handling support toggle
 */
if ( !defined( 'PIE_EASY_ERROR_HANDLING' ) ) {
	define( 'PIE_EASY_ERROR_HANDLING', false );
}
/**
 * PIE API: error reproting support toggle
 */
if ( !defined( 'PIE_EASY_ERROR_REPORTING' ) ) {
	define( 'PIE_EASY_ERROR_REPORTING', false );
}
/**
 * PIE API: exported files sub directory name
 */
if ( !defined( 'PIE_EASY_EXPORTS_SUBDIR' ) ) {
	define( 'PIE_EASY_EXPORTS_SUBDIR', 'exports' );
}

/**
 *  load error handler if applicable
 */
if ( PIE_EASY_ERROR_HANDLING ) {
	require_once PIE_EASY_LIB_DIR . '/errors/handler.php';
}

/**
 * include the base class
 */
require_once PIE_EASY_LIB_DIR . '/base/base.php';

/**
 * Make loading PIE libraries easy
 *
 * @package PIE
 */
final class Pie_Easy_Loader extends Pie_Easy_Base
{
	/**
	 * Delimeter at which to split library paths
	 */
	const PATH_DELIM = '/';

	/**
	 * Singleton instance
	 *
	 * @var Pie_Easy_Loader
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
				'custom' => true
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
	 * Initialize PIE
	 *
	 * You must tell PIE at what URL its root directory is located
	 *
	 * @param string $pie_url The absolute URL to PIE root
	 * @param string $ext_prefix The prefix for ext class names defined in the implementing API
	 */
	final static public function init( $pie_url, $ext_prefix )
	{
		// new instance if necessary
		if ( !self::$instance instanceof self ) {

			// define api ext prefixes
			define( 'PIE_EASY_EXT_PREFIX', 'Pie_Easy_Exts' );
			define( 'PIE_EASY_EXT_PREFIX_API', $ext_prefix );

			// define PIE urls
			define( 'PIE_EASY_URL', $pie_url );
			define( 'PIE_EASY_ASSETS_URL', PIE_EASY_URL . '/assets' );
			define( 'PIE_EASY_CSS_URL', PIE_EASY_ASSETS_URL . '/css' );
			define( 'PIE_EASY_ERRORS_URL', PIE_EASY_ASSETS_URL . '/errors' );
			define( 'PIE_EASY_IMAGES_URL', PIE_EASY_ASSETS_URL . '/images' );
			define( 'PIE_EASY_JS_URL', PIE_EASY_ASSETS_URL . '/js' );

			// is this an AJAX request?
			define( 'PIE_EASY_AJAX_REQUEST', defined( 'DOING_AJAX' ) );

			// setup i18n
			load_theme_textdomain( PIE_EASY_TEXT_DOMAIN, PIE_EASY_LANGUAGES_DIR );

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
			PIE_EASY_LIB_DIR .
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
		// determine class name
		$class_name =
			Pie_Easy_Files::file_to_class( implode( '_', $files ), PIE_EASY_EXT_PREFIX );

		// if class already exists, just return it
		if ( class_exists( $class_name ) ) {
			// already loaded, woot
			return $class_name;
		}

		// relative file
		$file = implode( '/', $files );
		
		// build up file path
		$path = PIE_EASY_LIBEXT_DIR . '/' . $file . '/class.php';

		// make sure it exists
		if ( Pie_Easy_Files::cache($path)->is_readable() ) {
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
