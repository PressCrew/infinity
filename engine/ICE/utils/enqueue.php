<?php
/**
 * ICE API: enqueue helpers class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage utils
 * @since 1.0
 */

/**
 * Make enqueing assets Easy
 *
 * @package ICE
 * @subpackage utils
 */
final class ICE_Enqueue extends ICE_Base
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
	const UI_STYLE_HANDLE = 'custom-ui';

	/**
	 * Singleton instance
	 * 
	 * @var ICE_Enqueue
	 */
	static private $instance;

	/**
	 * Will be set to true once style enqueuer has been executed
	 * 
	 * @var boolean 
	 */
	private $did_styles = false;

	/**
	 * Will be set to true once script enqueuer has been executed
	 * 
	 * @var boolean
	 */
	private $did_scripts = false;

	/**
	 * The style handle for the UI theme
	 *
	 * @var string
	 */
	private $ui_stylesheet;

	/**
	 * Constructor
	 * 
	 * @internal
	 */
	private function __construct()
	{
		// this is a singleton
	}

	/**
	 * Return singleton instance
	 *
	 * @return ICE_Enqueue
	 */
	static public function instance()
	{
		if ( !self::$instance instanceof self ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize enqueuing and set which actions to enqueue assets on.
	 *
	 * @param string $admin_action The admin action.
	 * @param string $blog_action The blog (public) action.
	 */
	static public function init( $admin_action, $blog_action = 'wp_enqueue_scripts' )
	{
		if ( is_admin() ) {
			self::instance()
				->styles_on_action( $admin_action )
				->scripts_on_action( $admin_action );
		} else {
			self::instance()
				->styles_on_action( $blog_action )
				->scripts_on_action( $blog_action );
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
	 * This method is used internally in ICE. There should be no reason to call this.
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
	 * Call enqueue styles action
	 *
	 * Never call this manually unless you really know what you are doing!
	 *
	 * @internal
	 */
	public function do_enqueue_styles()
	{
		// already run?
		if ( true === $this->did_styles ) {
			// yep, don't run again
			return;
		} else {
			// update toggle
			$this->did_styles = true;
		}

		// have a custom ui stylesheet?
		if ( $this->ui_stylesheet ) {
			// register custom ui stylesheet
			wp_register_style(
				self::UI_STYLE_HANDLE,
				ICE_CSS_URL . '/' . $this->ui_stylesheet
			);
		} else {
			// register default ui stylesheet
			wp_register_style(
				self::UI_STYLE_HANDLE,
				ICE_CSS_URL . '/ui/jquery-ui-1.10.3.custom.css'
			);
		}

		// register default styles

		wp_register_style(
			'jquery-juicy',
			ICE_CSS_URL . '/juicy/jquery.juicy.css',
			array( self::UI_STYLE_HANDLE )
		);

		wp_register_style(
			'ice-ui',
			ICE_CSS_URL . '/ui.css',
			array( 'jquery-juicy' )
		);

		do_action('ice_init_styles');
		do_action('ice_register_styles');
		do_action('ice_enqueue_styles');
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
		// already run?
		if ( true === $this->did_scripts ) {
			// yep, don't run again
			return;
		} else {
			// update toggle
			$this->did_scripts = true;
		}
		
		// register popular jQuery plugins

		wp_register_script(
			'jquery-cookie',
			ICE_JS_URL . '/jquery.kookie.js',
			array( 'jquery' ),
			ICE_VERSION,
			true
		);

		wp_register_script(
			'jquery-fitvids',
			ICE_JS_URL . '/jquery.fitvids.js',
			array( 'jquery' ),
			'1.0',
			true
		);

		wp_register_script(
			'jquery-mobilemenu',
			ICE_JS_URL . '/jquery.mobilemenu.js',
			array( 'jquery' ),
			ICE_VERSION,
			true
		);

		wp_register_script(
			'jquery-transit',
			ICE_JS_URL . '/jquery.transit.min.js',
			array( 'jquery' ),
			ICE_VERSION,
			true
		);

		// register default scripts

		wp_register_script(
			'modernizr-custom',
			ICE_JS_URL . '/modernizr-custom.js',
			array(),
			'2.6.1',
			false
		);

		wp_register_script(
			'webfont',
			ICE_JS_URL . '/webfont.js',
			array(),
			ICE_VERSION,
			true
		);

		wp_register_script(
			'jquery-ui-nestedsortable',
			ICE_JS_URL . '/jquery.ui.nestedSortable.js',
			array( 'jquery', 'jquery-ui-sortable' ),
			'1.3.4',
			true
		);

		wp_register_script(
			'ice-global',
			ICE_JS_URL . '/global.js',
			array(),
			ICE_VERSION,
			true
		);

		wp_register_script(
			'ice-slider',
			ICE_JS_URL . '/slider.js',
			array( 'ice-global', 'jquery-ui-slider' ),
			ICE_VERSION,
			true
		);

		wp_register_script(
			'ice-scrollpane',
			ICE_JS_URL . '/scrollpane.js',
			array( 'ice-global', 'jquery-ui-slider' ),
			ICE_VERSION,
			true
		);

		wp_register_script(
			'jquery-juicy-buttonmenu',
			ICE_JS_URL . '/juicy/jquery.juicy.buttonmenu.js',
			array( 'jquery-ui-button', 'jquery-ui-menu' ),
			ICE_VERSION,
			true
		);

		wp_register_script(
			'jquery-juicy-buttonselect',
			ICE_JS_URL . '/juicy/jquery.juicy.buttonselect.js',
			array( 'jquery-ui-button' ),
			ICE_VERSION,
			true
		);

		wp_register_script(
			'jquery-juicy-flashmesg',
			ICE_JS_URL . '/juicy/jquery.juicy.flashmesg.js',
			array( 'jquery-ui-widget' ),
			ICE_VERSION,
			true
		);

		wp_register_script(
			'jquery-juicy-titlebox',
			ICE_JS_URL . '/juicy/jquery.juicy.titlebox.js',
			array( 'jquery-ui-widget' ),
			ICE_VERSION,
			true
		);

		wp_register_script(
			'jquery-juicy-toolbar',
			ICE_JS_URL . '/juicy/jquery.juicy.toolbar.js',
			array( 'jquery-ui-widget', 'jquery-ui-button' ),
			ICE_VERSION,
			true
		);

		wp_register_script(
			'jquery-juicy-fontfilter',
			ICE_JS_URL . '/juicy/jquery.juicy.fontfilter.js',
			array( 'jquery-juicy-titlebox', 'jquery-juicy-buttonselect', 'ice-slider', 'webfont' ),
			ICE_VERSION,
			true
		);

		// localize
		$this->localize_scripts();

		// actions!
		do_action('ice_init_scripts');
		do_action('ice_register_scripts');
		do_action('ice_enqueue_scripts');
		do_action('ice_localize_scripts');
	}

	/**
	 * Localize internal scripts
	 */
	private function localize_scripts()
	{
		wp_localize_script(
			'ice-global',
			'iceEasyGlobalL10n',
			array(
				'ajax_url' => admin_url( self::SCRIPT_AJAX ),
				'async_url' => admin_url( self::SCRIPT_ASYNC )
			)
		);

	}
}
