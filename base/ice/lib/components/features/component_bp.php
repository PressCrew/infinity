<?php
/**
 * ICE API: base BuddyPress feature class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage features
 * @since 1.0
 */

ICE_Loader::load( 'components/features/component' );

/**
 * Make a BuddyPress feature easy
 *
 * @package ICE-components
 * @subpackage features
 */
abstract class ICE_Features_Feature_Bp
	extends ICE_Features_Feature
{
	/**
	 * Check if BuddyPress is active
	 *
	 * @return boolean
	 */
	public function supported()
	{
		// is buddypress active?
		if ( class_exists( 'BP_Component' ) ) {
			return parent::supported();
		}

		return false;
	}
	
}

?>
