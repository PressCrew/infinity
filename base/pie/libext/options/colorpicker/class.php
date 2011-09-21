<?php
/**
 * PIE API: option extensions, colorpicker class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/text' );

/**
 * Colorpicker option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Colorpicker
	extends Pie_Easy_Exts_Options_Text
{
	public function init_styles()
	{
		parent::init_styles();
		wp_enqueue_style( 'pie-easy-colorpicker' );
	}

	public function init_scripts()
	{
		parent::init_scripts();
		wp_enqueue_script( 'pie-easy-colorpicker' );
	}

}

?>
