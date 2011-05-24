<?php
/**
 * PIE API: base policeable class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

/**
 * Make policy implementation easy
 *
 * Custom implementations are handled via policy objects. Any object which needs access to
 * the implementing policy must implement this interface.
 *
 * @package PIE
 * @subpackage base
 */
interface Pie_Easy_Policeable
{
	/**
	 * Return the policy
	 *
	 * @param Pie_Easy_Policy $policy
	 * @return Pie_Easy_Policy
	 */
	public function policy( Pie_Easy_Policy $policy = null );

}

?>
