<?php
/**
 * Infinity Theme: option extensions, CSS overlay opacity class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage extensions
 * @since 1.0
 */

ICE_Loader::load_ext( 'options/ui/slider' );

/**
 * CSS overlay opacity
 *
 * @package Infinity
 * @subpackage extensions
 */
class Infinity_Exts_Options_Css_Overlay_Opacity
	extends ICE_Ext_Option_Ui_Slider
{
	protected function init()
	{
		// run parent
		parent::init();

		// init directives
		$this->title = __( 'Overlay Opacity', infinity_text_domain );
		$this->description = __( 'Select the overlay opacity by moving the slider', infinity_text_domain );
		$this->default_value = 0.2;
		$this->min = 0;
		$this->max = 1;
		$this->step = 0.01;
		$this->suffix = ' level';
		$this->style_property = 'opacity';
	}
}

?>
