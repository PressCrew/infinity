<?php
/**
 * PIE API: base iconable class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage ui
 * @since 1.0
 */

Pie_Easy_Loader::load( 'ui/icon' );

/**
 * Make icon implementation easy
 *
 * @package PIE
 * @subpackage ui
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
