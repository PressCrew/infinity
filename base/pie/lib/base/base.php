<?php
/**
 * PIE API: base class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

/**
 * Every PIE class extends this one
 *
 * @package PIE
 * @subpackage base
 */
abstract class Pie_Easy_Base
{
	/**
	 * Getter
	 *
	 * @param string $name Property name
	 */
	public function __get( $name )
	{
		throw new Exception(
			sprintf( 'The "%s" property is not accessible for reading.', $name ) );
	}

	/**
	 * Setter
	 * 
	 * @param string $name Property name
	 * @param mixed $value Property value
	 */
	public function __set( $name, $value )
	{
		throw new Exception(
			sprintf( 'The "%s" property is not accessible for writing.', $name ) );
	}

	/**
	 * Issetter
	 *
	 * @param string $name Property name
	 */
	public function __isset( $name )
	{
		throw new Exception(
			sprintf( 'The "%s" property does not exist (isset).', $name ) );
	}

	/**
	 * Issetter
	 *
	 * @param string $name Property name
	 */
	public function __unset( $name )
	{
		throw new Exception(
			sprintf( 'The "%s" property does not exist (unset).', $name ) );
	}

	/**
	 * Caller
	 *
	 * @param string $name Method name
	 * @param array $arguments Method arguments
	 */
	public function __call( $name, $arguments )
	{
		throw new Exception(
			sprintf( 'The "%s" method does not exist (obj context).', $name ) );
	}

	/**
	 * toStringer
	 *
	 * @return string
	 */
	public function __toString()
	{
		throw new Exception(
			sprintf( 'The "%s" class cannot be converted to a string.', get_class($this) ) );
	}
}

?>
