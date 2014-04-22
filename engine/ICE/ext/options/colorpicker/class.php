<?php
/**
 * ICE API: option extensions, colorpicker class file
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
 * Colorpicker option
 *
 * @package ICE-extensions
 * @subpackage options
 */
class ICE_Ext_Option_Colorpicker
	extends ICE_Ext_Option_Text
{
	/**
	 */
	public function init()
	{
		// run parent
		parent::init();

		// enqueue dash assets
		add_action( 'ice_init_dash', array( $this, 'do_enqueue_dash' ) );
	}
	
	/**
	 */
	public function do_enqueue_dash()
	{
		ice_enqueue_style( 'wp-color-picker' );
		ice_enqueue_script( 'wp-color-picker' );
	}

}
