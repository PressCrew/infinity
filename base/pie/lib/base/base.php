<?php
/**
 * PIE API: base class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
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
	 * @ignore
	 * @param string $name
	 */
	public function __get( $name )
	{
		throw new Exception(
			sprintf( 'The "%s" property is not accessible for reading.', $name ) );
	}

	/**
	 * Setter
	 * 
	 * @ignore
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set( $name, $value )
	{
		throw new Exception(
			sprintf( 'The "%s" property is not accessible for writing.', $name ) );
	}

	/**
	 * Issetter
	 *
	 * @ignore
	 * @param string $name
	 */
	public function __isset( $name )
	{
		throw new Exception(
			sprintf( 'The "%s" property does not exist (isset).', $name ) );
	}

	/**
	 * Issetter
	 *
	 * @ignore
	 * @param string $name
	 */
	public function __unset( $name )
	{
		throw new Exception(
			sprintf( 'The "%s" property does not exist (unset).', $name ) );
	}

	/**
	 * Caller
	 *
	 * @ignore
	 * @param string $name
	 * @param array $arguments 
	 */
	public function __call( $name, $arguments )
	{
		throw new Exception(
			sprintf( 'The "%s" metnod does not exist (obj context).', $name ) );
	}

	/**
	 * toStringer
	 *
	 * @ignore
	 */
	public function __toString()
	{
		throw new Exception(
			sprintf( 'The "%s" class cannot be converted to a string.', get_class($this) ) );
	}
}

?>
