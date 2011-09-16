<?php
/**
 * PIE API: enqueue helpers class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage utils
 * @since 1.0
 */

Pie_Easy_Loader::load( 'collections', 'utils/files' );

/**
 * Make enqueing assets Easy
 *
 * @package PIE
 * @subpackage utils
 */
final class Pie_Easy_Enqueue
{
	/**
	 * Script which handles the AJAX requests
	 */
	const SCRIPT_AJAX = 'admin-ajax.php';

	/**
	 * Script which accepts the async upload
	 */
	const SCRIPT_ASYNC = 'async-upload.php';
	
	/**
	 * Default UI style handle
	 */
	const UI_STYLE_HANDLE = '@:ui';

	/**
	 * @var Pie_Easy_Enqueue
	 */
	static private $instance;

	/**
	 * The style handle for the UI theme
	 *
	 * @var string
	 */
	private $ui_stylesheet;

	/**
	 * This is a singleton
	 */
	private function __construct()
	{
		// use our our actions because things get too freaking confusing
		add_action( 'template_redirect', array($this, 'do_enqueue_styles'), 99999 );
		add_action( 'template_redirect', array($this, 'do_enqueue_scripts'), 99999 );
		add_action( 'admin_init', array($this, 'do_enqueue_styles'), 99999 );
		add_action( 'admin_init', array($this, 'do_enqueue_scripts'), 99999 );
	}

	/**
	 * Return singleton instance
	 *
	 * @return Pie_Easy_Enqueue
	 */
	static public function instance()
	{
		if ( !self::$instance instanceof self ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize actions required for enqueueing to work properly.
	 *
	 * This must be called before anything has created the $wp_scripts global
	 */
	static public function init()
	{
		global $wp_scripts;

		if ( !$wp_scripts instanceof WP_Scripts ) {
			$wp_scripts = new WP_Scripts();
		}
		
		// negative priorities work... shhhh...
		add_action( 'after_setup_theme', array( self::instance(), 'override_jui' ), -99999 );
	}

	/**
	 * Set/Get UI style sheet
	 *
	 * Get or set the style sheet path to the jQuery UI style sheet that should
	 * be enqueued. This is important to ensure that the UI styles load before everything else.
	 *
	 * This method is used internally in PIE. There should be no reason to call this.
	 *
	 * @ignore
	 * @param string $stylesheet
	 */
	public function ui_stylesheet( $stylesheet = null )
	{
		if ( $stylesheet ) {
			if ( empty( $this->ui_stylesheet ) ) {
				$this->ui_stylesheet = $stylesheet;
			} else {
				throw new Exception( 'Cannot set style handle once it has been set' );
			}
		}

		if ( $this->ui_stylesheet ) {
			return $this->ui_stylesheet;
		} else {
			return null;
		}
	}

	/**
	 * Register a PIE style
	 *
	 * @param string $handle
	 * @param string $src
	 * @param array $deps
	 */
	private function register_style( $handle, $src, $deps = false )
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
	private function register_script( $handle, $src, $deps = false )
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
	 *
	public function auto_styles( $dir, $uri, $prefix = null, $version = null )
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
	*/

	/**
	 * Enqueue all javascript source files in a directory
	 *
	 * @param string $dir Absolute path the to the directory
	 * @param string $uri URI of the directory
	 * @param string $prefix A prefix for the enqueued handle
	 * @param string $version
	 *
	public function auto_scripts( $dir, $uri, $prefix = null, $version = null )
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
	*/

	/**
	 * Call enqueue styles action
	 *
	 * Never call this manually unless you really know what you are doing!
	 *
	 * @ignore
	 */
	public function do_enqueue_styles()
	{
		// have a custom ui stylesheet?
		if ( $this->ui_stylesheet ) {
			// register custom ui stylesheet
			wp_register_style(
				self::UI_STYLE_HANDLE,
				$this->ui_stylesheet
			);
		} else {
			// register default ui stylesheet
			$this->register_style(
				self::UI_STYLE_HANDLE,
				'ui/jquery-ui-1.8.16.custom.css'
			);
		}

		// register default styles

		$this->register_style(
			'pie-easy-ui',
			'ui.css',
			array( '@:ui' )
		);

		$this->register_style(
			'pie-easy-colorpicker',
			'colorpicker.css'
		);

		do_action('pie_easy_register_styles');
		do_action('pie_easy_init_styles');
		do_action('pie_easy_enqueue_styles');
	}

	/**
	 * Call enqueue scripts action
	 *
	 * Never call this manually unless you really know what you are doing!
	 *
	 * @ignore
	 */
	public function do_enqueue_scripts()
	{
		// register popular jQuery plugins
		$this->register_script(
			'jquery-cookie', 'jquery.cookie.js', array('jquery') );
		$this->register_script(
			'jquery-jstree', 'jquery.jstree.js', array('jquery','jquery-cookie') );

		// register default scripts
		$this->register_script(
			'pie-easy-colorpicker', 'colorpicker.js', array('jquery') );
		$this->register_script(
			'jquery-ui-nestedsortable', 'jquery.ui.nestedSortable.js', array('jquery', 'jquery-ui-sortable') );
		$this->register_script(
			'jquery-swfupload', 'jquery.swfupload.js', array('jquery', 'swfupload-all') );
		$this->register_script(
			'pie-easy-global', 'global.js', array('pie-easy-colorpicker', 'jquery-ui-button') );
		$this->register_script(
			'pie-easy-uploader', 'uploader.js', array('pie-easy-global', 'jquery-swfupload', 'jquery-ui-button') );

		// localize
		$this->localize_scripts();

		// actions!
		do_action('pie_easy_register_scripts');
		do_action('pie_easy_init_scripts');
		do_action('pie_easy_enqueue_scripts');
		do_action('pie_easy_localize_scripts');
	}

	/**
	 * Localize internal scripts
	 */
	private function localize_scripts()
	{
		wp_localize_script(
			'pie-easy-global',
			'pieEasyGlobalL10n',
			array(
				'ajax_url' => admin_url( self::SCRIPT_AJAX ),
				'async_url' => admin_url( self::SCRIPT_ASYNC )
			)
		);

	}

	/**
	 * Replace all registered jQuery UI scripts with the most recent version
	 *
	 * Never call this manually unless you really know what you are doing!
	 *
	 * @ignore
	 */
	public function override_jui()
	{
		global $wp_scripts;

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

		// override stable scripts
		foreach ( $jui as $handle => $cfg ) {
			$this->override_script(
				$wp_scripts,
				$handle,
				PIE_EASY_JS_URL . '/' . $cfg['src'],
				$cfg['deps'],
				'1.8.11',
				false,
				1
			);
		}

		// menu is experimental!
		$this->override_script(
			$wp_scripts,
			'jquery-ui-menu',
			PIE_EASY_JS_URL . '/' . 'jquery.ui.menu.min.js',
			$deps_cw,
			'1.9m2',
			false,
			1
		);
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
