<?php
/**
 * PIE API: base positionable class file
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
 * Make positioning implementation easy
 *
 * @package PIE
 * @subpackage base
 */
interface Pie_Easy_Positionable
{
	/**
	 * Set/Return the position
	 *
	 * @param Pie_Easy_Position $position
	 * @return Pie_Easy_Position
	 */
	public function position(Pie_Easy_Position $position = null );

}

?>
