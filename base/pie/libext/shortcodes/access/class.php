<?php
/**
 * PIE API: shortcode extensions, access shortcode class file
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
 * Access shortcode
 *
 * @package PIE-extensions
 * @subpackage shortcodes
 */
class Pie_Easy_Exts_Shortcodes_Access extends Pie_Easy_Shortcodes_Shortcode
{
	public function default_atts()
	{
		return array(
			'capability' => 'read'
		);
	}

	public function get_content()
	{
		if ( !is_null( parent::get_content() ) && !is_feed() && current_user_can( $this->get_att('capability') ) ) {
			return parent::get_content();
		}

		return null;
	}
}

?>
