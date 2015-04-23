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
	 * Component group to use when none configured.
	 */
	const DEFAULT_GROUP = 'default';

	/**
	 * Sub item delimeter.
	 */
	const SUB_ITEM_DELIM = '.';

	/**
	 * Array of valid component groups.
	 *
	 * @var array
	 */
	static private $groups =
		array(
			'default' => true
		);

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
	 * Theme support callback.
	 *
	 * @param bool $supported Defaults to true.
	 * @param array $args Additional args passed to current_theme_supports().
	 * @param array $features Array passed to add_theme_support().
	 * @return bool
	 */
	static public function supports( $supported, $args, $features )
	{
		// make sure supported up to this point
		if ( true === $supported ) {
			// get values from args that aren't in features
			$diff = array_diff( $args, $features );
			// empty diff means all args supported
			return empty( $diff );
		}

		// no supported
		return false;
	}

	/**
	 * Add a component group.
	 * 
	 * @param string $name
	 */
	static public function add_group( $name )
	{
		// does name match valid format?
		if ( 1 === preg_match( '/^([a-z0-9]+[-]?)*[a-z0-9]+$/', $name ) ) {
			// yes, add to valid groups
			self::$groups[ $name ] = true;
			// add dynamic supports filter
			add_filter( 'current_theme_supports-' . ICE_SLUG . ':' . $name, array('ICE_Registry','supports'), 10, 3 );
		} else {
			throw new Exception( sprintf(
				'The group name "%s" does not match the allowed pattern', $name
			));
		}
	}

	/**
	 * Returns true if given group name exists.
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function is_group( $name )
	{
		// is name a key in the groups array?
		return isset( self::$groups[ $name ] );
	}

	/**
	 */
	public function accept( ICE_Visitor $visitor )
	{
		// loop all grouped components
		foreach ( $this->components as $group_components ) {
			// loop all components
			foreach ( $group_components as $component ) {
				// call accept
				$component->accept( $visitor );
			}
		}
	}

	/**
	 * Init all components.
	 */
	public function init()
	{
		// loop all grouped components
		foreach ( $this->components as $group_components ) {
			// loop all components
			foreach ( $group_components as $component ) {
				// call init on each one
				$component->init();
			}
		}
	}

	/**
	 * Substitute values from sibling settings.
	 *
	 * @param string $key
	 * @param array $settings
	 * @return string
	 */
	private function substitute( $key, $settings )
	{
		// loop all settings
		foreach( $settings as $skey => $sval ) {
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
		$keys = array( 'title', 'description', 'linked_image' );

		// loop all grouped settings
		foreach ( $this->settings as $group => $group_settings ) {
			// loop all settings
			foreach ( $group_settings as $name => $settings ) {
				// reduce settings to only scalars
				$scalar_settings = array_filter( $settings, 'is_scalar' );
				// loop all keys
				foreach( $keys as $key ) {
					// is key set in settings?
					if ( true === isset( $scalar_settings[ $key ] ) ) {
						// yep, attempt to substitute
						$this->settings[ $group ][ $name ][ $key ] = $this->substitute( $key, $scalar_settings );
					}
				}
			}
		}
	}

	/**
	 * Returns true if a component has been registered
	 *
	 * @param string $name
	 * @param string $group
	 * @return boolean
	 */
	final public function has( $name, $group = self::DEFAULT_GROUP )
	{
		// is name registered for given group?
		return isset( $this->components[ $group ][ $name ] );
	}

	/**
	 * Return a registered component object by name and optionally group.
	 * 
	 * Optionally, you can pass only the name param using sugary syntax in the format: [group].[name]
	 *
	 * @param string $name
	 * @param string $group
	 * @return ICE_Component
	 */
	final public function get( $name, $group = self::DEFAULT_GROUP )
	{
		// did we get only one arg?
		if ( 1 === func_num_args() ) {
			// yes, do sugary parsing
			$sugary = $this->parse_sugary_name( $name );
			// did it parse?
			if ( true === is_object( $sugary ) ) {
				// override name and group
				$name = $sugary->name;
				$group = $sugary->group;
			}
		}
		
		// are both group and name keys set?
		if ( true === isset( $this->components[ $group ][ $name ] ) ) {
			// yes, return component at those keys
			return $this->components[ $group ][ $name ];
		}

		// didn't find it
		throw new Exception( sprintf( 'Unable to get component "%s": not registered', $name ) );
	}

	/**
	 * Return all registered components as an array, optionally limited to a specific group.
	 *
	 * @param string $group
	 * @return array
	 */
	final public function get_all( $group = null )
	{
		// is group null?
		if ( null === $group ) {
			// yes, return all components
			return $this->components;
		} else {
			// return only for specific group
			return $this->components[ $group ];
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

		// loop all grouped components
		foreach ( $this->components as $group_components ) {
			// loop all components
			foreach ( $group_components as $component_i ) {
				// is given component parent of this one?
				if ( $component->is_parent_of( $component_i ) ) {
					// yes, push onto stack
					$components[] = $component_i;
				}
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

		// loop all grouped components
		foreach ( $this->components as $group_components ) {
			// loop all components
			foreach ( $group_components as $component ) {
				// components must NOT have a parent set
				if ( null === $component->get_property( 'parent' ) ) {
					// filter on component names
					if ( empty( $component_names ) || in_array( $component->get_name(), $component_names, true ) ) {
						// append to return array
						$components[] = $component;
					}
				}
			}
		}

		return $components;
	}

	/**
	 * Return the value of a setting for the given item name and optionally group.
	 *
	 * @param string $setting
	 * @param string $name
	 * @param string $group
	 * @return mixed
	 */
	public function get_setting( $setting, $name, $group = self::DEFAULT_GROUP )
	{
		// is setting set?
		if ( isset( $this->settings[ $group ][ $name ][ $setting ] ) ) {
			// yep, return it
			return $this->settings[ $group ][ $name ][ $setting ];
		}

		// return null by default
		return null;
	}

	/**
	 * Return the entire settings stack for the given item name, and optionally group name.
	 *
	 * @param string $name
	 * @param string $group
	 * @return array
	 */
	public function get_settings_array( $name, $group = self::DEFAULT_GROUP )
	{
		// any settings set?
		if ( isset( $this->settings[ $group ][ $name ] ) ) {
			// yep, return it
			return $this->settings[ $group ][ $name ];
		}

		// return empty array by default
		return array();
	}

	/**
	 * Attempt to parse a sugary name string into group and name.
	 *
	 * @param string $name
	 * @return stdClass|false
	 */
	final protected function parse_sugary_name( $name )
	{
		// try to split at sub item delimeter
		$parts = explode( self::SUB_ITEM_DELIM, $name );

		// get exactly two chunks?
		if ( count( $parts ) == 2 ) {
			// yes, set up object
			$obj = new stdClass();
			$obj->group = $parts[0];
			$obj->name = $parts[1];
			// return it
			return $obj;
		}

		// failed to parse
		return false;
	}

	/**
	 * Parse a sugary name and return it reformated.
	 *
	 * @uses sprintf()
	 * @param string $name
	 * @param string $format A valid sprintf() format.
	 * @return string
	 */
	final public function reformat_sugary_name( $name, $format = '%1$s_%2$s')
	{
		// try to parse the name
		$sugary = $this->parse_sugary_name( $name );

		// get an object?
		if ( true === is_object( $sugary ) ) {
			// yep, return it formated
			return sprintf( $format, $sugary->group, $sugary->name );
		} else {
			// nope, return it untouched
			return $name;
		}
	}

	/**
	 * Register a component's settings.
	 *
	 * @param array $args
	 * @return boolean
	 */
	final public function register( $args, $defaults = array() )
	{
		// merge args over defaults to get final settings
		$settings = array_merge( $defaults, $args );

		// does component have a name set?
		if ( true === isset( $settings[ 'name' ] ) ) {
			// yes, but does name match required format?
			if ( 1 === preg_match( '/^[a-z][a-z0-9]*(-[a-z0-9]+)*$/', $settings[ 'name' ] ) ) {
				// yep, set it
				$name = $settings[ 'name' ];
			} else {
				// invalid name
				throw new Exception( sprintf(
					'The %s name "%s" does not match the allowed pattern.',
					$this->_policy->get_handle(), $settings[ 'name' ]
				));
			}
		} else {
			// missing name
			throw new Exception( sprintf(
				'The "name" setting is missing for a %s component.',
				$this->_policy->get_handle()
			));
		}

		// does component have a group set?
		if ( true === isset( $settings[ 'group' ] ) ) {
			// yep, but is it valid?
			if ( true === $this->is_group( $settings[ 'group' ] ) ) {
				// yep, use it!
				$group = $settings[ 'group' ];
			} else {
				// developer error, throw exception
				throw new Exception( sprintf(
					'The component group "%s" does not exit.', $settings[ 'group' ]
				));
			}
		} else {
			$group = $settings[ 'group' ] = self::DEFAULT_GROUP;
		}

		// COMPAT check for ignore setting
		if ( isset( $settings[ 'ignore' ] ) ) {
			// is debug on?
			if ( WP_DEBUG ) {
				// debug is on, throw exception
				throw new OutOfBoundsException( 'The "ignore" setting is no longer valid.' );
			} else {
				// debug is off, do not create but fail silently
				return true;
			}
		}
		
		// does component have config already?
		if ( isset( $this->settings[ $group ][ $name ] ) ) {
			// yep, merge over existing settings
			$this->settings[ $group ][ $name ] =
				array_merge(
					$this->settings[ $group ][ $name ],
					$settings
				);
		} else {
			// nope, just assign it
			$this->settings[ $group ][ $name ] = $settings;
		}

		// config loaded
		return true;
	}

	/**
	 * Create and return a component.
	 *
	 * @param string $name
	 * @param string $type
	 * @return ICE_Component|boolean
	 */
	protected function create_component( $name, $group, $type = self::DEFAULT_TYPE )
	{
		// try to create component
		try {
			// call extensions create method
			return $this->_policy->extensions()->create( $type, $name, $group );
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
		// loop all grouped settings
		foreach( $this->settings as $group => $group_settings ) {

			// loop all settings
			foreach( $group_settings as $name => $settings ) {

				// check if already registered
				if ( false === isset( $this->components[ $group ][ $name ] ) ) {
		
					// create new component
					$component = $this->create_component( $name, $settings['group'], $settings['type'] );

					// get a component?
					if ( $component instanceof ICE_Component ) {
						// add to components stack
						$this->components[ $group ][ $name ] = $component;
					}

				} else {
					// component already registered, not good!
					throw new OverflowException(
						sprintf( 'The "%s" component cannot be created twice.', $name )
					);
				}
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

		// loop all component groups
		foreach ( $this->components as $group_components ) {
			// loop all components
			foreach( $group_components as $component ) {
				// finalize it
				$component->finalize();
			}
		}

		// initialize the response on the "init" action
		add_action( 'ice_init', array( $this, 'init' ) );
	}
}

//
// Helpers
//

/**
 * Register a component group.
 *
 * @param string $name
 */
function ice_register_group( $name )
{
	ICE_Registry::add_group( $name );
}