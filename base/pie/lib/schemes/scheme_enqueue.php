<?php
/**
 * PIE API: schemes scheme styles and scripts enqueuer class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage schemes
 * @since 1.0
 */

/**
 * Make enqueuing scheme styles and scripts easy
 *
 * @todo this class needs to be split into three classes (pyramid)
 * @package PIE
 * @subpackage schemes
 */
class Pie_Easy_Scheme_Enqueue extends Pie_Easy_Base
{
	/**
	 * String on which to split "string packed" values
	 */
	const ITEM_DELIM = ',';

	/**
	 * String on which to split "string packed" parameters
	 */
	const PARAM_DELIM = ':';

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
	 *  Trigger map parameters key
	 */
	const TRIGGER_PARAMS = 'params';

	/**
	 * @var Pie_Easy_Scheme
	 */
	private $scheme;

	/**
	 * @var Pie_Easy_Map
	 */
	private $styles;

	/**
	 * @var Pie_Easy_Stack
	 */
	private $styles_ignore;

	/**
	 * @var Pie_Easy_Map
	 */
	private $scripts;

	/**
	 * The domain to set document.domain to
	 *
	 * @var string
	 */
	private $script_domain;

	/**
	 * Initialize the enqueuer by passing a valid PIE scheme object
	 *
	 * @param Pie_Easy_Scheme $scheme
	 */
	public function __construct( Pie_Easy_Scheme $scheme )
	{
		// set scheme
		$this->scheme = $scheme;

		// define script domain
		$this->script_domain = $this->scheme->directives()->get( Pie_Easy_Scheme::DIRECTIVE_SCRIPT_DOMAIN );

		// hook up script domain handler
		if ( $this->script_domain instanceof Pie_Easy_Init_Directive ) {
			add_action( 'wp_print_scripts', array( $this, 'handle_script_domain' ) );
			add_action( 'admin_print_scripts', array( $this, 'handle_script_domain' ) );
		}

		// init styles maps
		$this->styles = new Pie_Easy_Map();
		$this->styles_ignore = new Pie_Easy_Stack();

		// get style defs
		$style_defs =
			$this->scheme->directives()->get_map(
				Pie_Easy_Scheme::DIRECTIVE_STYLE_DEFS
			);

		// create new map so we can write to it
		$style_defs_w = new Pie_Easy_Map( $style_defs );

		// init internal styles
		$this->setup_internal_styles( $style_defs_w );

		// define styles
		$this->define_styles( $style_defs_w );

		// register styles
		add_action( 'pie_easy_register_styles', array( $this, 'register_styles' ) );
		
		// init scripts map
		$this->scripts = new Pie_Easy_Map();

		// get script defs
		$script_defs =
			$this->scheme->directives()->get_map(
				Pie_Easy_Scheme::DIRECTIVE_SCRIPT_DEFS
			);

		// create new map so we can write to it
		$script_defs_w = new Pie_Easy_Map( $script_defs );

		// init internal scripts
		$this->setup_internal_scripts( $script_defs_w );
		
		// define scripts
		$this->define_scripts( $script_defs_w );

		// register scripts
		add_action( 'pie_easy_register_scripts', array( $this, 'register_scripts' ) );
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
		if ( strpos( $handle, ':' ) ) {
			return $handle;
		}

		return sprintf( '%s:%s', $theme, trim( $handle ) );
	}

	/**
	 * Set UI environment
	 */
	private function setup_ui()
	{
		// get custom ui stylesheet directive
		$ui_stylesheet = $this->scheme->directives()->get( Pie_Easy_Scheme::DIRECTIVE_UI_STYLESHEET );
		
		// custom ui stylesheet set?
		if ( $ui_stylesheet instanceof Pie_Easy_Init_Directive ) {
			Pie_Easy_Enqueue::instance()->ui_stylesheet(
				Pie_Easy_Files::theme_file_url( $ui_stylesheet->theme, $ui_stylesheet->value )
			);
		}
	}

