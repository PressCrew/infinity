<?php
/**
 * PIE API: option extensions, tag class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options-ext
 * @since 1.0
 */

/**
 * Tag option
 *
 * @package PIE
 * @subpackage options-ext
 */
class Pie_Easy_Exts_Option_Tag
	extends Pie_Easy_Options_Option
{
	/**
	 * Render a tag select box
	 *
	 * @see render_select
	 */
	public function render_field( Pie_Easy_Options_Option_Renderer $renderer )
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

		// call the select renderer
		$renderer->render_select( $options );
	}
}

?>
