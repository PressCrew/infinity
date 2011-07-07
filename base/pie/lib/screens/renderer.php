<?php
/**
 * PIE API: screen renderer class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options
 * @since 1.0
 */

/**
 * Make rendering screens easy
 *
 * @package PIE
 * @subpackage screens
 */
abstract class Pie_Easy_Screens_Renderer extends Pie_Easy_Renderer
{
	/**
	 * Render the screen
	 */
	protected function render_output()
	{
		$this->get_current()->load_template();
	}

}

?>
