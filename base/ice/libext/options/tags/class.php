<?php
/**
 * ICE API: option extensions, tags class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage options
 * @since 1.0
 */

ICE_Loader::load_ext( 'options/checkbox' );

/**
 * Tags option
 *
 * @package ICE-extensions
 * @subpackage options
 */
class ICE_Exts_Options_Tags
	extends ICE_Exts_Options_Checkbox
		implements ICE_Options_Option_Auto_Field
{
	/**
	 */
	public function load_field_options()
	{
		$args = array(
			'hide_empty' => false
		);

		// get all tags
		$tags = get_tags( $args );

		// field options
		$options = array();

		// build of options array
		foreach ( $tags as $tag ) {
			$options[$tag->term_id] = $tag->name;
		}

		return $options;
	}
}

?>
