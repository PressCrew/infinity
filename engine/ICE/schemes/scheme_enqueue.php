<?php
/**
 * ICE API: schemes scheme styles and scripts enqueuer class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage schemes
 * @since 1.0
 */

/**
 * Make enqueuing scheme styles and scripts easy
 *
 * @todo this class needs to be split into three classes (pyramid)
 * @package ICE
 * @subpackage schemes
 */
class ICE_Scheme_Enqueue extends ICE_Base
{
	/**
	 *  Trigger map path key
	 */
	const TRIGGER_PATH = 'path';

	/**
	 *  Trigger map dependencies key
	 */
	const TRIGGER_DEPS = 'deps';

	/**
	 *  Trigger map actions key
	 */
	const TRIGGER_ACTS = 'actions';

	/**
	 *  Trigger map conditions key
	 */
	const TRIGGER_CONDS = 'conditions';

	/**
	 * The scheme instance which owns this instance
	 * 
	 * @var ICE_Scheme
	 */
	private $scheme;

	/**
	 * Map of style configurations
	 *
	 * @var ICE_Map
	 */
	private $styles;

	/**
	 * Stack of styles to ignore
	 *
	 * @var ICE_Stack
	 */
	private $styles_ignore;

	/**
	 * Map of script configurations
	 *
	 * @var ICE_Map
	 */
	private $scripts;

	/**
	 * The domain to set document.domain to
	 *
	 * @var string
	 */
	private $script_domain;

	/**
	 * Initialize the enqueuer by passing a valid ICE scheme object
	 *
	 * @param ICE_Scheme $scheme
	 */
	public function __construct( ICE_Scheme $scheme )
	{
		// set scheme
		$this->scheme = $scheme;

		// define script domain
		$this->script_domain = $this->scheme->settings()->get_value( ICE_Scheme::SETTING_SCRIPT_DOMAIN );

		// hook up script domain handler
		if ( $this->script_domain ) {
			add_action( 'wp_print_scripts', array( $this, 'handle_script_domain' ) );
			add_action( 'admin_print_scripts', array( $this, 'handle_script_domain' ) );
		}

		// init styles maps
		$this->styles = new ICE_Map();
		$this->styles_ignore = new ICE_Stack();

		// get style defs
		$style_defs =
			$this->scheme->settings()->get_stack(
				ICE_Scheme::SETTING_STYLE_DEFS
			);

		// create new map so we can write to it
		$style_defs_w = new ICE_Map( $style_defs );

		// init internal styles
		$this->setup_internal_styles( $style_defs_w );

		// define styles
		$this->define_styles( $style_defs_w );

		// register styles
		add_action( 'ice_register_styles', array( $this, 'register_styles' ) );
		
		// init scripts map
		$this->scripts = new ICE_Map();

		// get script defs
		$script_defs =
			$this->scheme->settings()->get_stack(
				ICE_Scheme::SETTING_SCRIPT_DEFS
			);

		// create new map so we can write to it
		$script_defs_w = new ICE_Map( $script_defs );

		// init internal scripts
		$this->setup_internal_scripts( $script_defs_w );
		
		// define scripts
		$this->define_scripts( $script_defs_w );

		// register scripts
		add_action( 'ice_register_scripts', array( $this, 'register_scripts' ) );
	}

	/**
	 * Create a unique handle for enqueing
	 *
	 * @param string $theme
	 * @param string $handle
	 * @return string
	 */
	private function make_handle( $theme, $handle )
	{		
		if ( substr( $handle, 0, 6 ) === 'icenq-' ) {
			return $handle;
		}

		return sprintf( '%s-%s', $theme, trim( $handle ) );
	}

	/**
	 * Set UI environment
	 */
	private function setup_ui()
	{
		// get custom ui stylesheet setting
		$ui_stylesheet_value = $this->scheme->settings()->get_value( ICE_Scheme::SETTING_UI_STYLESHEET );
		$ui_stylesheet_theme = $this->scheme->settings()->get_theme( ICE_Scheme::SETTING_UI_STYLESHEET );
		
		// custom ui stylesheet set?
		if ( $ui_stylesheet_value ) {
			ICE_Enqueue::instance()->ui_stylesheet(
				ICE_Scheme::instance()->theme_file_url( $ui_stylesheet_theme, $ui_stylesheet_value )
			);
		}
	}

	/**
	 * Inject internal stylesheets into style settings
	 *
	 * @internal
	 * @param ICE_Map $style_defs
	 */
	private function setup_internal_styles( ICE_Map $style_defs )
	{
		// style setting
		$setting = array(
			'style' => get_bloginfo( 'stylesheet_url' )
		);

		// add the theme style.css LAST
		$style_defs->add( 'icenq', $setting, true );

		// hook up styles internal handler
		add_action( 'ice_enqueue_styles', array( $this, 'handle_style_internal' ), 1 );

		// init ui env
		$this->setup_ui();
	}

