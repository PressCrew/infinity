<?php
/**
 * ICE API: options policy class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage options
 * @since 1.0
 */

ICE_Loader::load( 'base/policy' );

/**
 * Make customizing options implementations easy
 *
 * This object is passed to each option allowing the implementing API to
 * customize the implementation without confusing hooks and such.
 *
 * @package ICE-components
 * @subpackage options
 */
abstract class ICE_Options_Policy extends ICE_Policy
{
	/**
	 * @return string
	 */
	public function get_handle( $plural = true )
	{
		return ( $plural ) ? 'options' : 'option';
	}
}

?>
