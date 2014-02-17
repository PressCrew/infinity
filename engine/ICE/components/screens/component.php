<?php
/**
 * ICE API: screen class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage screens
 * @since 1.0
 */

ICE_Loader::load( 'base/component' );

/**
 * Make a display screen easy
 *
 * @package ICE-components
 * @subpackage screens
 */
abstract class ICE_Screen extends ICE_Component
{
	/**
	 * Target of the screen menu link.
	 *
	 * @var string
	 */
	protected $target;

	/**
	 * URL where to find the screen.
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * @todo need a better way to set URL from public scope.
	 */
	protected function set_property( $name, $value )
	{
		switch ( $name ) {
			case 'url':
				return $this->$name = $value;
			default:
				return parent::set_property( $name, $value );
		}
	}

	/**
	 */
	protected function get_property( $name )
	{
		switch ( $name ) {
			case 'url':
			case 'target':
				return $this->$name;
			default:
				return parent::get_property( $name );
		}
	}
	
}