	/**
	 * Inject internal stylesheets into style directives
	 *
	 * @internal
	 * @param Pie_Easy_Map $style_defs
	 */
	private function setup_internal_styles( Pie_Easy_Map $style_defs )
	{
		// map of styles and depends
		$styles = new Pie_Easy_Map();

		// get export
		$export_styles = $this->scheme->exports()->get( 'styles' );

		// build up array of exports
		if ( is_admin() ) {
			$exports['dynamic-admin'] = $export_styles->child( 'admin' );
		} else {
			$exports['dynamic'] = $export_styles;
		}
		
		// always register global
		$exports['dynamic-global'] = $export_styles->child( 'global' );
		
		// add internal style for every export
		foreach( $exports as $handle => $export ) {
			// register if it exists and has content
			if (  Pie_Easy_Files::cache( $export->path )->is_readable() && filesize( $export->path ) > 0 ) {
				$styles->add( $handle, $export->url );
			}
		}

		// add the theme style.css LAST
		$styles->add( 'style', get_bloginfo( 'stylesheet_url' ) );

		// add directive
		$directive = new Pie_Easy_Init_Directive( '@', 'style', $styles );
		$style_defs->add( '@', $directive, true );

		// hook up styles internal handler
		add_action( 'pie_easy_enqueue_styles', array( $this, 'handle_style_internal' ), 1 );

		// init ui env
		$this->setup_ui();
	}

	/**
	 * Inject internal scripts into script directives
	 *
	 * @internal
	 * @param Pie_Easy_Map $script_defs
	 */
	private function setup_internal_scripts( Pie_Easy_Map $script_defs )
	{
		// map of scripts and script depends
		$script = new Pie_Easy_Map();

		// get export
		$export_script = $this->scheme->exports()->get( 'scripts' );

		// build up array of exports
		if ( is_admin() ) {
			$exports['dynamic-admin'] = $export_script->child( 'admin' );
		} else {
			$exports['dynamic'] = $export_script;
		}

		// always register global
		$exports['dynamic-global'] = $export_script->child( 'global' );

		// add internal script for every export
		foreach( $exports as $handle => $export ) {
			// register if it exists and has content
			if ( Pie_Easy_Files::cache($export->path)->is_readable() && filesize( $export->path ) > 0 ) {
				$script->add( $handle, $export->url );
			}
		}

		// any scripts to add?
		if ( $script->count() ) {
			// add directive
			$directive = new Pie_Easy_Init_Directive( '@', 'script', $script );
			$script_defs->add( '@', $directive, true );
		}

		// hook up scripts internal handler
		add_action( 'pie_easy_enqueue_scripts', array( $this, 'handle_script_internal' ) );
	}

