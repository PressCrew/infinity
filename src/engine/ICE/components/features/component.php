<?php
/**
 * ICE API: feature class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage features
 * @since 1.0
 */

ICE_Loader::load(
	'base/component'
);

/**
 * Make a feature easy
 *
 * @package ICE-components
 * @subpackage features
 */
abstract class ICE_Feature
	extends ICE_Component
{
	/**
	 * Check if theme supports this feature
	 *
	 * @return boolean
	 */
	public function check_support()
	{		
		if ( false === current_theme_supports( ICE_SLUG . self::API_DELIM . $this->get_group(), $this->get_name() ) ) {
			return false;
		}

		return parent::check_support();
	}
	
}
