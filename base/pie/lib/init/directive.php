<?php
/**
 * PIE API: init directive class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage init
 * @since 1.0
 */

/**
 * Make an init directive easy
 *
 * @package PIE
 * @subpackage init
 * @property-read string $name The name of the directive
 * @property mixed $value The value of the directive
 * @property-read boolean $read_only Whether the value is read only
 */
class Pie_Easy_Init_Directive extends Pie_Easy_Base
{
	/**
	 * @var string The name of this directive
	 */
	private $name;

	/**
	 * @var mixed The value of this directive
	 */
	private $value;

	/**
	 * @var string The theme that set this directive
	 */
	private $theme;

	/**
	 * @var boolean Set to true to make directive read only
	 */
	private $read_only = false;

	/**
	 * Initialize the directive
	 *
	 * @param string $theme Slug of the theme which is setting this directive
	 * @param string $name Name for this directive (slug format)
	 * @param mixed $value Value for this directive
	 * @param boolean $read_only Set to true to disallow modification of the value once set
	 */
	public function __construct( $theme, $name, $value = null, $read_only = false )
	{
		$this->name = strtolower( trim( $name ) );
		$this->theme = strtolower( trim( $theme ) );
		$this->value = $value;

		if ( $read_only ) {
			$this->read_only = true;
		}
	}

	/**
	 */
	public function __get( $name )
	{
		switch ( $name ) {
			case 'theme':
			case 'name':
			case 'value':
			case 'read_only':
				return $this->$name;
			default:
				return parent::__get( $name );
		}
	}

	/**
	 */
	public function __isset( $name )
	{
		switch ( $name ) {
			case 'theme':
			case 'name':
			case 'value':
			case 'read_only':
				return isset( $this->$name );
			default:
				return parent::__isset( $name );
		}
	}

	/**
	 */
	public function __set( $name, $value )
	{
		switch ( $name ) {
			case 'value':
				return $this->set_value( $value );
			default:
				return parent::__set( $name, $value );
		}
	}

	/**
	 */
	public function __unset( $name )
	{
		switch ( $name ) {
			case 'value':
				return $this->set_value( null );
			default:
				return parent::__unset( $name );
		}
	}

	/**
	 * Set the value
	 *
	 * @param mixed $value New value for the directive
	 * @param boolean $silent Set to true to silently ignore failure to set due to read only status
	 * @return boolean true on success, false on silent failure
	 */
	public function set_value( $value, $silent = false )
	{
		if ( $this->read_only ) {
			if ( $silent ) {
				return false;
			} else {
				throw new Exception(
					sprintf( 'The "%s" directive has been set to read only', $this->name ) );
			}
		}

		$this->value = $value;
		return true;
	}
}

/**
 * Make maps of init directives easy
 *
 * @package PIE
 * @subpackage init
 */
class Pie_Easy_Init_Directive_Registry extends Pie_Easy_Base
{
	/**
	 * Registered directives
	 *
	 * @var Pie_Easy_Map
	 */
	private $directives;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->directives = new Pie_Easy_Map();
	}

	/**
	 * Set a directive
	 *
	 * @param string $theme
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $read_only
	 */
	public function set( $theme, $name, $value, $read_only = null )
	{
		// convert arrays to maps
		if ( is_array( $value ) ) {
			$value = new Pie_Easy_Map( $value, $read_only );
		}

		// check for existing map of theme directives
		if ( $this->has( $name ) ) {
			// use existing directive map
			$theme_map = $this->get_map( $name );
		} else {
			// create and add new map
			$theme_map = new Pie_Easy_Map();
			$this->directives->add( $name, $theme_map );
		}

		// check for existing directive for given theme
		if ( $theme_map->contains( $theme ) ) {
			return $theme_map->item_at($theme)->set_value( $value );
		} else {
			// create new directive
			$directive = new Pie_Easy_Init_Directive( $theme, $name, $value, $read_only );
			// add it to directive map
			return $theme_map->add( $theme, $directive );
		}
	}

	/**
	 * Return true if directive is set
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function has( $name )
	{
		// check for theme directive
		return $this->directives->contains( $name );
	}

	/**
	 * Get a directive by name
	 *
	 * @param string $name Name of directive to retreive (slug)
	 * @return Pie_Easy_Init_Directive
	 */
	public function get( $name )
	{
		// use existing directive map
		$theme_map = $this->get_map( $name );
		
		// get a map?
		if ( $theme_map ) {
			// get theme stack TOP DOWN
			$themes = Pie_Easy_Scheme::instance()->theme_stack( true );
			// did we get a stack?
			if ( is_array( $themes ) && count( $themes ) ) {
				// check for directive according to theme stack
				foreach ( $themes as $theme ) {
					// does theme have this directive set?
					if ( $theme_map->contains( $theme ) ) {
						// yes, return it
						return $theme_map->item_at($theme);
					}
				}
			}
		}

		// directive not set
		return null;
	}

	/**
	 * Get a directive's entire themes map
	 *
	 * @param string $name
	 * @return Pie_Easy_Map|null
	 */
	public function get_map( $name )
	{
		if ( $this->has( $name ) ) {
			return $this->directives->item_at( $name );
		}

		// directive not set
		return null;
	}

	/**
	 * Return all directives as an array
	 *
	 * @return array
	 */
	public function get_all()
	{
		return $this->directives->to_array();
	}
}
?>
