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
					__( 'Cannot overwrite the value of setting "%s" for theme "%s".', 'infinity' ),
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
	 * Get effective value.
	 *
	 * @param string $name Name of key to retrieve (slug)
	 * @return mixed
	 */
	public function get_value( $name )
	{
		// get effective theme
		$theme = $this->get_theme( $name );

		// get a theme?
		if ( $theme ) {
			// yep, return value for that theme
			return $this->data[ $name ][ $theme ];
		} else {
			// no value found
			return null;
		}
	}
	
	/**
	 * Get all effective values.
	 *
	 * @param string $name Name of key to retrieve (slug)
	 * @return array
	 */
	public function get_values( $name )
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
	 * Get effective theme.
	 *
	 * @param string $name Name of key to retrieve (slug)
	 * @return string
	 */
	public function get_theme( $name )
	{
		// exists in value map?
		if ( isset( $this->data[ $name ] ) ) {
			// yes, seek to end of stack
			end( $this->data[ $name ] );
			// return last key in stack
			return key( $this->data[ $name ] );
		} else {
			return null;
		}
	}

	/**
	 * Return all data items as an array
	 *
	 * @return array
	 */
//	public function get_all()
//	{
//		return $this->to_array();
//	}
}
