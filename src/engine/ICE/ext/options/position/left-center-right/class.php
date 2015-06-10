<?php
/**
 * ICE API: option extensions, left/center/right radio class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage options
 * @since 1.0
 */

/**
 * Left/Center/Right radio option
 *
 * @package ICE-extensions
 * @subpackage options
 */
class ICE_Ext_Option_Position_Left_Center_Right
	extends ICE_Ext_Option_Radio
		implements ICE_Option_Auto_Field
{
	/**
	 */
	public function load_field_options()
	{
		return array(
			'l' => __( 'Left', 'infinity-engine' ),
			'c' => __( 'Center', 'infinity-engine' ),
			'r' => __( 'Right', 'infinity-engine' )
		);
	}
}
