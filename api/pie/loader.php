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
define( 'PIE_EASY_LIB_DIR', PIE_EASY_DIR . DIRECTORY_SEPARATOR . 'library' );
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
 * Make loading PIE features easy
 *
 * @package PIE
 * @subpackage loader
 */
final class Pie_Easy_Loader
{
	/**
	 * Singleton instance
	 *
	 * @var Pie_Easy_Loader
	 */
	private static $instance;

	/**
	 * Available features
	 *
	 * @var array
	 */
	private $features = array(
		'collections' =>
			array( 'map', 'map_iterator', 'stack', 'stack_iterator' ),
		'features' =>
			array( 'feature' ),
		'options' =>
			array( 'registry', 'option', 'option_directive', 'option_renderer', 'section', 'uploader', 'walkers' ),
		'init' =>
			array( 'directive' ),
		'parsers' =>
			array( 'markdown', 'textile' ),
		'schemes' =>
			array( 'scheme', 'scheme_directive', 'scheme_enqueue' ),
		'utils' =>
			array( 'ajax', 'docs', 'enqueue', 'files', 'i18n' )
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
	 * Load feature(s) via static call
	 *
	 * @param string $feature,... An unlimited number of features to load
	 */
	final static public function load()
	{
		// handle variable number of args
		$features = func_get_args();

		// loop through all features
		foreach ( $features as $feature ) {
			self::$instance->load_feature( $feature );
		}
	}

	/**
	 * Load a single feature
	 *
	 * @param string $feature
	 * @return true|void
	 */
	final public function load_feature( $feature )
	{
		// make sure feature exists
		if ( array_key_exists( $feature, $this->features ) ) {
			return $this->load_package( $feature );
		} elseif ( in_array( $feature, $this->features, true ) ) {
			return $this->load_file( $feature );
		} else {
			$split = explode( '/', $feature );
			if ( count($split) == 2 ) {
				return $this->load_file( $split[1], $split[0] );
			}
		}

		// sorry
		throw new Exception( sprintf( 'The feature "%s" is not valid.', $feature ) );
	}

	/**
	 * Load a feature package
	 *
	 * @param string $feature
	 * @return true|void
	 */
	private function load_package( $feature )
	{
		foreach ( $this->features[$feature] as $file ) {
			$this->load_file( $file, $feature );
		}

		return true;
	}

	/**
	 * Load a feature file
	 *
	 * @param string $name
	 * @param string $feature
	 * @return true
	 */
	private function load_file( $name, $feature = null )
	{
		// build up file path
		$file = sprintf(
			'%s%s%s%s.php',
			PIE_EASY_LIB_DIR,
			DIRECTORY_SEPARATOR,
			( $feature ) ? $feature . DIRECTORY_SEPARATOR : null,
			$name
		);

		// load it
		require_once $file;
		return true;
	}

}

?>
