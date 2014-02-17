<?php
/**
 * ICE API: base configurable class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage base
 * @since 1.0
 */

/**
 * Make managing components with configurations easy
 *
 * @package ICE
 * @subpackage base
 */
interface ICE_Configurable
{
	/**
	 * Perform configuration steps
	 */
	public function configure();
}
