<?php
/**
 * PIE API: schemes scheme styles and scripts enqueuer class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage schemes
 * @since 1.0
 */

/**
 * Make enqueuing scheme styles and scripts easy
 *
 * @package PIE
 * @subpackage schemes
 */
class Pie_Easy_Scheme_Enqueue
{
	/**
	 * String on which to split "string packed" values
	 */
	const ITEM_DELIM = ',';
	/**
	 * String on which to split "string packed" parameters
	 */
	const PARAM_DELIM = ':';
	/**#@+
	 *  Trigger map key
	 */
	const TRIGGER_PATH = 'path';
	const TRIGGER_DEPS = 'deps';
	const TRIGGER_ALWAYS = 'always';
	const TRIGGER_ACTS = 'actions';
	const TRIGGER_CONDS = 'conditions';
	const TRIGGER_PARAMS = 'params';
	/**#@-*/
	/**
	 * Action on which to enqueue styles
	 */
	const ACTION_HANDLER_STYLES = 'pie_easy_enqueue_styles';
	/**
	 * Action on which to enqueue scripts
	 */
	const ACTION_HANDLER_SCRIPTS = 'pie_easy_enqueue_scripts';

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
		$this->script_domain = $this->scheme->get_directive( Pie_Easy_Scheme::DIRECTIVE_SCRIPT_DOMAIN );

		// hook up script domain handler
		if ( $this->script_domain instanceof Pie_Easy_Scheme_Directive ) {
			add_action( self::ACTION_HANDLER_SCRIPTS, array( $this, 'handle_script_domain' ) );
		}

		// init styles maps
		$this->styles = new Pie_Easy_Map();
		$this->styles_ignore = new Pie_Easy_Stack();

		// get style defs
		$style_defs =
			$this->scheme->get_directive_map(
				Pie_Easy_Scheme::DIRECTIVE_STYLE_DEFS
			);

		// init ui env
		$this->setup_ui( $style_defs );

		// define styles
		if ( $this->define( $this->styles, $style_defs ) ) {

			// hook up styles always handler
			add_action( self::ACTION_HANDLER_STYLES, array( $this, 'handle_style_always' ) );

			// init style depends
			$this->depends( $this->styles, Pie_Easy_Scheme::DIRECTIVE_STYLE_DEPS );

			// init style triggers
			$this->triggers( $this->styles, Pie_Easy_Scheme::DIRECTIVE_STYLE_ACTS, self::TRIGGER_ACTS );
			$this->triggers( $this->styles, Pie_Easy_Scheme::DIRECTIVE_STYLE_CONDS, self::TRIGGER_CONDS );

			// reg em
			$this->register_styles();
		}

		// init scripts map
		$this->scripts = new Pie_Easy_Map();

		// get script defs
		$script_defs = $this->scheme->get_directive_map( Pie_Easy_Scheme::DIRECTIVE_SCRIPT_DEFS );

