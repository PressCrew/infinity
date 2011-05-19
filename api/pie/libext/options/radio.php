<?php
/**
 * PIE API: option extensions, radio class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options-ext
 * @since 1.0
 */

/**
 * Radio option
 *
 * @package PIE
 * @subpackage options-ext
 */
class Pie_Easy_Exts_Option_Radio
	extends Pie_Easy_Options_Option
{
	/**
	 * Render one or more radio button tags
	 *
	 * @see Pie_Easy_Options_Renderer::render_input_group
	 */
	public function render_field()
	{
		$this->policy()->renderer()->render_input_group( 'radio' );
	}
}

?>
