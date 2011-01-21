<?php
/**
 * PIE API init directive class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage options
 * @since 1.0
 */

/**
 * Make an init directive easy
 *
 * @property string $name The name of the directive
 * @property mixed $value The value of the directive
 * @property-read boolean $read_only Whether the value is read only
 */
abstract class Pie_Easy_Init_Directive
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
	 * @var boolean Set to true to make directive read only
	 */
	private $read_only = false;

	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $read_only
	 */
	public function __construct( $name, $value, $read_only = false )
	{
		$this->name = strtolower( trim( $name ) );
		$this->value = $value;

		if ( $read_only ) {
			$this->read_only = true;
		}
	}

	/**
	 * Getter
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		switch ( $name ) {
			case 'name':
			case 'value':	
			case 'read_only':
				return $this->$name;
			default:
				throw new Exception(
					sprintf( 'The property "%s" does not exist', $name ) );
		}
	}

	/**
	 * Setter
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set( $name, $value )
	{
		switch ( $name ) {
			case 'value':
				return $this->set_value( $value );
			default:
				throw new Exception( sprintf( 'The "%s" property is not writable', $name ) );
		}
	}
	
	/**
	 * Set the value
	 * 
	 * @param string $value
	 * @param boolean $silent Set to true to silently ignore failure to set due to read only status
	 * @return boolean True on success, false on silent failure
	 */
	public function set_value( $value, $silent = false )
	{
		if ( $this->read_only ) {
			if ( $silent ) {
				return false;
			} else {
				throw new Exception(
					sprintf( 'The "%s" directive has been set to read only.', $this->name ) );
			}
		}

		$this->value = $value;
		return true;
	}
}

?>
