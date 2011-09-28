<?php
/**
 * PIE API: shortcode extensions, visitor shortcode class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage shortcodes
 * @since 1.0
 */

/**
 * Visitor shortcode
 *
 * Display content to requests which are not authenticated
 *
 * @package PIE-extensions
 * @subpackage shortcodes
 */
class Pie_Easy_Exts_Shortcodes_Visitor extends Pie_Easy_Shortcodes_Shortcode
{
	/**
	 */
	public function get_content()
	{
		// not logged in and not a feed?
		if ( !is_user_logged_in() && !is_feed() ) {
			// they are a visitor
			return parent::get_content();
		}

		return null;
	}
}

?>
