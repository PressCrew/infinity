<?php
/**
 * PIE API: base icon class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
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
class Pie_Easy_Icon extends Pie_Easy_Base
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

	public function __get( $name )
	{
		switch ( $name ) {
			case 'primary':
			case 'secondary':
				return $this->$name;
			default:
				return parent::__get( $name );
		}
	}

	public function __isset( $name )
	{
		switch ( $name ) {
			case 'primary':
			case 'secondary':
				return isset( $this->$name );
			default:
				return parent::__isset( $name );
		}
	}

	public function __set( $name, $value )
	{
		switch ( $name ) {
			case 'primary':
			case 'secondary':
				return $this->$name = $value;
			default:
				return parent::__set( $name, $value );
		}
	}

	public function __unset( $name )
	{
		switch ( $name ) {
			case 'primary':
			case 'secondary':
				return $this->$name = null;
			default:
				return parent::__unset( $name );
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
