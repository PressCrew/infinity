<?php
/**
 * PIE API: option extensions, textarea class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

/**
 * Textarea option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Textarea
	extends Pie_Easy_Options_Option
{
	/**
	 * Render a text area
	 */
	public function render_field()
	{
		$this->policy()->renderer()->render_textarea();
	}
}

?>
