<?php
/**
 * ICE API: base positionable class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage ui
 * @since 1.0
 */

ICE_Loader::load( 'ui/position' );

/**
 * Make positioning implementation easy
 *
 * @package ICE
 * @subpackage ui
 */
interface ICE_Positionable
{
	/**
	 * Set/Return the position
	 *
	 * @param ICE_Position $position
	 * @return ICE_Position
	 */
	public function position( ICE_Position $position = null );

}
