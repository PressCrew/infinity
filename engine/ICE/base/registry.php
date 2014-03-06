<?php
/**
 * ICE API: base registry
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage base
 * @since 1.0
 */

ICE_Loader::load(
	'base/componentable',
	'base/visitable',
	'utils/export'
);

/**
 * Make keeping track of concrete components
 *
 * @package ICE
 * @subpackage base
 */
abstract class ICE_Registry extends ICE_Componentable implements ICE_Visitable
{
	/**
	 * Sub option delimeter
	 */
	const SUB_OPTION_DELIM = '.';

	/**
	 * Sub option glue
	 */
	const SUB_OPTION_GLUE = '_';

	/**
	 * Registered components array
	 *
	 * @var array
	 */
	private $components = array();

	/**
	 * Enabled components array.
	 *
	 * @var array
	 */
	private $components_enabled = array();

	/**
	 * Raw settings array.
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 */
	public function accept( ICE_Visitor $visitor )
	{
		foreach ( $this->components_enabled as $component ) {
			$component->accept( $visitor );
		}
	}

	/**
	 * Init ajax requirements
	 */
	public function init_ajax()
	{
		// init ajax for each registered component
		foreach ( $this->get_all() as $component ) {
			if ( $component->supported() ) {
				$component->init_ajax();
			}
		}
	}

	/**
	 * Init screen dependencies for all applicable components to be rendered
	 */
	public function init_screen()
	{
		add_action( 'ice_init_styles', array($this, 'init_styles') );
		add_action( 'ice_init_scripts', array($this, 'init_scripts') );
		
		// init screen for each registered component
		foreach ( $this->get_all() as $component ) {
			if ( $component->supported() ) {
				$component->init_screen();
			}
		}
	}

	/**
	 * Enqueue required styles
	 */
	public function init_styles()
	{
		foreach ( $this->get_all() as $component ) {
			if ( $component->supported() ) {
				( true === ICE_IS_ADMIN )
					? $component->init_admin_styles()
					: $component->init_styles();
			}
		}
	}

	/**
	 * Enqueue required scripts
	 */
	public function init_scripts()
	{
		// init scripts for each registered component
		foreach ( $this->get_all() as $component ) {
			if ( $component->supported() ) {
				( true === ICE_IS_ADMIN )
					? $component->init_admin_scripts()
					: $component->init_scripts();
			}
		}
	}

	/**
	 * Register a component
	 *
	 * @param ICE_Component $component
	 * @return boolean
	 */
	final protected function register( ICE_Component $component )
	{
		// get the name property
		$name = $component->get_property( 'name' );

		// has the component already been registered?
		if ( false === isset( $this->components[ $name ] ) ) {
			// register it
			$this->components[ $name ] = $component;
			// get the ignore property
			$ignore = $component->get_property( 'ignore' );
			// ignored?
			if ( true !== $ignore ) {
				// nope, add to enabled components
				$this->components_enabled[ $name ] = $component;
			}
		}

		return true;
	}

	/**
	 * Normalize a component name which may have "dots" in it
	 *
	 * @param string $name
	 * @return string
	 */
	final protected function normalize_name( $name )
	{
		if ( strpos( $name, self::SUB_OPTION_DELIM ) !== false ) {
			return str_replace( self::SUB_OPTION_DELIM, self::SUB_OPTION_GLUE, $name );
		}

		return $name;
	}

	/**
	 * Substitute value from another init data key into this key's value
	 *
	 * @param string $value
	 * @param array $settings
	 * @return string
	 */
	private function substitute( $value, $settings )
	{
		// does string have enough % chars?
		if ( is_string( $value ) && substr_count( $value, '%' ) >= 2 ) {
			// matches container
			$matches = null;
			// find tokens
			if ( preg_match_all( '/%(\w+)%/', $value, $matches, PREG_SET_ORDER ) ) {
				// loop all matches
				foreach ( $matches as $match ) {
					// break out strings into vars
					$str_search = $match[0];
					$str_name = $match[1];
					// is data already set?
					if ( isset( $settings[ $str_name ] ) ) {
						// return new string
						$value = str_replace(
							$str_search,
							$settings[ $str_name ],
							$value
						);
					} else {
						throw new Exception( sprintf(
							'Cannot perform substitution for data value "%s" using value of ' .
							'data name "%s" because it has not been set', $value, $str_name ) );
					}
				}
			}
		}

		return $value;
	}

	/**
	 * Apply substitution to all configured settings.
	 */
	private function substitute_all()
	{
		foreach ( $this->settings as $comp_name => $settings ) {
			foreach ( $settings as $setting => $value ) {
				$this->settings[ $comp_name ][ $setting ] = $this->substitute( $value, $settings );
			}
		}
	}

	/**
	 * Returns true if a component has been registered
	 *
	 * @param string $name
	 * @return boolean
	 */
	final public function has( $name )
	{
		// normalize name
		$name = $this->normalize_name( $name );

		// call contains method of map class
		return isset( $this->components[ $name ] );
	}

