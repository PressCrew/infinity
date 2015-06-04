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

ICE_Loader::load(
	'dom/style',
	'dom/script'
);

/**
 * Make enqueing assets easy.
 *
 * @package ICE
 * @subpackage utils
 */
abstract class ICE_Enqueue extends ICE_Base
{
	/**
	 * Will be set to true once enqueue setup has been executed.
	 * 
	 * @var boolean 
	 */
	private $did_setup = false;

	/**
	 * The default style action to use if none specified.
	 *
	 * @var string
	 */
	private $default_action;

	/**
	 * The style actions stack.
	 *
	 * @var array
	 */
	private $actions = array();

	/**
	 * The style objects stack.
	 *
	 * @var array
	 */
	private $objects = array();

	/**
	 * The settings stack.
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * Constructor
	 * 
	 * @internal
	 */
	protected function __construct()
	{
		// this is a singleton
	}

	/**
	 * Initialize enqueuing and set which actions to enqueue assets on.
	 *
	 * @param string $admin_action The admin action.
	 * @param string $blog_action The blog (public) action.
	 */
	static public function init( $admin_action, $blog_action = 'wp_enqueue_scripts' )
	{
		// get instances
		$styles = ICE_Styles::instance();
		$scripts = ICE_Scripts::instance();

		if ( is_admin() ) {
			$styles->set_default_action( $admin_action );
			$scripts->set_default_action( $admin_action );
		} else {
			$styles->set_default_action( $blog_action );
			$scripts->set_default_action( $blog_action );
		}
	}

	/**
	 * Call the WP enqueuer.
	 *
	 * @param string $handle
	 */
	abstract protected function enqueue_now( $handle );

	/**
	 * Register an asset for later enqueueing.
	 *
	 * @param string $handle
	 * @param array $args
	 */
	abstract public function register( $handle, $args = array() );

	/**
	 * The template method is called just in time to register defaults.
	 */
	abstract protected function register_defaults();

	/**
	 * Set the action on which to attach this asset enqueuer.
	 *
	 * @param string $action
	 */
	public function set_default_action( $action )
	{
		// must not have been set yet!
		if ( null === $this->default_action ) {
			// set default action
			$this->default_action = $action;
			// enqueue styles on given action
			add_action( $action, array($this, 'do_enqueue_setup'), 1 );
		}
	}

	/**
	 * Handles internal setup before any assets are enqueued.
	 *
	 * Never call this manually unless you really know what you are doing!
	 *
	 * @internal
	 */
	public function do_enqueue_setup()
	{
		// already run?
		if ( true === $this->did_setup ) {
			// yep, don't run again
			return;
		}

		// register default assets
		$this->register_defaults();

		// update toggle
		$this->did_setup = true;
	}

	/**
	 * Maybe enqueue assets attached to current filter.
	 */
	public function do_enqueue_now()
	{
		global $wp_filter;

		// get current action
		$action = current_filter();
		
		// get current priority
		$priority = key( $wp_filter[ $action ] );

		// loop through handles on this action/priority
		foreach( $this->actions[ $action ][ $priority ] as $handle => $has_cond ) {
			// test conditions
			if (
				false === $has_cond ||
				true === $this->check_condition( $this->settings[ $handle ][ 'condition' ] )
			) {
				// enqueue it
				$this->enqueue_now( $handle );
			}
		}
	}

	/**
	 * Add the settings for a handle and configure actions.
	 *
	 * @param string $handle
	 * @param array $args
	 */
	protected function add( $handle, $args )
	{
		// init some vars
		$action = $priority = $condition = null;

		// default args
		$defaults = array(
			'action' => $this->default_action,
			'priority' => 10,
			'condition' => null
		);

		// parse em
		$settings = wp_parse_args( $args, $defaults );

		// extract em
		extract( $settings, EXTR_IF_EXISTS );

		// been hooked yet?
		if ( false === isset( $this->actions[ $action ][ $priority ] ) ) {
			// nope, hook into action at given priority
			add_action( $action, array( $this, 'do_enqueue_now' ), $priority, 0 );
		}

		// push handle onto actions array
		$this->actions[ $action ][ $priority ][ $handle ] = isset( $condition );

		// push settings onto settings array
		$this->settings[ $handle ] = $settings;
	}

