<?php
/**
 * PIE API: base icon class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage ui
 * @since 1.0
 */

/**
 * Make icons for component navigation easy
 *
 * @package PIE
 * @subpackage ui
 * @property-read string $primary
 * @property-read string $secondary
 */
class Pie_Easy_Icon
{
	/**
	 * The primary icon name
	 *
	 * @var string
	 */
	private $primary;

	/**
	 * The secondary icon name
	 *
	 * @var string
	 */
	private $secondary;

	/**
	 * Constructor
	 *
	 * Currently only jQuery UI icons are supported.
	 *
	 * @param string $primary Name of the primary icon.
	 * @param string $secondary Name of the secondary icon.
	 */
	public function __construct( $primary = null, $secondary = null )
	{
		$this->primary = $primary;
		$this->secondary = $secondary;
	}

	/**
	 * @ignore
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		switch ( $name ) {
			case 'primary':
				return $this->primary;
			case 'secondary':
				return $this->secondary;
			default:
				throw new Exception( 'Invalid property' );
		}
	}

	/**
	 * @ignore
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 */
	public function __set( $name, $value )
	{
		switch ( $name ) {
			case 'primary':
				return $this->primary = $value;
			case 'secondary':
				return $this->secondary = $value;
		}
	}

	/**
	 * Get icons config for jQuery UI
	 * 
	 * @return string|null
	 */
	public function config()
	{
		$a = array();

		if ( $this->primary ) {
			$a[] = sprintf( "primary: '%s'", $this->primary );
		}

		if ( $this->secondary ) {
			$a[] = sprintf( "secondary: '%s'", $this->secondary );
		}

		if ( count( $a ) ) {
			return sprintf( 'icons: {%s}', join( ',', $a ) );
		} else {
			return null;
		}
	}
}

?>
