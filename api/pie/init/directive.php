<?php
/**
 * PIE API: init directive class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
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
	 * @param string $name Name for this directive (slug format)
	 * @param mixed $value Value for this directive
	 * @param string $theme Slug of the theme which is setting this directive
	 * @param boolean $read_only Set to true to disallow modification of the value once set
	 */
	public function __construct( $name, $value, $theme, $read_only = false )
	{
		$this->name = strtolower( trim( $name ) );
		$this->theme = strtolower( trim( $theme ) );
		$this->value = $value;

		if ( $read_only ) {
			$this->read_only = true;
		}
	}

	/**
	 * @ignore
	 * @param string $name
	 * @return mixed
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
				throw new Exception(
					sprintf( 'The property "%s" does not exist', $name ) );
		}
	}

	/**
	 * @ignore
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

?>
