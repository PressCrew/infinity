<?php
/**
 * PIE API: screens policy class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage screens
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/policy' );

/**
 * Make customizing screen implementations easy
 *
 * This object is passed to each screen allowing the implementing API to
 * customize the implementation without confusing hooks and such.
 *
 * @package PIE-components
 * @subpackage screens
 */
abstract class Pie_Easy_Screens_Policy extends Pie_Easy_Policy
{
	/**
	 * @return string
	 */
	public function get_handle()
	{
		return 'screens';
	}
}

?>
