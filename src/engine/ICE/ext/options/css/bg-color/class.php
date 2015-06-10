<?php
/**
 * ICE API: option extensions, css background color class file
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
 * CSS background color option
 *
 * @package ICE-extensions
 * @subpackage options
 */
class ICE_Ext_Option_Css_Bg_Color
	extends ICE_Ext_Option_Colorpicker
{

	/**
	 */
	protected function configure()
	{
		// set defaults first
		$this->title = __( 'Background Color', 'infinity-engine' );
		$this->description = __( 'Choose a background color', 'infinity-engine' );
		$this->style_property = 'background-color';

		// run parent
		parent::configure();
	}
}
