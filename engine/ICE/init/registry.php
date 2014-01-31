<?php
/**
 * ICE API: init registry class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage init
 * @since 1.0
 */

/**
 * Make maps of initialization data easy
 *
 * @package ICE
 * @subpackage init
 */
abstract class ICE_Init_Registry extends ICE_Map
{
	/**
	 * Create a new instance of ICE_Init_Data for storing in the registry
	 *
	 * @param string $theme Slug of the theme which is setting this data
	 * @param string $name Name for this data (slug format)
	 * @param mixed $value Value for this data
	 * @param boolean $read_only Set to true to disallow modification of the value once set
	 * @return ICE_Init_Data
	 */
	protected function create( $theme, $name, $value = null, $read_only = false )
	{
		return new ICE_Init_Data( $theme, $name, $value, $read_only );
	}
	
	/**
	 * Set a value
	 *
	 * This method supports delayed locking of a value. Set the read only flags to
	 * true at any time to lock the value(s) from further modification.
	 *
	 * @param string $theme
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $ro_value
	 * @param boolean $ro_theme
	 */
	public function set( $theme, $name, $value, $ro_value = false, $ro_theme = false )
	{
		// convert arrays to maps
		if ( is_array( $value ) ) {
			$value = new ICE_Map( $value, $ro_value );
		}

		// try to get existing map
		$theme_map = $this->item_at( $name );

		// handle empty map
		if ( null === $theme_map ) {
			// create new map
			$theme_map = new ICE_Map_Lockable();
			// add theme map to registry (myself)
			$this->add( $name, $theme_map );
		}

		// check for existing data for given theme
		if ( $theme_map->contains( $theme ) ) {
			// get data
			$data = $theme_map->item_at( $theme );
			// update value, save result
			$result = $data->set_value( $value );
		} else {
			// create new data object
			$data = $this->create( $theme, $name, $value );
			// set registry
			$data->registry( $this );
			// add to theme map
			$theme_map->add( $theme, $data );
			// new data, always good result
			$result = true;
		}

		// lock data map if applicable
		if ( $ro_value ) {
			$data->lock();
		}

		// lock from any overriding theme data
		if ( $ro_theme ) {
			$theme_map->lock();
		}

		// return result
		return $result;
	}

	/**
	 * Return true if data key is set
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function has( $name )
	{
		// check for theme data
		return $this->contains( $name );
	}

	/**
	 * Get data by name (key)
	 *
	 * @param string $name Name of key to retrieve (slug)
	 * @return ICE_Init_Data
	 */
	public function get( $name )
	{
		// value is null by default
		$value = null;

		// use existing data map if exists
		$theme_map = $this->item_at( $name );

		// get a map?
		if ( $theme_map ) {
			// check for data according to theme stack
			foreach ( ICE_Scheme::instance()->theme_stack() as $theme ) {
				// does theme have this data key set?
				if ( $theme_map->contains( $theme ) ) {
					// yes, grab value
					$value = $theme_map->item_at($theme);
					// and skip remaining themes
					break;
				}
			}
		}

		// always return looked up value
		return $value;
	}

	public function get_value( $name )
	{
		$data = $this->get( $name );

		if ( $data ) {
			return $data->get_value();
		}

		return null;
	}

	/**
	 * Get a data key's entire themes map
	 *
	 * @param string $name
	 * @return ICE_Map|null
	 */
	public function get_map( $name )
	{
		// return item for name (key)
		return $this->item_at( $name );
	}

	/**
	 * Return all data items as an array
	 *
	 * @return array
	 */
	public function get_all()
	{
		return $this->to_array();
	}

	/**
	 * Lock registry from further addition/removal of data
	 */
	public function lock()
	{
		$this->set_read_only( true );
	}

	/**
	 * Return lock state
	 *
	 * @return boolean
	 */
	public function locked()
	{
		return ( $this->get_read_only() === true );
	}
}
