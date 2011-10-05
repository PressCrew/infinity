<?php
/**
 * PIE API: option extensions, css repeat class file
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
 * CSS repeat options
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Css_Repeat
	extends Pie_Easy_Exts_Options_Select
		implements Pie_Easy_Options_Option_Auto_Field
{
	/**
	 */
	public function load_field_options()
	{
		return array(
			'inherit' => __( 'Default', pie_easy_text_domain ),
			'repeat' => __( 'Full Tiling', pie_easy_text_domain ),
			'repeat-x' => __( 'Tile Horizontally Only', pie_easy_text_domain ),
			'repeat-y' => __( 'Tile Vertically Only', pie_easy_text_domain ),
			'no-repeat' => __( 'Disable Tiling', pie_easy_text_domain )
		);
	}
}

?>