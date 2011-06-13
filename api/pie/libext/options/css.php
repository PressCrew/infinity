<?php
/**
 * PIE API: option extensions, css class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options-ext
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/textarea' );

/**
 * CSS option
 *
 * @package PIE
 * @subpackage options-ext
 */
class Pie_Easy_Exts_Option_Css
	extends Pie_Easy_Exts_Option_Textarea
		implements Pie_Easy_Styleable
{
	public function export_css()
	{
		print $this->get();
	}
}

?>
