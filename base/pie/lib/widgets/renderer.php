<?php
/**
 * PIE API: widget renderer class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage widgets
 * @since 1.0
 */

/**
 * Make rendering widgets easy
 *
 * @package PIE
 * @subpackage widgets
 */
abstract class Pie_Easy_Widgets_Renderer extends Pie_Easy_Renderer
{
	/**
	 * Render the widget
	 */
	protected function render_output()
	{
		$this->get_current()->load_template();
	}

}

?>
