<?php
/**
 * PIE API: base positionable class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage ui
 * @since 1.0
 */

Pie_Easy_Loader::load( 'ui/position' );

/**
 * Make positioning implementation easy
 *
 * @package PIE
 * @subpackage ui
 */
interface Pie_Easy_Positionable
{
	/**
	 * Set/Return the position
	 *
	 * @param Pie_Easy_Position $position
	 * @return Pie_Easy_Position
	 */
	public function position( Pie_Easy_Position $position = null );

}

?>
