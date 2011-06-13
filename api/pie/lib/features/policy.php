<?php
/**
 * PIE API: features policy class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage features
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/policy' );

/**
 * Make customizing feature implementations easy
 *
 * This object is passed to each feature allowing the implementing API to
 * customize the implementation without confusing hooks and such.
 *
 * @package PIE
 * @subpackage features
 */
abstract class Pie_Easy_Features_Policy extends Pie_Easy_Policy
{
	/**
	 * @return string
	 */
	public function get_handle()
	{
		return 'features';
	}
}

?>
