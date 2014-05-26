<?php
/**
 * ICE API: compat helpers class file.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage utils
 * @since 1.0
 */

/**
 * Backwards compatibility helpers for the options component.
 *
 * @package ICE
 * @subpackage utils
 */
class ICE_Compat_Option
{
	/**
	 * Return the old option "api name".
	 *
	 * @param ICE_Option $option
	 * @return string
	 */
	static public function get_api_name( ICE_Option $option )
	{
		// return formatted api name
		return implode(
			ICE_Option::API_DELIM,
			array(
				ICE_Option::API_PREFIX,
				$option->get_hname(),
				ICE_ACTIVE_THEME
			)
		);
	}
}
