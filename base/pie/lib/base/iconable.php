<?php
/**
 * PIE API: base iconable class file
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
 * Make icon implementation easy
 *
 * @package PIE
 * @subpackage base
 */
interface Pie_Easy_Iconable
{
	/**
	 * Set/Return the icon
	 *
	 * @param Pie_Easy_Icon $icon
	 * @return Pie_Easy_Icon
	 */
	public function icon( Pie_Easy_Icon $icon = null );

}

?>
