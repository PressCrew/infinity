<?php
/**
 * PIE API: shortcode renderer class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage options
 * @since 1.0
 */

/**
 * Make rendering shortcodes easy
 *
 * @package PIE-components
 * @subpackage shortcodes
 */
abstract class Pie_Easy_Shortcodes_Renderer extends Pie_Easy_Renderer
{
	/**
	 * Render the shortcode
	 */
	protected function render_output()
	{
		$this->get_current()->load_template();
	}
}

?>
