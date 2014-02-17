<?php
/**
 * ICE API: features factory class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage features
 * @since 1.0
 */

ICE_Loader::load( 'base/factory' );

/**
 * Make creating feature objects easy
 *
 * @package ICE-components
 * @subpackage features
 */
class ICE_Feature_Factory extends ICE_Factory
{
	/**
	 */
	public function create( $name, $settings )
	{
		// only create if supported
		if ( true === current_theme_supports( $name ) ) {
			// supported, call parent
			return parent::create( $name, $settings );
		} else {
			// not supported
			return false;
		}
	}
}
