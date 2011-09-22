<?php
/**
 * PIE API: base scriptable class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage ui
 * @since 1.0
 */

Pie_Easy_Loader::load( 'ui/script' );

/**
 * Make script implementation easy
 *
 * @package PIE
 * @subpackage ui
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