	/**
	 * Return settings for given handle.
	 *
	 * @param string $handle
	 * @return array|false
	 */
	protected function get_settings( $handle )
	{
		// do settings exist?
		if ( true === isset( $this->settings[ $handle ] ) ) {
			// yes, return them
			return $this->settings[ $handle ];
		}

		// no settings found
		return false;
	}

	/**
	 * Add an asset for later enqueueing.
	 *
	 * @param string $handle
	 * @param array $args
	 */
	public function enqueue( $handle, $args = array() )
	{
		// simply call register
		$this->register( $handle, $args );
	}

	/**
	 * Add an asset object for later enqueuing.
	 *
	 * @param ICE_Asset $asset
	 * @param array $args
	 * @return string The handle which was generated.
	 */
	public function enqueue_object( ICE_Asset $asset, $args = array() )
	{
		// get a unique handle
		$handle = $this->generate_handle();

		// add to asset objects stack
		$this->objects[ $handle ] = $asset;

		// register it
		$this->register( $handle, $args );

		// return the handle for caller's reference
		return $handle;
	}

	/**
	 * Returns true if handle has already been added for later enqueuing.
	 *
	 * @param string $handle
	 * @return boolean
	 */
	protected function check_enqueued( $handle )
	{
		return isset( $this->settings[ $handle ] );
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

	/**
	 * Generate a unique handle which does not yet exist in settings stack.
	 *
	 * @return string
	 */
	private function generate_handle()
	{
		do {
			$handle = mt_rand();
		} while( isset( $this->settings[ $handle ] ) );

		return $handle;
	}

	/**
	 * Call render() method on every object in asset object stack.
	 */
	public function render()
	{
		// loop all asset objects
		foreach( $this->objects as $object ) {
			// render it
			$object->render();
		}
		/* @var $object ICE_Asset */
	}
}

/**
 * Make enqueing styles easy.
 *
 * @package ICE
 * @subpackage utils
 */
class ICE_Styles extends ICE_Enqueue
{
	/**
	 * Default UI style handle
	 */
	const UI_STYLE_HANDLE = 'jquery-ui-custom';

	/**
	 * Singleton instance
	 *
	 * @var ICE_Styles
	 */
	static private $instance;

	/**
	 * The stylesheet URL for the UI theme.
	 *
	 * @var string
	 */
	private $ui_stylesheet;

	/**
	 */
	protected function __construct()
	{
		// run parent
		parent::__construct();

		// set default UI stylesheet
		$this->ui_stylesheet( ICE_CSS_URL . '/ui/jquery-ui-1.10.3.custom.css' );
	}