	/**
	 * Try to define triggers which have been set in the scheme's config
	 *
	 * @param Pie_Easy_Map $map
	 * @param Pie_Easy_Map $directive_map
	 * @return boolean
	 */
	private function define( Pie_Easy_Map $map, Pie_Easy_Map $directive_map )
	{
		// loop through and populate trigger map
		foreach ( $directive_map as $theme => $directive ) {

			// is directive value a map?
			if ( $directive->value instanceof Pie_Easy_Map ) {

				// yes, add each handle and URL path to map
				foreach( $directive->value as $handle => $path ) {

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
	 * @param Pie_Easy_Map $map
	 * @param string $theme
	 * @param string $handle
	 * @param string $path
	 * @return boolean
	 */
	private function define_one( Pie_Easy_Map $map, $theme, $handle, $path )
	{
		// new map for this trigger
		$trigger = new Pie_Easy_Map();

		// add path value
		$trigger->add( self::TRIGGER_PATH, $this->enqueue_path($theme, $path) );

		// init deps stack
		$trigger->add( self::TRIGGER_DEPS, new Pie_Easy_Stack() );

		// init empty actions stack
		$trigger->add( self::TRIGGER_ACTS, new Pie_Easy_Stack() );

		// init empty conditions stack
		$trigger->add( self::TRIGGER_CONDS, new Pie_Easy_Stack() );

		// init empty params stack
		$trigger->add( self::TRIGGER_PARAMS, new Pie_Easy_Stack() );

		// add trigger to main map
		$map->add( $this->make_handle( $theme, $handle ), $trigger );

		return $trigger;
	}

	/**
	 * @internal
	 * @param Pie_Easy_Map $style_defs
	 */
	private function define_styles( Pie_Easy_Map $style_defs )
	{
		return $this->define( $this->styles, $style_defs );
	}

	/**
	 * @param Pie_Easy_Map $script_defs
	 */
	private function define_scripts( Pie_Easy_Map $script_defs )
	{
		return $this->define( $this->scripts, $script_defs );
	}

	/**
	 * Set dependancies for specified directives.
	 *
	 * This is for scheme directives that define a handle with a
	 * value being a delimeted string of dependant style or script handles
	 *
	 * @param Pie_Easy_Map $map
	 * @param Pie_Easy_Map $directive_map
	 * @return boolean
	 */
	private function depends( Pie_Easy_Map $map, Pie_Easy_Map $directive_map )
	{
		// loop through and update triggers map with deps
		foreach ( $directive_map as $theme => $directive ) {

			// is directive value a map?
			if ( $directive->value instanceof Pie_Easy_Map ) {

				// yes, add action to each trigger's dependancy stack
				foreach( $directive->value as $handle => $dep_handles ) {

					// get theme handle
					$theme_handle = $this->make_handle( $theme, $handle );

					// get a string?
					if ( is_string( $dep_handles) ) {
						// split dep handles at delimeter
						$dep_handles = explode( self::ITEM_DELIM, $dep_handles );
					}

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
	 * Set triggers for specified directives.
	 *
	 * This is for scheme directives that define a trigger with a
	 * value being a delimeted string of style or script handles
	 *
	 * @param Pie_Easy_Map $map
	 * @param string $directive_name
	 * @param string $trigger_type
	 * @param string $trigger_action
	 * @return boolean
	 */
	private function triggers( Pie_Easy_Map $map, $directive_name, $trigger_type, $trigger_action = null )
	{
		// check if at least one theme defined this trigger
		if ( $this->scheme->directives()->has( $directive_name ) ) {

			// get trigger directives for all themes
			$directive_map = $this->scheme->directives()->get_map( $directive_name );

			// loop through and update triggers map
			foreach ( $directive_map as $theme => $directive ) {

				// is directive value a map?
				if ( $directive->value instanceof Pie_Easy_Map ) {

					// yes, add action to each trigger's trigger stack
					foreach( $directive->value as $action => $handles ) {

						// no action params by default
						$action_params = null;

						// check for params for action
						if ( stripos($action, self::PARAM_DELIM) ) {
							// split action at param delimeter
							$action_parts = explode( self::PARAM_DELIM, $action );
							// must have exactly two results
							if ( count( $action_parts ) == 2 ) {
								// action is first result
								$action = $action_parts[0];
								$action_params = explode( self::ITEM_DELIM, $action_parts[1] );
							} else {
								throw new Exception( 'Invalid parameter syntax' );
							}
						}

						// split handles at delimeter
						$handles = explode( self::ITEM_DELIM, $handles );

						// loop through each handle
						foreach ( $handles as $handle ) {

							// is this an override situation? (exact handle already in map)
							if ( !$map->item_at( $handle ) ) {
								// not an override, use theme handle
								$handle = $this->make_handle( $theme, $handle );
							}

							// does trigger handle exist?
							if ( $map->item_at( $handle ) ) {
								// push onto trigger's trigger stack
								$map->item_at($handle)->item_at($trigger_type)->push($action);
								// push params onto trigger's params stack
								$map->item_at($handle)->item_at(self::TRIGGER_PARAMS)->copy_from($action_params);
							}
						}
						
						// is this an actions trigger type?
						if ( $trigger_type == self::TRIGGER_ACTS ) {
							// yes, hook it to action handler
							add_action( $action, array( $this, 'handle_' . $directive_name ) );
						}
					}
				}
			}

			// is this a conditions trigger type?
			if ( $trigger_type == self::TRIGGER_CONDS && $trigger_action ) {
				// yes, hook up conditions handler
				add_action(
					$trigger_action,
					array( $this, 'handle_' . $directive_name ),
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
			return Pie_Easy_Files::theme_file_url( $theme, $path );
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
		// check if at least one theme defined this dep
		if ( $this->scheme->directives()->has( Pie_Easy_Scheme::DIRECTIVE_STYLE_DEPS ) ) {

			// get dep directives for all themes
			$style_depends =
				$this->scheme->directives()->get_map(
					Pie_Easy_Scheme::DIRECTIVE_STYLE_DEPS
				);

			// create new map so we can write to it
			$style_depends_w = new Pie_Easy_Map( $style_depends );

			// start with empty stacks
			$dep_stack = new Pie_Easy_Stack();
			$dep_global_stack = new Pie_Easy_Stack();
			$dep_admin_stack = new Pie_Easy_Stack();

			// add dynamic style depends for every policy
			foreach( Pie_Easy_Policy::all() as $policy ) {
				// loop through all registered components
				foreach ( $policy->registry()->get_all() as $component ) {
					// push deps onto stacks
					$component->style()->push_deps( $dep_stack );
					$component->style()->section('global')->push_deps( $dep_global_stack );
					$component->style()->section('admin')->push_deps( $dep_admin_stack );
				}
			}

			// add addtl dependancies
			$dep_map = new Pie_Easy_Map();
			$dep_map->add( '@:dynamic', $dep_stack->to_array() );
			$dep_map->add( '@:dynamic-global', $dep_global_stack->to_array() );
			$dep_map->add( '@:dynamic-admin', $dep_admin_stack->to_array() );
			$directive_deps = new Pie_Easy_Init_Directive( '@', 'style_depends', $dep_map );
			$style_depends_w->add( '@', $directive_deps, true );

			// init style depends
			$this->depends( $this->styles, $style_depends_w );
		}

		// init style action triggers
		$this->triggers(
			$this->styles,
			Pie_Easy_Scheme::DIRECTIVE_STYLE_ACTS,
			self::TRIGGER_ACTS
		);

		// init style condition triggers
		$this->triggers(
			$this->styles,
			Pie_Easy_Scheme::DIRECTIVE_STYLE_CONDS,
			self::TRIGGER_CONDS,
			'pie_easy_enqueue_styles'
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
			$deps
		);
	}

	/**
	 * Register all scripts
	 */
	final public function register_scripts()
	{
		// check if at least one theme defined this dep
		if ( $this->scheme->directives()->has( Pie_Easy_Scheme::DIRECTIVE_SCRIPT_DEPS ) ) {

			// get dep directives for all themes
			$script_depends =
				$this->scheme->directives()->get_map(
					Pie_Easy_Scheme::DIRECTIVE_SCRIPT_DEPS
				);

			// create new map so we can write to it
			$script_depends_w = new Pie_Easy_Map( $script_depends );

			// start with empty stacks
			$dep_stack = new Pie_Easy_Stack();
			$dep_global_stack = new Pie_Easy_Stack();
			$dep_admin_stack = new Pie_Easy_Stack();

			// add dynamic script depends for every policy
			foreach( Pie_Easy_Policy::all() as $policy ) {
				// loop through all registered components
				foreach ( $policy->registry()->get_all() as $component ) {
					// push deps onto stacks
					$component->script()->push_deps( $dep_stack );
					$component->script()->section('global')->push_deps( $dep_global_stack );
					$component->script()->section('admin')->push_deps( $dep_admin_stack );
				}
			}

			// add addtl dependancies
			$dep_map = new Pie_Easy_Map();
			$dep_map->add( '@:dynamic', $dep_stack->to_array() );
			$dep_map->add( '@:dynamic-global', $dep_global_stack->to_array() );
			$dep_map->add( '@:dynamic-admin', $dep_admin_stack->to_array() );
			$directive_deps = new Pie_Easy_Init_Directive( '@', 'script_depends', $dep_map );
			$script_depends_w->add( '@', $directive_deps, true );

			// init script depends
			$this->depends( $this->scripts, $script_depends_w );
		}

		// init script action triggers
		$this->triggers(
			$this->scripts,
			Pie_Easy_Scheme::DIRECTIVE_SCRIPT_ACTS,
			self::TRIGGER_ACTS
		);

		// init script condition triggers
		$this->triggers(
			$this->scripts,
			Pie_Easy_Scheme::DIRECTIVE_SCRIPT_CONDS,
			self::TRIGGER_CONDS,
			'pie_easy_enqueue_scripts'
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
		// always enqueue global styles
		if ( wp_style_is( '@:dynamic-global', 'registered' ) ) {
			wp_enqueue_style( '@:dynamic-global' );
		}

		// are we at the admin dashboard?
		if ( is_admin() ) {
			// yes, enqueue admin styles
			if ( wp_style_is( '@:dynamic-admin', 'registered' ) ) {
				wp_enqueue_style( '@:dynamic-admin' );
			}
		} else {
			// enq active theme stylesheet
			wp_enqueue_style( '@:style' );
			// enqueue public styles
			if ( wp_style_is( '@:dynamic', 'registered' ) ) {
				wp_enqueue_style( '@:dynamic' );
			}
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
					if ( function_exists( $callback ) && call_user_func_array($callback,$config_map->item_at(self::TRIGGER_PARAMS)->to_array()) == true ) {
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
		// always enqueue global scripts
		if ( wp_script_is( '@:dynamic-global', 'registered' ) ) {
			wp_enqueue_script( '@:dynamic-global' );
		}

		// are we at the admin dashboard?
		if ( is_admin() ) {
			// yes, enqueue admin scripts
			if ( wp_script_is( '@:dynamic-admin', 'registered' ) ) {
				wp_enqueue_script( '@:dynamic-admin' );
			}
		} else {
			// enqueue public scripts
			if ( wp_script_is( '@:dynamic', 'registered' ) ) {
				wp_enqueue_script( '@:dynamic' );
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
					if ( function_exists( $callback ) && call_user_func_array($callback,$config_map->item_at(self::TRIGGER_PARAMS)->to_array()) == true ) {
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
		// render it
		?><script type="text/javascript">document.domain = '<?php print $this->script_domain->value ?>';</script><?php
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

?>
