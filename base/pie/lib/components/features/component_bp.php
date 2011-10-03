<?php
/**
 * PIE API: base BuddyPress feature class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage features
 * @since 1.0
 */

Pie_Easy_Loader::load( 'components/features/component' );

/**
 * Make a BuddyPress feature easy
 *
 * @package PIE-components
 * @subpackage features
 */
abstract class Pie_Easy_Features_Feature_Bp
	extends Pie_Easy_Features_Feature
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
