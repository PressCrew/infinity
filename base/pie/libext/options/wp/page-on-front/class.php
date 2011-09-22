<?php
/**
 * PIE API: option extensions, WP page on front class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/page' );

/**
 * WP page on front option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Wp_Page_On_Front
	extends Pie_Easy_Exts_Options_Page
{
	public function configure( $conf_map, $theme )
	{
		if ( !$conf_map->title ) {
			$conf_map->title = __('Front page displays');
		}

		if ( !$conf_map->description ) {
			$conf_map->description = __('Select the page to show on the front page');
		}

		parent::configure( $conf_map, $theme );
	}

	protected function get_option()
	{
		return get_option( 'page_on_front' );
	}

	protected function update_option( $value )
	{
		// make sure show on front is set correctly
		update_option( 'show_on_front', 'page' );

		// now its safe to set the page
		return update_option( 'page_on_front', $value );
	}

	protected function delete_option()
	{
		// revert show on front to posts
		update_option( 'show_on_front', 'posts' );
		
		// set page on front to zero
		update_option( 'page_on_front', 0 );

		return true;
	}
}

?>