	/**
	 * Inject internal scripts into script settings
	 *
	 * @internal
	 * @param ICE_Map $script_defs
	 */
	private function setup_internal_scripts( ICE_Map $script_defs )
	{
		// hook up scripts internal handler
		add_action( 'ice_enqueue_scripts', array( $this, 'handle_script_internal' ) );
	}

	/**
	 * Try to define triggers which have been set in the scheme's config
	 *
	 * @param ICE_Map $map
	 * @param array $settings
	 * @return boolean
	 */
	private function define( ICE_Map $map, $settings )
	{
		// loop through and populate trigger map
		foreach ( $settings as $theme => $setting ) {

			// is setting value an array?
			if ( is_array( $setting ) ) {

				// yes, add each handle and URL path to map
				foreach( $setting as $handle => $path ) {

					// define it
					$this->define_one( $map, $theme, $handle, $path );
				}
			}
		}

		return true;
	}

	/**
	 * Define ONE trigger
	 *
	 * @param ICE_Map $map
	 * @param string $theme
	 * @param string $handle
	 * @param string $path
	 * @return boolean
	 */
	private function define_one( ICE_Map $map, $theme, $handle, $path )
	{
		// new map for this trigger
		$trigger = new ICE_Map();

		// add path value
		$trigger->add( self::TRIGGER_PATH, $this->enqueue_path($theme, $path) );

		// init deps stack
		$trigger->add( self::TRIGGER_DEPS, new ICE_Stack() );

		// init empty actions stack
		$trigger->add( self::TRIGGER_ACTS, new ICE_Stack() );

		// init empty conditions stack
		$trigger->add( self::TRIGGER_CONDS, new ICE_Stack() );

		// add trigger to main map
		$map->add( $this->make_handle( $theme, $handle ), $trigger );

		return $trigger;
	}

	/**
	 * Define all styles for given style defs map
	 *
	 * @internal
	 * @param ICE_Map $style_defs
	 */
	private function define_styles( ICE_Map $style_defs )
	{
		return $this->define( $this->styles, $style_defs );
	}

	/**
	 * Define all scripts for given script defs map
	 *
	 * @param ICE_Map $script_defs
	 */
	private function define_scripts( ICE_Map $script_defs )
	{
		return $this->define( $this->scripts, $script_defs );
	}

	/**
	 * Set dependancies for specified settings.
	 *
	 * This is for scheme settings that define a handle with a
	 * value being a delimeted string of dependant style or script handles
	 *
	 * @param ICE_Map $map
	 * @param array $settings
	 * @return boolean
	 */
	private function depends( ICE_Map $map, $settings )
	{
		// loop through and update triggers map with deps
		foreach ( $settings as $theme => $setting ) {
			
			// is setting value an array?
			if ( is_array( $setting ) ) {

				// yes, add action to each trigger's dependancy stack
				foreach( $setting as $handle => $dep_handles ) {

					// get theme handle
					$theme_handle = $this->make_handle( $theme, $handle );

					// loop through each handle
					foreach ( $dep_handles as $dep_handle ) {

						// get dep_theme handle
						$dep_theme_handle = $this->make_handle( $theme, $dep_handle );

						// does dep theme handle exist?
						if ( $map->item_at( $dep_theme_handle ) ) {
							// yep, use it
							$dep_handle = $dep_theme_handle;
						}

						// make sure theme handle exists in map
						if ( $map->contains($theme_handle) ) {
							// push onto trigger's dep stack
							$map->item_at($theme_handle)->item_at(self::TRIGGER_DEPS)->push($dep_handle);
						}
					}
				}
			}
		}

		return true;
	}

