<?php
/**
 * PIE API: base configurable class file
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
 * Make managing components with configurations easy
 *
 * @package PIE
 * @subpackage base
 */
interface Pie_Easy_Configurable
{
	/**
	 * Accept a configuration
	 *
	 * @param Pie_Easy_Init_Config $config
	 * @return boolean
	 */
	public function configure( Pie_Easy_Init_Config $config );
}

?>
