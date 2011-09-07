<?php
/**
 * PIE API: option extensions, posts class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

/**
 * Posts option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Posts
	extends Pie_Easy_Options_Option
{
	/**
	 * Render posts checkboxes
	 *
	 * @see Pie_Easy_Options_Renderer::render_input_group
	 */
	public function render_field()
	{
		// get all posts
		$posts = get_posts();

		// field options
		$options = array();

		// build of options array
		foreach ( $posts as $post ) {
			$options[$post->ID] = apply_filters( 'the_title', $post->post_title, $post->ID );
		}

		// call the input group renderer
		$this->policy()->renderer()->render_input_group( 'checkbox', $options );
	}
}

?>
