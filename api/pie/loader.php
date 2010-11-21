<?php
/**
 * PIE Framework loader class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage loader
 * @since 1.0
 */

// check if pie has been included already
if ( defined( 'PIE_EASY_DIR' ) ) {
	return;
}

// set pie contants
define( 'PIE_EASY_DIR', dirname( __FILE__ ) );
define( 'PIE_EASY_VERSION', '1.0' );

/**
 * Make loading pie features easy
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
		'ajax',
		'l10n',
		'options' => array( 'option', 'registry', 'renderer', 'section', 'uploader', 'walkers' )
	);

	/**
	 * Constructor
	 */
	private function __construct()
	{
		// can't create singleton
	}

	/**
	 * Initialize
	 *
	 * @param string $pie_url The full URL to pie files
	 */
	final public function init( $pie_url )
	{
		// new instance if necessary
		if ( !self::$instance instanceof self ) {
			// define PIE urls
			define( 'PIE_EASY_URL', $pie_url );
			define( 'PIE_EASY_ASSETS_URL', PIE_EASY_URL . '/assets' );
			define( 'PIE_EASY_CSS_URL', PIE_EASY_ASSETS_URL . '/css' );
			define( 'PIE_EASY_IMAGES_URL', PIE_EASY_ASSETS_URL . '/images' );
			define( 'PIE_EASY_JS_URL', PIE_EASY_ASSETS_URL . '/js' );
			// create singleton instance
			self::$instance = new self();
			// handle requirements
			self::init_reqs();
		}
	}

	/**
	 * Initialize requirements
	 */
	static private function init_reqs()
	{
		// global style and script
		Pie_Easy_Loader::enqueue_style( 'global' );
		Pie_Easy_Loader::enqueue_script( 'global', array('jquery') );
	}

	/**
	 * Load a feature via static call
	 *
	 * @param string $feature
	 * @return bool
	 */
	final static public function load( $feature )
	{
		return self::$instance->load_feature( $feature );
	}
	
	/**
	 * Load a feature
	 *
	 * @param string $feature
	 * @return boolean
	 */
	final public function load_feature( $feature )
	{
		// make sure feature exists
		if ( array_key_exists( $feature, $this->features ) ) {
			return $this->load_package( $feature );
		} elseif ( in_array( $feature, $this->features, true ) ) {
			return $this->load_file( $feature );
		}

		// sorry
		throw new Exception( sprintf( 'The feature "%s" is not valid.', $feature ) );
	}

	/**
	 * Load a feature package
	 *
	 * @param string $feature
	 * @return true
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
			PIE_EASY_DIR,
			DIRECTORY_SEPARATOR,
			( $feature ) ? $feature . DIRECTORY_SEPARATOR : null,
			$name
		);

		// load it
		require_once $file;
		return true;
	}

	/**
	 * Enqueue a PIE stylesheet
	 *
	 * @param string $handle
	 * @param array $deps
	 */
	final static public function enqueue_style( $handle, $deps = false, $media = 'all' )
	{
		return
			wp_enqueue_style(
				'pie-easy-' . $handle,
				sprintf( '%s/%s.css', PIE_EASY_CSS_URL, $handle ),
				$deps,
				PIE_EASY_VERSION,
				$media
			);
	}

	/**
	 * Enqueue a PIE script
	 *
	 * @param string $handle
	 * @param array $deps
	 */
	final static public function enqueue_script( $handle, $deps = false )
	{
		return
			wp_enqueue_script(
				'pie-easy-' . $handle,
				sprintf( '%s/%s.js', PIE_EASY_JS_URL, $handle ),
				$deps,
				PIE_EASY_VERSION
			);
	}

	/**
	 * Localize a PIE script
	 *
	 * @param string $handle
	 * @param array $deps
	 */
	final static public function localize_script( $handle, $object_name, $l10n )
	{
		return wp_localize_script( 'pie-easy-' . $handle, $object_name, $l10n );
	}
}

?>
