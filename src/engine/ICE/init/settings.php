<?php
/**
 * ICE API: init settings class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage init
 * @since 1.2
 */

/**
 * Make tracking a "stack" of initialization settings easy.
 *
 * @package ICE
 * @subpackage init
 */
class ICE_Init_Settings
{
	/**
	 * @var array
	 */
	private $data = array();

	/**
	 * Set a value.
	 *
	 * @param string $theme
	 * @param string $name
	 * @param mixed $value
	 */
	public function set( $theme, $name, $value )
	{
		// has value already been set for theme?
		if (
			true === isset( $this->data[ $name ][ $theme ] ) ||
			true === isset( $this->data[ $name ] ) &&
			true === array_key_exists( $theme, $this->data[ $name ] )
		) {
			// yes, overwriting is not allowed
			throw new OverflowException(
				sprintf(
					__( 'Cannot overwrite the value of setting "%s" for theme "%s".', 'infinity-engine' ),
					$name,
					$theme
				)
			);
		}

		// set the value
		$this->data[ $name ][ $theme ] = $value;
	}

	/**
	 * Get a value.
	 *
	 * @param string $theme
	 * @param string $name
	 * @return boolean
	 */
	public function get( $theme, $name )
	{
		if ( isset( $this->data[ $name ][ $theme ] ) ) {
			return $this->data[ $name ][ $theme ];
		} else {
			return null;
		}
	}

	/**
	 * Get effective theme.
	 *
	 * @param string $name Name of key to retrieve (slug)
	 * @param callable $callback The callback to use to seek to the correct theme stack position.
	 * @return string
	 */
	public function get_theme_name( $name, $callback = 'end' )
	{
		// exists in value map?
		if ( isset( $this->data[ $name ] ) ) {
			// seek using callback
			$callback( $this->data[ $name ] );
			// return current key
			return key( $this->data[ $name ] );
		} else {
			return null;
		}
	}

	/**
	 * Get value for each theme.
	 *
	 * @param string $name Name of key to retrieve (slug)
	 * @return array
	 */
	public function get_stack( $name )
	{
		// in value map?
		if ( isset( $this->data[ $name ] ) ) {
			// return values for all themes
			return $this->data[ $name ];
		} else {
			// no values found
			return array();
		}
	}

	/**
	 * Return all data items untouched.
	 *
	 * @return array
	 */
	public function get_stack_all()
	{
		// return data
		return $this->data;
	}
	
	/**
	 * Get effective value.
	 *
	 * @param string $name Name of key to retrieve (slug)
	 * @param callable $callback The callback to use to seek to the correct theme stack position.
	 * @return mixed
	 */
	public function get_value( $name, $callback = 'end' )
	{
		// exists in value map?
		if ( isset( $this->data[ $name ] ) ) {
			// seek using callback
			$callback( $this->data[ $name ] );
			// return value using current
			return current( $this->data[ $name ] );
		} else {
			// not set or null
			return null;
		}
	}
	
	/**
	 * Get effective theme/value pair suitable for use with list().
	 *
	 * @param string $name Name of key to retrieve (slug)
	 * @param callable $callback The callback to use to seek to the correct theme stack position.
	 * @return array|false
	 */
	public function get_value_each( $name, $callback = 'end' )
	{
		// exists in value map?
		if ( isset( $this->data[ $name ] ) ) {
			// seek using callback
			$callback( $this->data[ $name ] );
			// return theme/value pair
			return array( key( $this->data[ $name ] ), current( $this->data[ $name ] ) );
		} else {
			// not set or null
			return false;
		}
	}
	
	/**
	 * Return all data values.
	 *
	 * @param callable $callback The callback used to reduce the theme stacked values to one value.
	 * @return array
	 */
	public function get_value_all( $callback = 'end' )
	{
		// return mapped array
		return array_map( $callback, $this->data );
	}

}
