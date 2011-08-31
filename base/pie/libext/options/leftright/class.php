<?php
/**
 * PIE API: option extensions, left/right radio class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/radio' );

/**
 * Left/Right radio option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Option_Leftright
	extends Pie_Easy_Exts_Option_Radio
		implements Pie_Easy_Options_Option_Auto_Field
{
	public function load_field_options()
	{
		return array(
			'l' => __( 'Left', pie_easy_text_domain ),
			'r' => __( 'Right', pie_easy_text_domain )
		);
	}
}

?>
