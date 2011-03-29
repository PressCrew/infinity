<?php
/**
 * PIE enqueue helpers class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage enqueue
 * @since 1.0
 */

Pie_Easy_Loader::load( 'files' );

/**
 * Make enqueing assets Easy
 */
final class Pie_Easy_Enqueue
{
	/**
	 * @var Pie_Easy_Enqueue
	 */
	static private $instance;

	/**
	 * Singleton constructor
	 */
	private function __construct()
	{
		// this is a singleton
	}
	
	/**
	 * Return singleton instance
	 *
	 * @return Pie_Easy_Enqueue
	 */
	public function instance()
	{
		if ( !self::$instance instanceof self ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register a PIE style
	 *
	 * @param string $handle
	 * @param string $src
	 * @param array $deps
	 */
	static public function register_style( $handle, $src, $deps = false )
	{
		return
			wp_register_style(
				$handle,
				sprintf( '%s/%s', PIE_EASY_CSS_URL, $src ),
				$deps,
				PIE_EASY_VERSION
			);
	}

	/**
	 * Register a PIE script
	 *
	 * @param string $handle
	 * @param string $src
	 * @param array $deps
	 */
	static public function register_script( $handle, $src, $deps = false )
	{
		return
			wp_register_script(
				$handle,
				sprintf( '%s/%s', PIE_EASY_JS_URL, $src ),
				$deps,
				PIE_EASY_VERSION
			);
	}

	/**
	 * Enqueue all stylesheets in a directory
	 *
	 * @param string $dir Absolute path the to the directory
	 * @param string $uri URI of the directory
	 * @param string $prefix A prefix for the enqueued handle
	 * @param string $version
	 */
	static public function auto_styles( $dir, $uri, $prefix = null, $version = null )
	{
		// get all css files from dir
		$files = Pie_Easy_Files::list_filtered( $dir, '/\.css$/' );

		// enqueue each one
		foreach ( $files as $file ) {
			wp_enqueue_style(
				$prefix . str_replace( '.css', '', $file),
				sprintf( '%s/%s', $uri, $file ),
				null,
				$version
			);
		}
	}
	
	/**
	 * Enqueue all javascript source files in a directory
	 *
	 * @param string $dir Absolute path the to the directory
	 * @param string $uri URI of the directory
	 * @param string $prefix A prefix for the enqueued handle
	 * @param string $version
	 */
	static public function auto_scripts( $dir, $uri, $prefix = null, $version = null )
	{
		// get all css files from dir
		$files = Pie_Easy_Files::list_filtered( $dir, '/\.js$/' );

		// enqueue each one
		foreach ( $files as $file ) {
			wp_enqueue_script(
				$prefix . str_replace( '.js', '', $file),
				sprintf( '%s/%s', $uri, $file ),
				array( 'jquery' ),
				$version
			);
		}
	}

	/**
	 * Replace all registered jQuery UI scripts with the most recent version
	 *
	 * @param WP_Scripts $wp_scripts
	 */
	public function override_jui( WP_Scripts $wp_scripts )
	{		
		$deps_c = array( 'jquery-ui-core' );
		$deps_cw = array_merge( $deps_c, array( 'jquery-ui-widget' ) );
		$deps_cwm = array_merge( $deps_cw, array( 'jquery-ui-mouse' ) );
		$deps_cwp = array_merge( $deps_cw, array( 'jquery-ui-position' ) );

		$jui = array(
			// core
			'jquery-ui-core' =>
				array( 'src' => 'jquery.ui.core.min.js', 'deps' => array('jquery') ),
			'jquery-ui-widget' =>
				array( 'src' => 'jquery.ui.widget.min.js', 'deps' => array('jquery') ),
			'jquery-ui-position' =>
				array( 'src' => 'jquery.ui.position.min.js', 'deps' => array('jquery') ),
			'jquery-ui-mouse' =>
				array( 'src' => 'jquery.ui.mouse.min.js', 'deps' => $deps_cw ),
			// interactions
			'jquery-ui-draggable' =>
				array( 'src' => 'jquery.ui.draggable.min.js', 'deps' => $deps_cwm ),
			'jquery-ui-droppable' =>
				array( 'src' => 'jquery.ui.droppable.min.js', 'deps' => $deps_cwm ),
			'jquery-ui-resizable' =>
				array( 'src' => 'jquery.ui.resizable.min.js', 'deps' => $deps_cwm ),
			'jquery-ui-selectable' =>
				array( 'src' => 'jquery.ui.selectable.min.js', 'deps' => $deps_cwm ),
			'jquery-ui-sortable' =>
				array( 'src' => 'jquery.ui.sortable.min.js', 'deps' => $deps_cwm ),
			// widgets
			'jquery-ui-accordion' =>
				array( 'src' => 'jquery.ui.accordion.min.js', 'deps' => $deps_cw ),
			'jquery-ui-autocomplete' =>
				array( 'src' => 'jquery.ui.autocomplete.min.js', 'deps' => $deps_cwp ),
			'jquery-ui-button' =>
				array( 'src' => 'jquery.ui.button.min.js', 'deps' => $deps_cw ),
			'jquery-ui-datepicker' =>
				array( 'src' => 'jquery.ui.datepicker.min.js', 'deps' => $deps_c ),
			'jquery-ui-dialog' =>
				array( 'src' => 'jquery.ui.dialog.min.js', 'deps' => $deps_cwp ),
			'jquery-ui-progressbar' =>
				array( 'src' => 'jquery.ui.progressbar.min.js', 'deps' => $deps_cw ),
			'jquery-ui-slider' =>
				array( 'src' => 'jquery.ui.slider.min.js', 'deps' => $deps_cwm ),
			'jquery-ui-tabs' =>
				array( 'src' => 'jquery.ui.tabs.min.js', 'deps' => $deps_cw )
		);

		foreach ( $jui as $handle => $cfg ) {
			$this->override_script(
				$wp_scripts,
				$handle,
				PIE_EASY_JS_URL . '/' . $cfg['src'],
				$cfg['deps'], '1.8.11',
				false,
				1
			);
		}
	}

	/**
	 * Override registered scripts with another script
	 *
	 * @param WP_Scripts $wp_scripts
	 * @param string $handle
	 * @param string $src
	 * @param array $deps
	 * @param string $ver
	 * @param boolean $in_footer
	 * @param integer $group
	 */
	private function override_script( WP_Scripts $wp_scripts, $handle, $src, $deps = array(), $ver = false, $in_footer = false, $group = null )
	{
		// enqueue when done?
		$do_enqueue = false;

		// check if handle queued already
		if ( $wp_scripts->query( $handle, 'queue' ) ) {
			$wp_scripts->dequeue( $handle );
			$do_enqueue = true;
		}

		// get dependancy for handle
		$dependancy = $wp_scripts->query( $handle );

		// existing dependancy?
		if ( $dependancy instanceof _WP_Dependency ) {
			// tweak it
			$dependancy->src = $src;
			$dependancy->deps = $deps;
			$dependancy->ver = $ver;
		} else {
			// register it
			$wp_scripts->add( $handle, $src, $deps, $ver, $in_footer );
			// handle group
			if ( $group ) {
				$wp_scripts->add_data( $handle, 'group', $group );
			}
		}

		// enqueue it?
		if ( $do_enqueue ) {
			$wp_scripts->enqueue( $handle );
		}
	}
}

?>
