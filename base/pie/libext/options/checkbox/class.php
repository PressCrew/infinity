<?php
/**
 * PIE API: option extensions, checkbox class file
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
 * Checkbox option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Option_Checkbox
	extends Pie_Easy_Options_Option
{
	/**
	 * Render one or more checkboxes
	 *
	 * @see Pie_Easy_Options_Renderer::render_input_group
	 */
	public function render_field()
	{
		$this->policy()->renderer()->render_input_group( 'checkbox' );
	}
}

?>