	/**
	 * Return singleton instance
	 *
	 * @return ICE_Styles
	 */
	static public function instance()
	{
		if ( !self::$instance instanceof self ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/**
	 */
	protected function enqueue_now( $handle )
	{
		// try to get settings
		$settings = $this->get_settings( $handle );

		// have settings?
		if ( true === is_array( $settings ) ) {
			// call enqueue with settings as args
			wp_enqueue_style(
				$handle,
				$settings['src'],
				$settings['deps'],
				$settings['ver'],
				$settings['media']
			);
		} else {
			// call enqueue with handle only
			wp_enqueue_style( $handle );
		}
	}

	/**
	 */
	protected function register_defaults()
	{
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

		wp_register_style(
			'ice-ext-blog',
			ICE_CSS_URL . '/ext/blog.css'
		);

		wp_register_style(
			'ice-ext-dash',
			ICE_CSS_URL . '/ext/dash.css',
			array( 'ice-ui' )
		);
	}
	
	/**
	 * Register a style for later enqueueing.
	 *
	 * @param string $handle
	 * @param array $args
	 */
	public function register( $handle, $args = array() )
	{
		// has already been enqueued?
		if ( true === $this->check_enqueued( $handle ) ) {
			// yes, don't overwrite
			return;
		}

		// default args
		$defaults = array(
			'src' => null,
			'deps' => array(),
			'ver' => false,
			'media' => 'all'
		);

		// parse em
		$settings = wp_parse_args( $args, $defaults );

		// call delayed enqueuer
		$this->add( $handle, $settings );
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
}

/**
 * Make enqueing scripts easy.
 *
 * @package ICE
 * @subpackage utils
 */
class ICE_Scripts extends ICE_Enqueue
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
	 * Singleton instance
	 *
	 * @var ICE_Scripts
	 */
	static private $instance;

	/**
	 * Return singleton instance
	 *
	 * @return ICE_Scripts
	 */
	static public function instance()
	{
		if ( !self::$instance instanceof self ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/**
	 */
	protected function enqueue_now( $handle )
	{
		// try to get settings
		$settings = $this->get_settings( $handle );

		// have settings?
		if ( true === is_array( $settings ) ) {
			// call enqueue with settings as args
			wp_enqueue_script(
				$handle,
				$settings['src'],
				$settings['deps'],
				$settings['ver'],
				$settings['in_footer']
			);
		} else {
			// call enqueue with handle only
			wp_enqueue_script( $handle );
		}
	}

	/**
	 */
	public function register_defaults()
	{
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

		wp_register_script(
			'ice-ext-blog',
			ICE_JS_URL . '/ext/blog.js',
			array(),
			ICE_VERSION,
			true
		);

		wp_register_script(
			'ice-ext-dash',
			ICE_JS_URL . '/ext/dash.js',
			array(),
			ICE_VERSION,
			true
		);

		// localize
		$this->localize();
	}

	/**
	 * Localize internal scripts
	 */
	private function localize()
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
	 * Register a script for later enqueueing.
	 *
	 * @param string $handle
	 * @param array $args
	 */
	public function register( $handle, $args = array() )
	{
		// has already been enqueued?
		if ( true === $this->check_enqueued( $handle ) ) {
			// yes, don't overwrite
			return;
		}

		// default args
		$defaults = array(
			'src' => null,
			'deps' => array(),
			'ver' => false,
			'in_footer' => false
		);

		// parse em
		$settings = wp_parse_args( $args, $defaults );

		// call delayed enqueuer
		$this->add( $handle, $settings );
	}
}

//
// Helpers
//

/**
 * Add a style for later enqueuing.
 *
 * @see ICE_Styles::enqueue()
 * @param string $handle
 * @param array $args
 */
function ice_enqueue_style( $handle, $args = array() )
{
	ICE_Styles::instance()->enqueue( $handle, $args );
}

/**
 * Register a style for later enqueuing.
 *
 * @see ICE_Styles::register()
 * @param string $handle
 * @param array $args
 */
function ice_register_style( $handle, $args )
{
	ICE_Styles::instance()->register( $handle, $args );
}

/**
 * Render all dynamic styles.
 *
 * @see ICE_Styles::render()
 */
function ice_render_styles()
{
	ICE_Styles::instance()->render();
}

/**
 * Add a script for later enqueuing.
 *
 * @see ICE_Scripts::enqueue()
 * @param string $handle
 * @param array $args
 */
function ice_enqueue_script( $handle, $args = array() )
{
	ICE_Scripts::instance()->enqueue( $handle, $args = array() );
}

/**
 * Register a script for later enqueuing.
 *
 * @see ICE_Scripts::register()
 * @param string $handle
 * @param array $args
 */
function ice_register_script( $handle, $args )
{
	ICE_Scripts::instance()->register( $handle, $args );
}

/**
 * Render all dynamic scripts.
 *
 * @see ICE_Scripts::render_scripts()
 */
function ice_render_scripts()
{
	ICE_Scripts::instance()->render();
}