		// define scripts
		if ( $this->define( $this->scripts, $script_defs ) ) {

			// hook up scripts always handler
			add_action( self::ACTION_HANDLER_SCRIPTS, array( $this, 'handle_script_always' ) );

			// init script depends
			$this->depends( $this->scripts, Pie_Easy_Scheme::DIRECTIVE_SCRIPT_DEPS );

			// init script triggers
			$this->triggers( $this->scripts, Pie_Easy_Scheme::DIRECTIVE_SCRIPT_ACTS, self::TRIGGER_ACTS );
			$this->triggers( $this->scripts, Pie_Easy_Scheme::DIRECTIVE_SCRIPT_CONDS, self::TRIGGER_CONDS );

			// reg em
			$this->register_scripts();
		}
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
		return sprintf( '%s-%s', $theme, trim( $handle ) );
	}

	/**
	 * Set UI environment
	 *
	 * @param Pie_Easy_Map $style_defs
	 */
	private function setup_ui( Pie_Easy_Map $style_defs )
	{
		// custom JUI set?
		$jui_active_theme = $this->scheme->get_directive( Pie_Easy_Scheme::DIRECTIVE_JUI_THEME );

		// try to make a handle
		if ( $jui_active_theme instanceof Pie_Easy_Scheme_Directive ) {
			$jui_active_handle = $this->make_handle( $jui_active_theme->theme, $jui_active_theme->value );
		} else {
			return false;
		}

		// get the entire JUI theme map
		$jui_theme_map = $this->scheme->get_directive_map( Pie_Easy_Scheme::DIRECTIVE_JUI_THEME );

		// loop through all JUI theme maps
		foreach ( $jui_theme_map as $jui_theme ) {
			// jui handle for this iteration
			$jui_handle = $this->make_handle($jui_theme->theme, $jui_theme->value);
			// is this the active jui handle?
			if ( $jui_handle == $jui_active_handle ) {
				// try to get jui theme settings theme styles
				if ( $style_defs->contains( $jui_theme->theme ) ) {
					// ok, we have the styles
					$theme_styles = $style_defs->item_at($jui_theme->theme)->value;
					// do the theme styles contain the theme handle
					if ( $theme_styles->contains( $jui_theme->value ) ) {
						// yes! register it
						wp_register_style(
							$jui_handle,
							$this->enqueue_path(
								$jui_theme->theme,
								$theme_styles->item_at($jui_theme->value)
							),
							(is_admin()) ? array('colors') : array()
						);
						// set it as the ui style handle in the enqueuer
						Pie_Easy_Enqueue::instance()->ui_style_handle( $jui_handle );
						// skip to next loop to avoid the ignore list!
						continue;
					}
				}
			}

			// jui handle was not used, so add it to the ignore stack
			$this->styles_ignore->push( $jui_handle );
		}
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

					// new map for this trigger
					$trigger = new Pie_Easy_Map();

					// add path value
					$trigger->add( self::TRIGGER_PATH, $this->enqueue_path($theme, $path) );

					// init deps stack
					$trigger->add( self::TRIGGER_DEPS, new Pie_Easy_Stack() );

					// init empty always toggle
					$trigger->add( self::TRIGGER_ALWAYS, true );

					// init empty actions stack
					$trigger->add( self::TRIGGER_ACTS, new Pie_Easy_Stack() );

					// init empty conditions stack
					$trigger->add( self::TRIGGER_CONDS, new Pie_Easy_Stack() );

					// init empty params stack
					$trigger->add( self::TRIGGER_PARAMS, new Pie_Easy_Stack() );

					// add trigger to main map
					$map->add( $this->make_handle( $theme, $handle ), $trigger );
				}
			}
		}

		return true;
	}

	/**
	 * Set dependancies for specified directives.
	 *
	 * This is for scheme directives that define a handle with a
	 * value being a delimeted string of dependant style or script handles
	 *
	 * @param Pie_Easy_Map $map
	 * @param string $directive_name
	 * @return boolean
	 */
	private function depends( Pie_Easy_Map $map, $directive_name )
	{
		// check if at least one theme defined this dep
		if ( $this->scheme->has_directive( $directive_name ) ) {

			// get dep directives for all themes
			$directive_map = $this->scheme->get_directive_map( $directive_name );

			// loop through and update triggers map with deps
			foreach ( $directive_map as $theme => $directive ) {

				// is directive value a map?
				if ( $directive->value instanceof Pie_Easy_Map ) {

					// yes, add action to each trigger's dependancy stack
					foreach( $directive->value as $handle => $dep_handles ) {

						// get theme handle
						$theme_handle = $this->make_handle( $theme, $handle );

						// split dep handles at delimeter
						$dep_handles = explode( self::ITEM_DELIM, $dep_handles );

						// loop through each handle
						foreach ( $dep_handles as $dep_handle ) {

							// get dep_theme handle
							$dep_theme_handle = $this->make_handle( $theme, $dep_handle );

							// does dep theme handle exist?
							if ( $map->item_at( $dep_theme_handle ) ) {
								// yep, use it
								$dep_handle = $dep_theme_handle;
							}

							// push onto trigger's dep stack
							$map->item_at($theme_handle)->item_at(self::TRIGGER_DEPS)->push($dep_handle);
						}
					}
				}
			}
			return true;
		}
		return false;
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
	 * @return boolean
	 */
	private function triggers( Pie_Easy_Map $map, $directive_name, $trigger_type )
	{
		// check if at least one theme defined this trigger
		if ( $this->scheme->has_directive( $directive_name ) ) {

			// get trigger directives for all themes
			$directive_map = $this->scheme->get_directive_map( $directive_name );

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

								// remove trigger's always toggle
								$map->item_at($handle)->add(self::TRIGGER_ALWAYS, false);

								// push onto trigger's trigger stack
								$map->item_at($handle)->item_at($trigger_type)->push($action);

								// push params onto trigger's params stack
								$map->item_at($handle)->item_at(self::TRIGGER_PARAMS)->copy_from($action_params);

								// is this an actions trigger type?
								if ( $trigger_type == self::TRIGGER_ACTS ) {
									// yes, hook it to action handler
									add_action( $action, array( $this, 'handle_' . $directive_name ) );
								}

							}
						}
					}
				}
			}

			// is this a conditions trigger type?
			if ( $trigger_type == self::TRIGGER_CONDS ) {
				// yes, hook up conditions handler
				add_action(
					$this->handler_action( $directive_name ),
					array( $this, 'handle_' . $directive_name ),
					11
				);
			}

			return true;
		}

		return false;
	}

	/**
	 * Determine handler action based on directive
	 *
	 * @param string $directive_name
	 * @return string
	 */
	private function handler_action( $directive_name )
	{
		switch ( $directive_name ) {
			case Pie_Easy_Scheme::DIRECTIVE_STYLE_DEFS:
			case Pie_Easy_Scheme::DIRECTIVE_STYLE_ACTS:
			case Pie_Easy_Scheme::DIRECTIVE_STYLE_CONDS:
				return self::ACTION_HANDLER_STYLES;
			case Pie_Easy_Scheme::DIRECTIVE_SCRIPT_DEFS:
			case Pie_Easy_Scheme::DIRECTIVE_SCRIPT_ACTS:
			case Pie_Easy_Scheme::DIRECTIVE_SCRIPT_CONDS:
				return self::ACTION_HANDLER_SCRIPTS;
			default:
				throw new Exception( 'That directive does not have a handler action' );
		}
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
		if ( preg_match( '/^http:\/\//i', $path ) ) {
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

		// reg it
		wp_register_style(
			$handle,
			$config_map->item_at(self::TRIGGER_PATH),
			$config_map->item_at(self::TRIGGER_DEPS)->to_array()
		);
	}

	/**
	 * Register all styles
	 */
	private function register_styles()
	{
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
		// do it
		wp_register_script(
			$handle,
			$config_map->item_at(self::TRIGGER_PATH),
			$config_map->item_at(self::TRIGGER_DEPS)->to_array()
		);
	}

	/**
	 * Register all scripts
	 */
	private function register_scripts()
	{
		foreach ( $this->scripts as $handle => $config_map ) {
			$this->register_script( $handle, $config_map );
		}
	}

	/**
	 * Handle enqueing styles that should always be loaded
	 *
	 * @ignore
	 */
	public function handle_style_always()
	{
		// loop through styles and check if always is toggled on
		foreach( $this->styles as $handle => $config_map ) {
			// always load?
			if ( $config_map->item_at(self::TRIGGER_ALWAYS) == true ) {
				// yes, enqueue it!
				wp_enqueue_style( $handle );
			}
		}
	}

	/**
	 * Handle enqueing styles on configured actions
	 *
	 * @ignore
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
	 * @ignore
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
	 * Handle enqueing scripts that should always be loaded
	 *
	 * @ignore
	 */
	public function handle_script_always()
	{
		// loop through scripts and check if always is toggled on
		foreach( $this->scripts as $handle => $config_map ) {
			// always load?
			if ( $config_map->item_at(self::TRIGGER_ALWAYS) == true ) {
				// yes, enqueue it!
				wp_enqueue_script( $handle );
			}
		}
	}

	/**
	 * Handle enqueing scripts on configured actions
	 *
	 * @ignore
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
	 * @ignore
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
	 * @ignore
	 */
	public function handle_script_domain()
	{
		// render it
		?><script type="text/javascript">document.domain = '<?php print $this->script_domain->value ?>';</script><?php
		echo PHP_EOL;
	}

}

?>
