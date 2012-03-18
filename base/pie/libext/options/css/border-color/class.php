<?php
/**
 * PIE API: option extensions, CSS border color picker class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/colorpicker' );

/**
 * CSS Border Color Picker
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Css_Border_Color
	extends Pie_Easy_Exts_Options_Colorpicker
{
	protected function init()
	{
		parent::init();

		// initialize directives
		$this->title = 'Border Color';
		$this->description = 'Choose a color for the border';
		$this->style_property = 'border-color';
	}
}

?>
