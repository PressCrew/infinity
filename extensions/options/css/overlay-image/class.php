<?php
/**
 * Infinity Theme: option extensions, CSS overlay image class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage extensions
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/ui/overlay-picker' );

/**
 * CSS overlay image
 *
 * @package Infinity
 * @subpackage extensions
 */
class Infinity_Exts_Options_Css_Overlay_Image
	extends Pie_Easy_Exts_Options_Ui_Overlay_Picker
{
	protected function init()
	{
		// run parent
		parent::init();

		// init directives
		$this->title = __( 'Overlay Image', infinity_text_domain );
		$this->description = __( 'Select a texture to use as the background overlay', infinity_text_domain );
		$this->item_width = '100px';
		$this->item_height = '100px';
		$this->file_directory = 'assets/images/textures';
		$this->file_extension = 'png';
	}
}

?>
