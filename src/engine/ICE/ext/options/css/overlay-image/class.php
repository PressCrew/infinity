<?php
/**
 * Infinity Theme: option extensions, CSS overlay image class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity-extensions
 * @subpackage options
 * @since 1.0
 */

/**
 * CSS overlay image
 *
 * @package Infinity-extensions
 * @subpackage options
 */
class ICE_Ext_Option_Css_Overlay_Image
	extends ICE_Ext_Option_Ui_Overlay_Picker
{
	/**
	 */
	protected function configure()
	{
		// set defaults first
		$this->title = __( 'Background Overlay', 'infinity-engine' );
		$this->description = __( 'Select a texture to use as the background overlay', 'infinity-engine' );
		$this->item_width = '100px';
		$this->item_height = '100px';

		// run parent
		parent::configure();
	}
}
