<?php
/**
 * ICE API: feature extensions, BuddyPress Joyride tour class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage features
 * @since 1.0
 */

/**
 * BuddyPress tour feature
 *
 * @package ICE-extensions
 * @subpackage features
 */
class ICE_Ext_Feature_Bp_Tour
	extends ICE_Ext_Feature_Scripts_Joyride
{
	/**
	 */
	public function init()
	{
		// run parent
		parent::init();

		// set property defaults
		$this->title = __( 'BuddyPress Tour', 'infinity-engine' );
		$this->description = __( 'Gives users a tour of the BuddyPress activity stream', 'infinity-engine' );

		// add action for the activity element renderer
		add_action( 'bp_before_activity_loop', array( $this, 'render_activity_element' ) );
	}

	/**
	 * Render an empty placeholder element for the first tour target
	 */
	public function render_activity_element()
	{
		// simply spit out an empty element ?>
		<div align="center" id="activity-tour-favorites"></div><?php
	}

}