	/**
	 * Set triggers for specified settings.
	 *
	 * This is for scheme settings that define a trigger with a
	 * value being a delimeted string of style or script handles
	 *
	 * @param ICE_Map $map
	 * @param string $setting_name
	 * @param string $trigger_type
	 * @param string $trigger_action
	 * @return boolean
	 */
	private function triggers( ICE_Map $map, $setting_name, $trigger_type, $trigger_action = null )
	{
		// get trigger settings for all themes
		$settings = $this->scheme->settings()->get_stack( $setting_name );

		// check if at least one theme defined this trigger
		if ( $settings ) {

			// loop through and update triggers map
			foreach ( $settings as $theme => $setting ) {

				// is setting value an array?
				if ( is_array( $setting ) ) {

					// yes, add action to each trigger's trigger stack
					foreach( $setting as $handle => $actions ) {

						// is actions NOT an array?
						if ( false === is_array( $actions ) ) {
							// convert actions to an array
							$actions = array( $actions );
						}

						// loop through each action
						foreach ( $actions as $action ) {

							// is this an override situation? (exact handle already in map)
							if ( !$map->item_at( $handle ) ) {
								// not an override, use theme handle
								$handle = $this->make_handle( $theme, $handle );
							}

							// does trigger handle exist?
							if ( $map->item_at( $handle ) ) {
								// push onto trigger's trigger stack
								$map->item_at($handle)->item_at($trigger_type)->push($action);
							}
						}
						
						// is this an actions trigger type?
						if ( $trigger_type == self::TRIGGER_ACTS ) {
							// yes, hook it to action handler
							add_action( $action, array( $this, 'handle_' . $setting_name ) );
						}
					}
				}
			}

			// is this a conditions trigger type?
			if ( $trigger_type == self::TRIGGER_CONDS && $trigger_action ) {
				// yes, hook up conditions handler
				add_action(
					$trigger_action,
					array( $this, 'handle_' . $setting_name ),
					11
				);
			}

			return true;
		}

		return false;
	}

	/**
	 * Determine path to enqueue
	 *
	 * @param string $theme
	 * @param string $path
	 * @return string
	 */
	private function enqueue_path( $theme, $path )
	{
		if ( preg_match( '/^https?:\/\//i', $path ) ) {
			return $path;
		} else {
			return ICE_Scheme::instance()->theme_file_url( $theme, $path );
		}
	}

	/**
	 * Register a style with data from a config map
	 *
	 * @param string $handle
	 * @param string $config_map
	 */
	private function register_style( $handle, $config_map )
	{
		if ( $this->styles_ignore->contains( $handle ) ) {
			return;
		}

		$deps = array();

		foreach ( $config_map->item_at(self::TRIGGER_DEPS)->to_array() as $dep ) {
			if ( $this->styles->contains( $dep ) || wp_style_is( $dep, 'registered' ) ) {
				array_push( $deps, $dep );
			}
		}

		// reg it
		wp_register_style(
			$handle,
			$config_map->item_at(self::TRIGGER_PATH),
			$deps
		);
	}

	/**
	 * Register all styles
	 */
	final public function register_styles()
	{
		// get dep settings for all themes
		$style_depends =
			$this->scheme->settings()->get_stack(
				ICE_Scheme::SETTING_STYLE_DEPS
			);

		// check if at least one theme defined this dep
		if ( $style_depends ) {
			// init style depends
			$this->depends( $this->styles, $style_depends );
		}

		// init style action triggers
		$this->triggers(
			$this->styles,
			ICE_Scheme::SETTING_STYLE_ACTS,
			self::TRIGGER_ACTS
		);

		// init style condition triggers
		$this->triggers(
			$this->styles,
			ICE_Scheme::SETTING_STYLE_CONDS,
			self::TRIGGER_CONDS,
			'ice_enqueue_styles'
		);

		foreach ( $this->styles as $handle => $config_map ) {
			$this->register_style( $handle, $config_map );
		}
	}

	/**
	 * Register a script with data from a config map
	 *
	 * @param string $handle
	 * @param string $config_map
	 */
	private function register_script( $handle, $config_map )
	{
		$deps = array();

		foreach ( $config_map->item_at(self::TRIGGER_DEPS)->to_array() as $dep ) {
			if ( $this->scripts->contains( $dep ) || wp_script_is( $dep, 'registered' ) ) {
				array_push( $deps, $dep );
			}
		}

		// do it
		wp_register_script(
			$handle,
			$config_map->item_at(self::TRIGGER_PATH),
			$deps,
			false,
			true
		);
	}

	/**
	 * Register all scripts
	 */
	final public function register_scripts()
	{
		// get dep settings for all themes
		$script_depends =
			$this->scheme->settings()->get_stack(
				ICE_Scheme::SETTING_SCRIPT_DEPS
			);

		// check if at least one theme defined this dep
		if ( $script_depends ) {
			// init script depends
			$this->depends( $this->scripts, $script_depends );
		}

		// init script action triggers
		$this->triggers(
			$this->scripts,
			ICE_Scheme::SETTING_SCRIPT_ACTS,
			self::TRIGGER_ACTS
		);

		// init script condition triggers
		$this->triggers(
			$this->scripts,
			ICE_Scheme::SETTING_SCRIPT_CONDS,
			self::TRIGGER_CONDS,
			'ice_enqueue_scripts'
		);

		foreach ( $this->scripts as $handle => $config_map ) {
			$this->register_script( $handle, $config_map );
		}
	}

