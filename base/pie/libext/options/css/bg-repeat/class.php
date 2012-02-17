<?php
/**
 * PIE API: option extensions, css background repeat class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/select' );

/**
 * CSS background repeat option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Css_Bg_Repeat
	extends Pie_Easy_Exts_Options_Select
{
	/**
	 */
	protected function init()
	{
		// run parent
		parent::init();

		// init directives
		$this->title = __( 'Background Image Tiling', pie_easy_text_domain );
		$this->description = __( 'Set the tiling mode of the background image', pie_easy_text_domain );
		$this->style_property = 'background-repeat';
		$this->default_value = 'repeat';
	}
}

?>
