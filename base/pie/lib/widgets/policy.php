<?php
/**
 * PIE API: widgets policy class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage widgets
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/policy' );

/**
 * Make customizing widget implementations easy
 *
 * This object is passed to each widget allowing the implementing API to
 * customize the implementation without confusing hooks and such.
 *
 * @package PIE
 * @subpackage widgets
 */
abstract class Pie_Easy_Widgets_Policy extends Pie_Easy_Policy
{
	/**
	 * @return string
	 */
	public function get_handle()
	{
		return 'widgets';
	}
}

?>
