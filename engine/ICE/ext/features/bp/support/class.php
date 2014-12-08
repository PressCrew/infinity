<?php
/**
 * ICE API: feature extensions, BuddyPress support feature class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage features
 * @since 1.0
 */

ICE_Loader::load( 'components/features/component' );

/**
 * BuddyPress support feature
 *
 * @package ICE-extensions
 * @subpackage features
 */
class ICE_Ext_Feature_Bp_Support
	extends ICE_Feature
{
	/**
	 */
	public function check_reqs()
	{
		// is buddypress active?
		if ( true === parent::check_reqs() ) {
			return $this->is_active();
		}

		return false;
	}

	/**
	 */
	public function init()
	{
		parent::init();

		// addtl filters
		add_filter( 'bp_no_access_mode', array( $this, 'use_wplogin' ) );

		// addtl actions
		add_action( 'open_sidebar', array( $this, 'message_notices' ) );
	}

	/**
	 * Add wrapper around message notices
	 */
	public function message_notices()
	{
		// render notification box ?>
		<div class="top-notification-box">
			<?php
				if ( is_user_logged_in() && bp_is_active( 'messages' ) ) {
					bp_message_get_notices();
				}
			?>
		</div><?php
	}

	/**
	 * We want to use wp_login
	 *
	 * @return integer
	 */
	public function use_wplogin()
	{
		return 2;
	}

	//
	// Helpers
	//

	/**
	 * Determine if BuddyPress is active and should load up
	 *
	 * @return boolean
	 */
	private function is_active()
	{
		if ( function_exists( 'bp_is_root_blog' ) ) {
			return bp_is_root_blog();
		} else {
			return false;
		}
	}
}
