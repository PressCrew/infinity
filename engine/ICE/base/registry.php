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
class ICE_Registry extends ICE_Componentable implements ICE_Visitable
{
	/**
	 * Component type to use when none configured.
	 */
	const DEFAULT_TYPE = 'default';

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
	 * Raw settings array.
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 */
	public function accept( ICE_Visitor $visitor )
	{
		foreach ( $this->components as $component ) {
			$component->accept( $visitor );
		}
	}

	/**
	 * Init all components.
	 */
	public function init()
	{
		// loop all components
		foreach ( $this->get_all() as $component ) {
			// call init on each one
			$component->init();
		}
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
	 * Substitute values from sibling settings.
	 *
	 * @param string $key
	 * @param array $settings
	 * @return string
	 */
	private function substitute( $key, &$settings )
	{
		// loop all settings
		foreach( $settings as $skey => &$sval ) {
			// only scalar values can be injected into strings safely
			if ( true === is_scalar( $sval ) ) {
				// wrap key with delim
				$search[] = '%' . $skey . '%';
			}
		}
		
		// replace it
		return str_replace( $search, $settings, $settings[ $key ] );
	}

	/**
	 * Apply substitution to all configured settings.
	 */
	private function substitute_all()
	{
		// subject keys to replace value of
		$keys = array( 'parent', 'title', 'description', 'linked_image' );

		// loop all registered settings
		foreach ( $this->settings as &$settings ) {
			// loop all keys
			foreach( $keys as $key ) {
				// is key set in settings?
				if ( true === isset( $settings[ $key ] ) ) {
					// yep, attempt to substitute
					$settings[ $key ] = $this->substitute( $key, $settings );
				}
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
	 * @return array
	 */
	final public function get_all()
	{
		// return all components
		return $this->components;
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
					// append to return array
					$components[] = $component;
				}
			}
		}

		return $components;
	}

	/**
	 * Return the value of a setting for the given item name.
	 *
	 * @param string $name
	 * @param string $setting
	 * @return mixed
	 */
	public function get_setting( $name, $setting )
	{
		// is setting set?
		if ( isset( $this->settings[ $name ][ $setting ] ) ) {
			// yep, return it
			return $this->settings[ $name ][ $setting ];
		}

		// return null by default
		return null;
	}

	/**
	 * Return the entire settings stack for the given item name.
	 *
	 * @param string $name
	 * @return array
	 */
	public function get_settings( $name )
	{
		// any settings set?
		if ( isset( $this->settings[ $name ] ) ) {
			// yep, return it
			return $this->settings[ $name ];
		}

		// return empty array by default
		return array();
	}

	/**
	 * Load a file in context so it can make calls to register() in scope.
	 *
	 * @param string $filename
	 */
	public function register_file( $filename )
	{
		require_once $filename;
	}

	/**
	 * Register a component's settings.
	 *
	 * @param string $name
	 * @param array $settings
	 * @return boolean
	 */
	public function register( $name, $settings )
	{
		// COMPAT check for ignore setting
		if ( isset( $settings[ 'ignore' ] ) ) {
			// do not create it
			if ( WP_DEBUG ) {
				throw new OutOfBoundsException( 'The "ignore" setting is no longer valid.' );
			} else {
				continue;
			}
		}

		// does component have config already?
		if ( isset( $this->settings[ $name ] ) ) {
			// yep, merge over existing settings
			$this->settings[ $name ] =
				array_merge(
					$this->settings[ $name ],
					$settings
				);
		} else {
			// nope, just assign it
			$this->settings[ $name ] = $settings;
		}

		// config loaded
		return true;
	}

	/**
	 * Create and return a component.
	 *
	 * @param string $comp_name
	 * @param string $type
	 * @return ICE_Component|boolean
	 */
	protected function create_component( $comp_name, $type )
	{
		// set default type if necessary
		if ( empty( $type ) ) {
			$type = self::DEFAULT_TYPE;
		}

		// try to create component
		try {
			// call extensions create method
			return $this->_policy->extensions()->create( $type, $comp_name );
		// catch enviro exception
		} catch ( ICE_Environment_Exception $e ) {
			// create failed
			return false;
		}
	}

	/**
	 * Create all configured components using raw settings.
	 *
	 * @return boolean
	 */
	private function create_components()
	{
		// loop all component configurations.
		foreach( $this->settings as $comp_name => &$settings ) {

			// check if already registered
			if ( false === $this->has( $comp_name ) ) {

				// is a feature set?
				if ( isset( $settings['feature'] ) ) {
					// try to grab defaults
					$defaults_array = $this->_policy->features()->registry()->get_suboption_defaults( $settings['feature'] );
					// get any defaults?
					if ( !empty( $defaults_array ) ) {
						// merge config *ON TOP OF* defaults
						$settings = array_merge( $defaults_array, $settings );
					}
				}

				// create new component
				$component = $this->create_component( $comp_name, $settings['type'] );

				// get a component?
				if ( $component instanceof ICE_Component ) {
					// add to components stack
					$this->components[ $comp_name ] = $component;
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

		// loop all components
		foreach ( $this->components as $component ) {
			// finalize it
			$component->finalize();
		}

		// initialize the response on the "init" action
		add_action( 'ice_init', array( $this, 'init' ) );
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
