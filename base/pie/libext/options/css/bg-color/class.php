<?php
/**
 * PIE API: option extensions, css background color class file
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
 * CSS background color option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Css_Bg_Color
	extends Pie_Easy_Exts_Options_Colorpicker
{
	/**
	 */
	protected function init()
	{
		// run parent
		parent::init();

		// init directives
		$this->title = __( 'Background Color', pie_easy_text_domain );
		$this->description = __( 'Choose a background color', pie_easy_text_domain );
		$this->style_property = 'background-color';
	}
}

?>
