<?php
/**
 * ICE API: shortcode extensions, access shortcode class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage shortcodes
 * @since 1.0
 */

/**
 * Access shortcode
 *
 * @package ICE-extensions
 * @subpackage shortcodes
 */
class ICE_Exts_Shortcodes_Access extends ICE_Shortcodes_Shortcode
{
	/**
	 */
	public function default_atts()
	{
		return array(
			'capability' => 'read',
			'message' => false
		);
	}

	/**
	 */
	public function get_content()
	{
		if ( !is_null( parent::get_content() ) && !is_feed() && current_user_can( $this->get_att('capability') ) ) {
			return parent::get_content();
		}

		return null;
	}
}

?>
