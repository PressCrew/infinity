<?php
/**
 * PIE API: option extensions, top/bottom radio class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/radio' );

/**
 * Top/Bottom radio option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Position_Top_Bottom
	extends Pie_Easy_Exts_Options_Radio
		implements Pie_Easy_Options_Option_Auto_Field
{
	/**
	 */
	public function load_field_options()
	{
		return array(
			't' => __( 'Top', pie_easy_text_domain ),
			'b' => __( 'Bottom', pie_easy_text_domain )
		);
	}
}

?>
