<?php
/**
 * PIE API: option extensions, WP blog description class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/text' );

/**
 * WP blog description option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Option_Wp_Blogdescription
	extends Pie_Easy_Exts_Option_Text
{
	public function configure( $conf_map, $theme )
	{
		if ( !$conf_map->title ) {
			$conf_map->title = __('Tagline');
		}

		if ( !$conf_map->description ) {
			$conf_map->description = __('In a few words, explain what this site is about.');
		}

		parent::configure( $conf_map, $theme );
	}

	protected function get_option()
	{
		return get_option( 'blogdescription' );
	}

	protected function update_option( $value )
	{
		return update_option( 'blogdescription', $value );
	}
}

?>
