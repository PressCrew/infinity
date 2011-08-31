<?php
/**
 * PIE API: feature renderer class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage features
 * @since 1.0
 */

/**
 * Make rendering features easy
 *
 * @package PIE-components
 * @subpackage features
 */
abstract class Pie_Easy_Features_Renderer extends Pie_Easy_Renderer
{
	/**
	 * Render the feature
	 */
	protected function render_output()
	{
		$this->get_current()->load_template();
	}
}

?>
