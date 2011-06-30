<?php
/**
 * PIE API: base styleable class file
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
 * Make style implementation easy
 *
 * @package PIE
 * @subpackage base
 */
interface Pie_Easy_Styleable
{
	/**
	 * Return style object
	 * 
	 * @return Pie_Easy_Style
	 */
	public function style();

}

?>
