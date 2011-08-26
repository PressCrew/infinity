<?php
/**
 * PIE API: loader class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage loader
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
 * @subpackage loader
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
		'features' =>
			array( 'component', 'factory', 'renderer', 'policy', 'registry' ),
		'options' =>
			array( 'component', 'factory', 'renderer', 'policy', 'registry', 'uploader', 'walkers' ),
		'init' =>
			array( 'directive' ),
		'parsers' =>
			array( 'markdown', 'textile' ),
		'schemes' =>
			array( 'scheme', 'scheme_enqueue' ),
		'screens' =>
			array( 'component', 'factory', 'renderer', 'policy', 'registry' ),
		'sections' =>
			array( 'component', 'factory', 'renderer', 'policy', 'registry' ),
		'shortcodes' =>
			array( 'component', 'factory', 'renderer', 'policy', 'registry' ),
		'utils' =>
			array( 'ajax', 'docs', 'enqueue', 'export', 'files', 'i18n' ),
		'widgets' =>
			array( 'component', 'factory', 'renderer', 'policy', 'registry' )
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
			'radio',
			'select',
			'tag', 'tags',
			'text', 'textarea',
			'upload',
			'yes', 'yesno'
		),
		'screens' => array( 'cpanel' ),
		'sections' => array( 'default' ),
		'shortcodes' => array( 'access' ),
		'widgets' => array( 'default', 'posts-list' )
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
			if ( is_array( $lib ) ) {
				self::$instance->load_lib( $lib[1], $lib[0] );
			} else {
				self::$instance->load_lib( $lib );
			}
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
			if ( is_array( $ext ) ) {
				return self::$instance->load_libext( $ext[1], $ext[0] );
			} else {
				return self::$instance->load_libext( $ext );
			}
		}
	}

	/**
	 * Load a single lib
	 *
	 * @param string $lib
	 * @param string $pkg
	 * @return true|void
	 */
	private function load_lib( $lib, $pkg = null )
	{
		if ( $pkg ) {
			return $this->load_lib_file( $lib, $pkg );
		} else {
			// load an entire package?
			if ( $this->load_lib_pkg( $lib ) ) {
				return true;
			} else {
				// split at path delim
				$split = explode( self::PATH_DELIM, $lib );
				// must have exactly two parts
				if ( count($split) == 2 ) {
					return $this->load_lib_file( $split[1], $split[0] );
				} else {
					throw new Exception( sprintf( 'The library path "%s" is not formatted correctly.', $lib ) );
				}
			}
		}
	}

	/**
	 * Load a library package
	 *
	 * @param string $pkg
	 * @return true|void
	 */
	private function load_lib_pkg( $pkg )
	{
		if ( array_key_exists( $pkg, $this->pkgs ) ) {
			foreach ( $this->pkgs[$pkg] as $lib ) {
				$this->load_lib_file( $lib, $pkg );
			}
			return true;
		}

		return false;
	}

	/**
	 * Load a library file
	 *
	 * @param string $lib
	 * @param string $pkg
	 * @return true
	 */
	private function load_lib_file( $lib, $pkg )
	{
		// check validity of package
		if ( array_key_exists( $pkg, $this->pkgs ) ) {
			// check validity of lib
			if ( in_array( $lib, $this->pkgs[$pkg], true ) ) {
				// build up file path
				$file =
					PIE_EASY_LIB_DIR .
					DIRECTORY_SEPARATOR . $pkg .
					DIRECTORY_SEPARATOR . $lib . '.php';
				// load it
				require_once $file;
				return true;
			} else {
				throw new Exception( sprintf( 'The library extension "%s" is not valid for the type "%s".', $lib, $pkg ) );
			}
		} else {
			throw new Exception( sprintf( 'The library package "%s" is not valid.', $pkg ) );
		}
	}

	/**
	 * Load a lib extension
	 *
	 * @param string $ext
	 * @param string $type
	 * @return string Name of class that was loaded
	 */
	private function load_libext( $ext, $type = null )
	{
		// exact type defined?
		if ( $type ) {
			// load without parsing
			return $this->load_libext_file( $ext, $type );
		} else {
			// split at path delim
			$split = explode( self::PATH_DELIM, $ext );
			// must be exactly two parts
			if ( count($split) == 2 ) {
				// try to load it
				return $this->load_libext_file( $split[1], $split[0] );
			} else {
				throw new Exception( sprintf( 'The library extension "%s" is not formatted correctly.', $ext ) );
			}
		}
	}

	/**
	 * Load a lib extension file
	 *
	 * @param string $ext
	 * @param string $type
	 * @return true|void
	 */
	private function load_libext_file( $ext, $type )
	{
		// check validity of type
		if ( array_key_exists( $type, $this->exts ) ) {
			// check validity of extension
			if ( in_array( $ext, $this->exts[$type], true ) ) {
				// build up file path
				$file =
					PIE_EASY_LIBEXT_DIR .
					DIRECTORY_SEPARATOR . $type .
					DIRECTORY_SEPARATOR . $ext .
					DIRECTORY_SEPARATOR . 'class.php';
				// load it
				require_once $file;
				// return class name
				return Pie_Easy_Files::file_to_class( $ext, $this->exts_prefix[$type] );
			} else {
				throw new Exception( sprintf( 'The library extension "%s" is not valid for the type "%s".', $ext, $type ) );
			}
		} else {
			throw new Exception( sprintf( 'The library extension type "%s" is not valid.', $type ) );
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