	/**
	 * Return a registered component object by name
	 *
	 * @param string $name
	 * @return ICE_Component
	 */
	final public function get( $name )
	{
		// normalize name
		$name = $this->normalize_name( $name );

		// check registry
		if ( isset( $this->components[ $name ] ) ) {
			// from top of stack
			return $this->components[ $name ];
		}

		// didn't find it
		throw new Exception( sprintf( 'Unable to get component "%s": not registered', $name ) );
	}

	/**
	 * Return all registered components as an array
	 *
	 * @param boolean $include_ignored Set to true to also return components which have ignore set to true
	 * @return array
	 */
	final public function get_all( $include_ignored = false )
	{
		// do they want ignored as well?
		if ( true === $include_ignored ) {
			// yep, return all components
			return $this->components;
		} else {
			// return only enabled components
			return $this->components_enabled;
		}
	}

	/**
	 * Return all registered child components of a component
	 *
	 * This adheres to parent settings in the component config file
	 *
	 * @param ICE_Component $component The component object whose children you want to get
	 * @return array
	 */
	public function get_children( ICE_Component $component )
	{
		// the components that will be returned
		$components = array();

		// find all registered component where parent is the target component
		foreach ( $this->get_all() as $component_i ) {
			if ( $component->is_parent_of( $component_i ) ) {
				$components[] = $component_i;
			}
		}

		return $components;
	}

	/**
	 * Get components that should behave as a root component
	 *
	 * This method mostly exists as a helper to use when rendering menus
	 *
	 * @param array $component_names An array of component names to include, defaults to all
	 * @return array
	 */
	public function get_roots( $component_names = array() )
	{
		// components to be returned
		$components = array();

		// loop through all registered components
		foreach ( $this->get_all() as $component ) {
			// components must NOT have a parent set
			if ( null === $component->get_property( 'parent' ) ) {
				// filter on component names
				if ( empty( $component_names ) || in_array( $component->get_property( 'name' ), $component_names, true ) ) {
					// component must be supported
					if ( $component->supported() ) {
						// append to return array
						$components[] = $component;
					}
				}
			}
		}

		return $components;
	}

	/**
	 * Load a config file.
	 *
	 * @param string $filename Absolute path to the component config file to parse.
	 * @return boolean
	 */
	final public function load_config_file( $filename )
	{
		// get component configurations from file
		$settings = require_once( $filename );

		// get an array?
		if ( is_array( $settings ) ) {
			// loop all component configs
			foreach( $settings as $comp_name => $settings ) {
				// load component config
				$this->load_config_array( $comp_name, $settings );
			}
			// file loaded
			return true;
		}

		// file NOT loaded
		return false;
	}

	/**
	 * Load one component config into registry from an array.
	 *
	 * @param string $comp_name
	 * @param array $settings
	 * @return boolean
	 */
	protected function load_config_array( $comp_name, $settings )
	{
		// does component have config already?
		if ( isset( $this->settings[ $comp_name ] ) ) {
			// yep, merge over existing settings
			$this->settings[ $comp_name ] =
				array_merge(
					$this->settings[ $comp_name ],
					$settings
				);
		} else {
			// nope, just assign it
			$this->settings[ $comp_name ] = $settings;
		}

		// config loaded
		return true;
	}

	/**
	 * Create and return a component.
	 *
	 * @param string $comp_name
	 * @param array $settings
	 * @return ICE_Component|boolean
	 */
	private function create_component( $comp_name, $settings )
	{
		// use factory to create new component
		return
			$this->policy()->factory()->create(
				$comp_name,
				$settings
			);
	}

	/**
	 * Create all configured components using raw settings.
	 *
	 * @return boolean
	 */
	private function create_components()
	{
		// loop all component configurations.
		foreach( $this->settings as $comp_name => $settings ) {

			// check if already registered
			if ( false === $this->has( $comp_name ) ) {

				// create new component
				$component = $this->create_component( $comp_name, $settings );

				// get a component?
				if ( $component instanceof ICE_Component ) {

					// register component
					$this->register( $component );
				}

			} else {
				// component already registered, not good!
				throw new OverflowException(
					sprintf( 'The "%s" component cannot be created twice.', $comp_name )
				);
			}
		}

		// all done
		return true;
	}

	/**
	 * Perform final setup steps
	 */
	final public function finalize()
	{
		// perform substitutions
		$this->substitute_all();

		// call component creator
		$this->create_components();

		foreach ( $this->components_enabled as $component ) {
			$component->finalize();
		}
	}

	/**
	 * Format a suboption using the glue string
	 *
	 * @param string $comp_name
	 * @param string $option_name
	 * @return string
	 */
	final static public function format_suboption( $comp_name, $option_name )
	{
		// join the two strings with the glue character
		return $comp_name . self::SUB_OPTION_GLUE . $option_name;
	}
}
