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
	const UI_STYLE_HANDLE = 'jquery-ui-custom';

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
	 * The default style action to use if none specified.
	 *
	 * @var string
	 */
	private $style_action;

	/**
	 * The style actions stack.
	 *
	 * @var array
	 */
	private $style_actions = array();

	/**
	 * The style settings stack.
	 *
	 * @var array
	 */
	private $style_settings = array();

	/**
	 * The default script action to use if none specified.
	 *
	 * @var string
	 */
	private $script_action;

	/**
	 * The script actions stack.
	 *
	 * @var array
	 */
	private $script_actions = array();

	/**
	 * The script settings stack.
	 *
	 * @var array
	 */
	private $script_settings = array();

	/**
	 * The stylesheet URL for the UI theme.
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

		// set default UI stylesheet
		self::instance()
			->ui_stylesheet( ICE_CSS_URL . '/ui/jquery-ui-1.10.3.custom.css' );
	}

	/**
	 * Add an action on which to attach the style enqueuer
	 *
	 * @param string $action
	 * @param integer $priority
	 */
	public function styles_on_action( $action, $priority = null )
	{
		// set default action
		$this->style_action = $action;
		
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
		// set default script action
		$this->script_action = $action;

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
	 * Get or set the stylesheet URL of the jQuery UI style sheet that should
	 * be enqueued. This is important to ensure that the UI styles load before everything else.
	 *
	 * @param string $stylesheet URL of jQuery UI stylesheet
	 * @return string
	 */
	final public function ui_stylesheet( $stylesheet = null )
	{
		// are we setting?
		if ( $stylesheet ) {
			// yep, set it
			$this->ui_stylesheet = $stylesheet;
		}

		// return it
		return $this->ui_stylesheet;
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

		// register default styles
		
		wp_register_style(
			self::UI_STYLE_HANDLE,
			$this->ui_stylesheet
		);

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

		// hook up styles internal handler
		add_action( 'ice_enqueue_styles', array( $this, 'do_enqueue_style_deps' ), 1 );

		do_action('ice_init_styles');
		do_action('ice_register_styles');
		do_action('ice_enqueue_styles');
	}

	/**
	 * Maybe enqueue styles attached to current filter.
	 */
	public function do_enqueue_style()
	{
		// action is current filter
		$action = current_filter();

		// loop through handles on this action
		foreach( $this->style_actions[ $action ] as $handle ) {
			// test conditions
			if ( $this->check_condition( $this->style_settings[ $handle ][ 'condition' ] ) ) {
				// enqueue it
				wp_enqueue_style( $handle );
			}
		}
	}

	/**
	 * Handle enqueing style dependancies
	 *
	 * @internal
	 */
	public function do_enqueue_style_deps()
	{
		// enqueue injected style depends for every policy
		foreach( ICE_Policy::all() as $policy ) {
			// loop through all registered components
			foreach ( $policy->registry()->get_all() as $component ) {
				// call style dep enqueuer
				$component->style()->enqueue_deps();
			}
		}
	}

	/**
	 * Maybe enqueue scripts attached to current filter.
	 */
	public function do_enqueue_script()
	{
		// action is current filter
		$action = current_filter();

		// loop through handles on this action
		foreach( $this->script_actions[ $action ] as $handle ) {
			// test conditions
			if ( $this->check_condition( $this->script_settings[ $handle ][ 'condition' ] ) ) {
				// enqueue it
				wp_enqueue_script( $handle );
			}
		}
	}

	/**
	 * Handle enqueuing script dependancies.
	 *
	 * @internal
	 */
	public function do_enqueue_script_deps()
	{
		// enqueue injected script depends for every policy
		foreach( ICE_Policy::all() as $policy ) {
			// loop through all registered components
			foreach ( $policy->registry()->get_all() as $component ) {
				// call script dep enqueuer
				$component->script()->enqueue_deps();
			}
		}
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

		// hook up scripts internal handler
		add_action( 'ice_enqueue_scripts', array( $this, 'do_enqueue_script_deps' ), 1 );
		
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

	/**
	 * Register a style for later enqueueing.
	 *
	 * @param string $handle
	 * @param array $args
	 */
	public function register_style( $handle, $args )
	{
		// init local vars
		$src = $deps = $ver = $media = $action = $priority = null;

		// default args
		$defaults = array(
			'src' => null,
			'deps' => array(),
			'ver' => false,
			'media' => 'all',
			'action' => $this->style_action,
			'priority' => 10,
			'condition' => null
		);

		// parse em
		$settings = wp_parse_args( $args, $defaults );

		// extract em
		extract( $settings, EXTR_IF_EXISTS );

		// register style
		wp_register_style( $handle, $src, $deps, $ver, $media );

		// been hooked yet?
		if ( false === isset( $this->style_actions[ $action ] ) ) {
			// nope, hook into action
			add_action( $action, array( $this, 'do_enqueue_style' ), $priority, 0 );
		}

		// push handle onto style actions array
		$this->style_actions[ $action ][] = $handle;

		// push settings onto settings array
		$this->style_settings[ $handle ] = $settings;
	}

	/**
	 * Register a script for later enqueueing.
	 *
	 * @param string $handle
	 * @param array $args
	 */
	public function register_script( $handle, $args )
	{
		// init local vars
		$src = $deps = $ver = $in_footer = $action = $priority = null;

		// default args
		$defaults = array(
			'src' => null,
			'deps' => array(),
			'ver' => false,
			'in_footer' => false,
			'action' => $this->script_action,
			'priority' => 10,
			'condition' => null
		);

		// parse em
		$settings = wp_parse_args( $args, $defaults );

		// extract em
		extract( $settings, EXTR_IF_EXISTS );

		// register script
		wp_register_script( $handle, $src, $deps, $ver, $in_footer );

		// been hooked yet?
		if ( false === isset( $this->script_actions[ $action ] ) ) {
			// nope, hook into action
			add_action( $action, array( $this, 'do_enqueue_script' ), $priority, 0 );
		}

		// push handle onto script actions array
		$this->script_actions[ $action ][] = $handle;

		// push settings onto settings array
		$this->script_settings[ $handle ] = $settings;
	}

	/**
	 * Test if condition callback(s) eval to true.
	 *
	 * @param string|array $condition
	 * @return boolean
	 */
	private function check_condition( $condition )
	{
		// have a condition?
		if ( false === empty( $condition ) ) {

			// condition must be an array so we can loop it
			settype( $condition, 'array' );

			// loop all conditions
			foreach( $condition as $callback ) {
				// try to exec the callback
				if ( false === is_callable( $callback ) || true !== call_user_func( $callback ) ) {
					// callback did not eval to true, test failed
					return false;
				}
			}
		}

		// no conditions set, or all conditions eval'd true
		return true;
	}
}

//
// Helpers
//

/**
 * Register a style for later enqueuing.
 *
 * @see ICE_Enqueue::register_style()
 * @param string $handle
 * @param array $args
 */
function ice_register_style( $handle, $args )
{
	ICE_Enqueue::instance()->register_style( $handle, $args );
}

/**
 * Register a script for later enqueuing.
 *
 * @see ICE_Enqueue::register_script()
 * @param string $handle
 * @param array $args
 */
function ice_register_script( $handle, $args )
{
	ICE_Enqueue::instance()->register_script( $handle, $args );
}
