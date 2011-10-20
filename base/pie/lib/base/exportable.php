<?php
/**
 * PIE API: base exportable class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

/**
 * Make exporting arbitrary data for objects easy
 *
 * @package PIE
 * @subpackage base
 */
interface Pie_Easy_Exportable
{
	/**
	 * Return a string of data to export
	 *
	 * @return string
	 */
	public function export();
}

?>
