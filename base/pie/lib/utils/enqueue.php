<?php
/**
 * PIE API: enqueue helpers class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
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
final class Pie_Easy_Enqueue extends Pie_Easy_Base
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
	 * @internal
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
	static public function instance()
	{
		if ( !self::$instance instanceof self ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Initialize actions required for enqueueing to work properly.
	 */
	private function init()
	{
		global $wp_version;
		
		if ( (float) $wp_version < 3.3 ) {
			// negative priorities work... shhhh...
			add_action( 'after_setup_theme', array( self::instance(), 'register_ui_scripts' ), -99999 );
		}
	}

	/**
	 * Add an action on which to attach the style enqueuer
	 *
	 * @param string $action
	 * @param integer $priority
	 */
	public function styles_on_action( $action, $priority = null )
	{
		// handle empty priority
		if ( empty( $priority ) ) {
			$priority = 99999;
		}
		
		// enqueue styles on given action
		add_action( $action, array($this, 'do_enqueue_styles'), $priority );

		return $this;
	}

	/**
	 * Add an action on which to attach the script enqueuer
	 *
	 * @param string $action
	 * @param integer $priority
	 */
	public function scripts_on_action( $action, $priority = null )
	{
		// handle empty priority
		if ( empty( $priority ) ) {
			$priority = 99999;
		}

		// enqueue scripts on given action
		add_action( $action, array($this, 'do_enqueue_scripts'), $priority );

		return $this;
	}

	/**
	 * Set/Get UI style sheet
	 *
	 * Get or set the style sheet path to the jQuery UI style sheet that should
	 * be enqueued. This is important to ensure that the UI styles load before everything else.
	 *
	 * This method is used internally in PIE. There should be no reason to call this.
	 *
	 * @internal
	 * @param string $stylesheet Relative path to jQuery UI stylesheet
	 */
	final public function ui_stylesheet( $stylesheet = null )
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
	 * Call enqueue styles action
	 *
	 * Never call this manually unless you really know what you are doing!
	 *
	 * @internal
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
			'jquery-juicy',
			'juicy/jquery.juicy.css',
			array( '@:ui' )
		);

		$this->register_style(
			'pie-easy-ui',
			'ui.css',
			array( 'jquery-juicy' )
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
	 * @internal
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
			'webfont', 'webfont.js' );
		$this->register_script(
			'pie-easy-colorpicker', 'colorpicker.js', array('jquery') );
		$this->register_script(
			'jquery-ui-menu', 'jquery.ui.menu.min.js', array( 'jquery-ui-core', 'jquery-ui-widget' ) );
		$this->register_script(
			'jquery-ui-nestedsortable', 'jquery.ui.nestedSortable.js', array('jquery', 'jquery-ui-sortable') );
		$this->register_script(
			'jquery-swfupload', 'jquery.swfupload.js', array('jquery', 'swfupload-all') );
		$this->register_script(
			'pie-easy-global', 'global.js', array('pie-easy-colorpicker') );
		$this->register_script(
			'pie-easy-slider', 'slider.js', array('pie-easy-global', 'jquery-ui-slider') );
		$this->register_script(
			'pie-easy-scrollpane', 'scrollpane.js', array('pie-easy-global', 'jquery-ui-slider') );
		$this->register_script(
			'pie-easy-uploader', 'uploader.js', array('pie-easy-global', 'jquery-swfupload', 'jquery-ui-button') );
		$this->register_script(
			'jquery-juicy-browsertabs', 'juicy/jquery.juicy.browsertabs.js', array('jquery-ui-tabs') );
		$this->register_script(
			'jquery-juicy-buttonmenu', 'juicy/jquery.juicy.buttonmenu.js', array('jquery-ui-button','jquery-ui-menu') );
		$this->register_script(
			'jquery-juicy-buttonselect', 'juicy/jquery.juicy.buttonselect.js', array('jquery-ui-button') );
		$this->register_script(
			'jquery-juicy-flashmesg', 'juicy/jquery.juicy.flashmesg.js', array('jquery-ui-widget') );
		$this->register_script(
			'jquery-juicy-titlebox', 'juicy/jquery.juicy.titlebox.js', array('jquery-ui-widget') );
		$this->register_script(
			'jquery-juicy-toolbar', 'juicy/jquery.juicy.toolbar.js', array('jquery-ui-widget','jquery-ui-button') );
		$this->register_script(
			'jquery-juicy-fontfilter', 'juicy/jquery.juicy.fontfilter.js', array('jquery-juicy-titlebox','jquery-juicy-buttonselect','pie-easy-slider','webfont') );

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
	 * Register additional jQuery UI scripts
	 *
	 * Never call this manually unless you really know what you are doing!
	 *
	 * @internal
	 */
	public function register_ui_scripts()
	{
		global $wp_scripts;

		if ( !$wp_scripts instanceof WP_Scripts ) {
			$wp_scripts = new WP_Scripts();
		}

		$deps_c = array( 'jquery-ui-core' );
		$deps_cw = array_merge( $deps_c, array( 'jquery-ui-widget' ) );
		$deps_cwm = array_merge( $deps_cw, array( 'jquery-ui-mouse' ) );
		$deps_cwp = array_merge( $deps_cw, array( 'jquery-ui-position' ) );

		$jui = array(
			// widgets
			'jquery-ui-accordion' =>
				array( 'src' => 'jquery.ui.accordion.min.js', 'deps' => $deps_cw ),
			'jquery-ui-autocomplete' =>
				array( 'src' => 'jquery.ui.autocomplete.min.js', 'deps' => $deps_cwp ),
			'jquery-ui-datepicker' =>
				array( 'src' => 'jquery.ui.datepicker.min.js', 'deps' => $deps_c ),
			'jquery-ui-progressbar' =>
				array( 'src' => 'jquery.ui.progressbar.min.js', 'deps' => $deps_cw ),
			'jquery-ui-slider' =>
				array( 'src' => 'jquery.ui.slider.min.js', 'deps' => $deps_cwm )
		);

		// register more scripts
		foreach ( $jui as $handle => $cfg ) {
			// make sure not registered already
			if ( !$wp_scripts->query( $handle ) ) {
				// register it
				$wp_scripts->add( $handle, PIE_EASY_JS_URL . '/' . $cfg['src'], $cfg['deps'], '1.8.12' );
				// put in footer group
				$wp_scripts->add_data( $handle, 'group', 1 );
			}
		}
	}
}

?>