	/**
	 * Handle enqueing internal styles
	 *
	 * @internal
	 */
	public function handle_style_internal()
	{
		// enqueue injected style depends for every policy
		foreach( ICE_Policy::all() as $policy ) {
			// loop through all registered components
			foreach ( $policy->registry()->get_all() as $component ) {
				// call style dep enqueuer
				$component->style()->enqueue_deps();
			}
		}

		// are we at the admin dashboard?
		if ( !is_admin() ) {
			// no, enq active theme stylesheet
			wp_enqueue_style( 'icenq-style' );
		}
	}

	/**
	 * Handle enqueing styles on configured actions
	 *
	 * @internal
	 */
	public function handle_style_actions()
	{
		// action is current filter
		$action = current_filter();

		// loop through styles and check if action is set
		foreach( $this->styles as $handle => $config_map ) {
			// action in this style's action stack?
			if ( $config_map->item_at(self::TRIGGER_ACTS)->contains($action) ) {
				// yes, enqueue it!
				wp_enqueue_style( $handle );
			}
		}
	}

	/**
	 * Handle enqueing styles when specific conditions are met
	 *
	 * @internal
	 */
	public function handle_style_conditions()
	{
		// loop through styles and check if conditions are set
		foreach( $this->styles as $handle => $config_map ) {
			// and conditions in stack?
			if ( count( $config_map->item_at(self::TRIGGER_CONDS) ) ) {
				// check if ANY of the conditions eval to true
				foreach( $config_map->item_at(self::TRIGGER_CONDS) as $callback ) {
					// try to exec the callback
					if ( function_exists( $callback ) && true === call_user_func( $callback ) ) {
						// callback exists and evaled to true, enqueue it
						wp_enqueue_style( $handle );
						// done with this inner (conditions) loop
						break;
					}
				}
			}
		}
	}

	/**
	 * Handle enqueing internal scripts
	 *
	 * @internal
	 */
	public function handle_script_internal()
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
	 * Handle enqueing scripts on configured actions
	 *
	 * @internal
	 */
	public function handle_script_actions()
	{
		// action is current filter
		$action = current_filter();

		// loop through scripts and check if action is set
		foreach( $this->scripts as $handle => $config_map ) {
			// action in this script's action stack?
			if ( $config_map->item_at(self::TRIGGER_ACTS)->contains($action) ) {
				// yes, enqueue it!
				wp_enqueue_script( $handle );
			}
		}
	}

	/**
	 * Handle enqueing scripts when specific conditions are met
	 *
	 * @internal
	 */
	public function handle_script_conditions()
	{
		// loop through scripts and check if conditions are set
		foreach( $this->scripts as $handle => $config_map ) {
			// any conditions in stack?
			if ( count( $config_map->item_at(self::TRIGGER_CONDS) ) ) {
				// check if ANY of the conditions eval to true
				foreach( $config_map->item_at(self::TRIGGER_CONDS) as $callback ) {
					// try to exec the callback
					if ( function_exists( $callback ) && true === call_user_func( $callback ) ) {
						// callback exists and evaled to true, enqueue it
						wp_enqueue_script( $handle );
						// done with this inner (conditions) loop
						break;
					}
				}
			}
		}
	}

	/**
	 * Handle setting of the document domain
	 *
	 * @internal
	 */
	public function handle_script_domain()
	{
		// render it ?>
		<script type="text/javascript">
		//<![CDATA[
			document.domain = '<?php print $this->script_domain ?>';
		//]]>
		</script><?php
		echo PHP_EOL;
	}

	/**
	 * Add a style to a theme
	 *
	 * @param string $theme
	 * @param string $handle
	 * @param string $path
	 * @param array $deps
	 */
	public function style( $theme, $handle, $path, $deps = null )
	{
		// inject into style map
		$trigger = $this->define_one( $this->styles, $theme, $handle, $path );

		// add deps if applicable
		if ( is_array( $deps ) && count( $deps ) ) {
			foreach ( $deps as $dep ) {
				if ( $this->styles->contains( $dep ) ) {
					$trigger->item_at(self::TRIGGER_DEPS)->push( $dep );
				}
			}
		}
	}

	/**
	 * Add dep to an existing style
	 *
	 * This only applies to styles handled by this special enqueuer
	 *
	 * @param string $handle
	 * @param string $dep
	 */
	public function style_dep( $handle, $dep )
	{
		if ( $this->styles->contains( $dep ) ) {
			$this->styles->item_at($handle)->item_at(self::TRIGGER_DEPS)->push($dep);
		}
	}

}
