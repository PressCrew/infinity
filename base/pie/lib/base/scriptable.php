<?php
/**
 * PIE API: base scriptable class file
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
 * Make script implementation easy
 *
 * @package PIE
 * @subpackage base
 */
interface Pie_Easy_Scriptable
{
	/**
	 * Return script object
	 * 
	 * @return Pie_Easy_Script
	 */
	public function script();

}

?>
