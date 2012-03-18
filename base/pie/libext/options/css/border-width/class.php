<?php
/**
 * PIE API: option extensions, CSS border width slider class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/css/length-px' );

/**
 * CSS Border Width Slider
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Css_Border_Width
	extends Pie_Easy_Exts_Options_Css_Length_Px
{
	protected function init()
	{
		parent::init();

		// initialize directives
		$this->title = 'Border Width';
		$this->description = 'Select the width of the border by moving the slider';
		$this->style_property = 'border-width';
	}
}

?>
