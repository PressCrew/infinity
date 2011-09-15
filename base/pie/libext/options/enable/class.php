<?php
/**
 * PIE API: option extensions, "enable" checkbox class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/checkbox' );

/**
 * Enable checkbox option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Enable
	extends Pie_Easy_Exts_Options_Checkbox
		implements Pie_Easy_Options_Option_Auto_Field
{
	
	public function load_field_options()
	{
		return array(
			true => __( 'Enable', pie_easy_text_domain )
		);
	}
}

?>
