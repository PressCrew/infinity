<?php
/**
 * PIE API: loader class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @since 1.0
 */

/**
 * check if PIE has been included already
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
define( 'PIE_EASY_LIB_DIR', PIE_EASY_DIR . DIRECTORY_SEPARATOR . 'lib' );
/**
 * PIE API: extensions library directory
 */
define( 'PIE_EASY_LIBEXT_DIR', PIE_EASY_DIR . DIRECTORY_SEPARATOR . 'libext' );
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
define( 'PIE_EASY_LANGUAGES_DIR', PIE_EASY_DIR . DIRECTORY_SEPARATOR . 'languages' );
/**
 * PIE API: vendors library directory
 */
define( 'PIE_EASY_VENDORS_DIR', PIE_EASY_LIB_DIR . DIRECTORY_SEPARATOR . 'vendors' );
/**
 * PIE API: exported files sub directory name
 */
if ( !defined( 'PIE_EASY_EXPORTS_SUBDIR' ) ) {
	define( 'PIE_EASY_EXPORTS_SUBDIR', 'exports' );
}

/**
 * Make loading PIE libraries easy
 *
 * @package PIE
 */
final class Pie_Easy_Loader
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
	private $pkgs = array(
		'base' =>
			array(
				'asset',
				'component', 'componentable',
				'icon', 'iconable',
				'factory',
				'policy', 'policeable', 'position', 'positionable',
				'registry', 'renderer',
				'script', 'scriptable',
				'style', 'styleable'
			),
		'collections' =>
			array( 'map', 'map_iterator', 'stack', 'stack_iterator' ),
		'components' =>
			array(
				'features' =>
					array( 'component', 'factory', 'renderer', 'policy', 'registry' ),
				'options' =>
					array( 'component', 'factory', 'renderer', 'policy', 'registry', 'uploader', 'walkers' ),
				'screens' =>
					array( 'component', 'factory', 'renderer', 'policy', 'registry' ),
				'sections' =>
					array( 'component', 'factory', 'renderer', 'policy', 'registry' ),
				'shortcodes' =>
					array( 'component', 'factory', 'renderer', 'policy', 'registry' ),
				'widgets' =>
					array( 'component', 'factory', 'renderer', 'policy', 'registry' )
			),
		'init' =>
			array( 'directive' ),
		'parsers' =>
			array( 'markdown', 'textile' ),
		'schemes' =>
			array( 'scheme', 'scheme_enqueue' ),
		'utils' =>
			array( 'ajax', 'docs', 'enqueue', 'export', 'files', 'i18n' )
	);

	/**
	 * Available lib extensions
	 *
	 * @var array
	 */
	private $exts = array(
		'features' => array(
			'css-background', 'custom-css', 'default', 'gravatar', 'header-logo'
		),
		'options' => array(
			'category', 'categories', 'checkbox', 'colorpicker', 'css',
			'disable',
			'enable', 'enabledisable',
			'leftright',
			'off', 'on', 'onoff',
			'page', 'pages', 'post', 'posts',
			'plugins' => array(
				'domain-mapping'
			),
			'radio',
			'select',
			'tag', 'tags',
			'text', 'textarea',
			'upload',
			'wp' => array(
				'blogname', 'blogdescription', 'page-on-front'
			),
			'yes', 'yesno'
		),
		'screens' => array( 'cpanel' ),
		'sections' => array( 'default' ),
		'shortcodes' => array( 'access' ),
		'widgets' => array( 'default', 'menu', 'posts-list', 'title-block' )
	);

	/**
	 * Map of exts types to their class prefix
	 * 
	 * @var array
	 */
	private $exts_prefix = array(
		'features' => 'Pie_Easy_Exts_Feature',
		'options' => 'Pie_Easy_Exts_Option',
		'screens' => 'Pie_Easy_Exts_Screen',
		'sections' => 'Pie_Easy_Exts_Section',
		'shortcodes' => 'Pie_Easy_Exts_Shortcode',
		'widgets' => 'Pie_Easy_Exts_Widget'
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
	 */
	final static public function init( $pie_url )
	{
		// new instance if necessary
		if ( !self::$instance instanceof self ) {

			// define PIE urls
			define( 'PIE_EASY_URL', $pie_url );
			define( 'PIE_EASY_ASSETS_URL', PIE_EASY_URL . '/assets' );
			define( 'PIE_EASY_CSS_URL', PIE_EASY_ASSETS_URL . '/css' );
			define( 'PIE_EASY_IMAGES_URL', PIE_EASY_ASSETS_URL . '/images' );
			define( 'PIE_EASY_JS_URL', PIE_EASY_ASSETS_URL . '/js' );

			// setup i18n
			load_theme_textdomain( PIE_EASY_TEXT_DOMAIN, PIE_EASY_LANGUAGES_DIR );

			// create singleton instance
			self::$instance = new self();

			// need the enqueue helper
			self::$instance->load( 'utils/enqueue' );

			// init enqueue helper right away
			Pie_Easy_Enqueue::init();
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
			return self::$instance->load_libext( $ext );
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
			// base pkg
			$pkg = $this->pkgs;
			// check all libs
			foreach ( $files as $file ) {
				// is file a pkg key?
				if ( isset( $pkg[$file] ) ) {
					// its a pkg, go to next level
					$pkg = $pkg[$file];
				} elseif ( in_array( $file, $pkg ) ) {
					// its a lib
					return $this->load_lib_file( array_pop( $files ), $files );
				} else {
					throw new Exception(
						sprintf( 'The library path "%s" is not valid.', $lib ) );
				}
			}
		} else {
			throw new Exception( 'The library path is empty.' );
		}

		// loading entire pkg
		if ( is_array( $pkg ) ) {
			// yep
			foreach( $pkg as $file ) {
				$this->load_lib_file( $file, $files );
			}
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
			DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $pkg ) .
			DIRECTORY_SEPARATOR . $file . '.php';

		// load it
		require_once $path;
		return true;
	}

	/**
	 * Load a lib extension
	 *
	 * @param string $ext
	 * @return string Name of class that was loaded
	 */
	private function load_libext( $ext )
	{
		// set files
		$files = is_array( $ext ) ? $ext : explode( self::PATH_DELIM, $ext );

		// files can't be empty
		if ( count( $files ) ) {
			// base pkg
			$exts = $this->exts;
			// check all libs
			foreach ( $files as $file ) {
				// is file a pkg key?
				if ( isset( $exts[$file] ) ) {
					// its a pkg, go to next level
					$exts = $exts[$file];
				} elseif ( in_array( $file, $exts ) ) {
					// its a lib
					return $this->load_libext_file( $files );
				}
			}
			// not found
			throw new Exception(
				sprintf( 'The extension path "%s" is not valid.', $ext ) );
		} else {
			throw new Exception( 'The extension path is empty.' );
		}
	}

	/**
	 * Load a library extension file
	 *
	 * @param string $file
	 * @param array|string $files
	 * @return true
	 */
	private function load_libext_file( $files )
	{
		// relative file
		$file = implode( DIRECTORY_SEPARATOR, $files );
		
		// build up file path
		$path =
			PIE_EASY_LIBEXT_DIR .
			DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $files ) .
			DIRECTORY_SEPARATOR . 'class.php';

		// component type is first item
		$component_type = array_shift( $files );

		// load it
		require_once $path;
		// return class name
		return Pie_Easy_Files::file_to_class( implode( '_', $files ), $this->exts_prefix[$component_type] );
	}

	/**
	 * Load WordPress wp-include files via static call
	 *
	 * @param string $file,... An unlimited number of files to load
	 */
	public function load_wp_lib()
	{
		foreach( func_get_args() as $file ) {
			require_once( Pie_Easy_Files::path_build( ABSPATH, 'wp-includes', $file . '.php' ) );
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
			require_once( Pie_Easy_Files::path_build( ABSPATH, 'wp-admin', 'includes', $file . '.php' ) );
		}
	}

}

?>
